<?php

use App\Models\Branch;
use App\Models\Service;
use App\Models\Technician;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('creates a transaction with correct totals and commission entries', function () {
    $branch     = Branch::factory()->create();
    $cashier    = User::factory()->cashier()->for($branch)->create();
    $service    = Service::factory()->for($branch)->create(['price' => '150.00']);
    $technician = Technician::factory()->for($branch)->create(['commission_rate' => '10.00']);

    $items = Collection::make([
        ['service_id' => $service->id, 'technician_id' => $technician->id, 'qty' => 1],
    ]);

    $this->actingAs($cashier);

    $transaction = app(CheckoutService::class)->process(
        $branch->id, $cashier->id, $items
    );

    expect($transaction->total)->toBe('150.00')
        ->and($transaction->items)->toHaveCount(1)
        ->and($transaction->items->first()->commission)->not->toBeNull();

    $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'total' => '150.00']);
    $this->assertDatabaseHas('audit_logs', ['action' => 'checkout', 'subject_id' => $transaction->id]);
});

it('rolls back the entire checkout when stock deduction fails', function () {
    $branch     = Branch::factory()->create();
    $cashier    = User::factory()->cashier()->for($branch)->create();
    $service    = Service::factory()->for($branch)->create(['price' => '200.00']);
    $technician = Technician::factory()->for($branch)->create();

    // Force InventoryService to throw so we can test atomic rollback
    $this->mock(\App\Services\InventoryService::class)
        ->shouldReceive('deductForService')
        ->andThrow(new \RuntimeException('Simulated stock failure'));

    $items = Collection::make([
        ['service_id' => $service->id, 'technician_id' => $technician->id, 'qty' => 1],
    ]);

    $this->actingAs($cashier);

    expect(fn () => app(CheckoutService::class)->process($branch->id, $cashier->id, $items))
        ->toThrow(\RuntimeException::class);

    // Nothing should be persisted
    $this->assertDatabaseCount('transactions', 0);
    $this->assertDatabaseCount('transaction_items', 0);
    $this->assertDatabaseCount('commissions', 0);
    $this->assertDatabaseCount('audit_logs', 0);
});

it('prevents a cashier from checking out for a different branch', function () {
    $branch1 = Branch::factory()->create();
    $branch2 = Branch::factory()->create();
    $cashier = User::factory()->cashier()->for($branch1)->create();
    $service = Service::factory()->for($branch2)->create();

    $this->actingAs($cashier);

    // Service belongs to branch2 but cashier is in branch1 — must be rejected
    expect(fn () => app(CheckoutService::class)->process(
        $branch2->id, $cashier->id,
        Collection::make([['service_id' => $service->id, 'technician_id' => 1, 'qty' => 1]])
    ))->toThrow(\Illuminate\Auth\Access\AuthorizationException::class);
});
