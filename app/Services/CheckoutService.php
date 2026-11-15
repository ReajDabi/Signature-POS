<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\AuditLog;
use App\Jobs\SendLowStockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * Atomic checkout pipeline.
 *
 * All writes (transaction, line items, commissions, stock deductions,
 * audit log) happen inside a single DB::transaction().  If any step
 * fails the entire checkout rolls back — no partial records.
 *
 * Usage:
 *   app(CheckoutService::class)->process($branchId, $cashierId, $items, $discountId);
 *
 * $items: Collection of arrays:
 *   ['service_id' => 1, 'technician_id' => 2, 'qty' => 1]
 */
class CheckoutService
{
    public function __construct(
        private CommissionService $commissions,
        private InventoryService $inventory,
    ) {}

    /**
     * @param  int        $branchId
     * @param  int        $cashierId
     * @param  Collection $items
     * @param  int|null   $discountId
     * @return Transaction
     */
    public function process(
        int $branchId,
        int $cashierId,
        Collection $items,
        ?int $discountId = null
    ): Transaction {
        return DB::transaction(function () use ($branchId, $cashierId, $items, $discountId) {
            // 1. Build line items and compute subtotal
            $lineItems = $this->resolveLineItems($items, $branchId);
            $subtotal  = $lineItems->sum('line_total');
            $discount  = $this->resolveDiscount($discountId, $subtotal, $branchId);
            $total     = bcsub((string) $subtotal, (string) $discount, 2);

            // 2. Create the transaction header
            $transaction = Transaction::create([
                'branch_id'   => $branchId,
                'cashier_id'  => $cashierId,
                'discount_id' => $discountId,
                'subtotal'    => $subtotal,
                'discount'    => $discount,
                'total'       => $total,
                'status'      => 'completed',
            ]);

            // 3. Persist line items + trigger commissions + deduct stock
            foreach ($lineItems as $line) {
                $item = TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'branch_id'      => $branchId,
                    'service_id'     => $line['service_id'],
                    'technician_id'  => $line['technician_id'],
                    'qty'            => $line['qty'],
                    'unit_price'     => $line['unit_price'],
                    'line_total'     => $line['line_total'],
                ]);

                // Commission ledger entry (exact decimal math)
                $this->commissions->record($item);

                // Deduct consumed supplies; dispatches alert job if threshold reached
                $this->inventory->deductForService($item->service_id, $branchId);
            }

            // 4. Append audit log
            AuditLog::create([
                'branch_id'  => $branchId,
                'user_id'    => $cashierId,
                'action'     => 'checkout',
                'subject'    => 'transactions',
                'subject_id' => $transaction->id,
                'new_value'  => json_encode(['total' => $total, 'items' => $lineItems->count()]),
            ]);

            return $transaction->load('items.service', 'items.technician');
        });
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function resolveLineItems(Collection $items, int $branchId): Collection
    {
        return $items->map(function (array $item) use ($branchId) {
            $service = \App\Models\Service::findOrFail($item['service_id']);

            // All arithmetic uses bcmath — never floats
            $lineTotal = bcmul((string) $service->price, (string) ($item['qty'] ?? 1), 2);

            return [
                'service_id'    => $service->id,
                'technician_id' => $item['technician_id'],
                'qty'           => $item['qty'] ?? 1,
                'unit_price'    => $service->price,
                'line_total'    => $lineTotal,
            ];
        });
    }

    private function resolveDiscount(?int $discountId, string $subtotal, int $branchId): string
    {
        if ($discountId === null) {
            return '0.00';
        }

        $discount = \App\Models\Discount::where('branch_id', $branchId)
            ->findOrFail($discountId);

        if ($discount->type === 'percent') {
            return bcdiv(bcmul($subtotal, (string) $discount->value, 4), '100', 2);
        }

        return (string) $discount->value; // flat amount
    }
}
