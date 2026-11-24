<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Database-level integrity: bad money or stock values are rejected even if
 * application code has a bug. MySQL only (SQLite cannot ALTER ... ADD CHECK).
 */
return new class extends Migration
{
    private array $checks = [
        'services' => ['chk_services_price' => 'price > 0'],
        'technicians' => ['chk_technicians_rate' => 'commission_rate BETWEEN 0 AND 100'],
        'discounts' => ['chk_discounts_value' => 'value >= 0'],
        'branch_inventory' => ['chk_inventory_qty' => 'qty_on_hand >= 0 AND low_stock_threshold >= 0'],
        'transactions' => ['chk_transactions_math' => 'subtotal >= 0 AND discount >= 0 AND total = subtotal - discount'],
        'transaction_items' => ['chk_items_math' => 'qty > 0 AND line_total = unit_price * qty'],
        'commissions' => ['chk_commissions_values' => 'rate BETWEEN 0 AND 100 AND amount >= 0'],
    ];

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->checks as $table => $constraints) {
            foreach ($constraints as $name => $expression) {
                DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$name} CHECK ({$expression})");
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->checks as $table => $constraints) {
            foreach (array_keys($constraints) as $name) {
                DB::statement("ALTER TABLE {$table} DROP CHECK {$name}");
            }
        }
    }
};
