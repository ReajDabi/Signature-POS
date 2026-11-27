<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class BranchPortalUiTest extends TestCase
{
    #[TestWith(['manager.dashboard', '/manager', 'Tagum City Center', 'Stock Watchlist'])]
    #[TestWith(['manager.transactions.index', '/manager/transactions', 'Branch Transactions', 'TX-TCC-041'])]
    #[TestWith(['manager.inventory.index', '/manager/inventory', 'Branch Inventory', 'Rebond Cream'])]
    #[TestWith(['manager.imports.index', '/manager/imports', 'CSV Imports', 'tcc-nov-restock.csv'])]
    #[TestWith(['manager.technicians.index', '/manager/technicians', 'Technicians & Commissions', 'Maria Santos'])]
    #[TestWith(['manager.catalog.index', '/manager/catalog', 'Branch Discounts', 'Holiday Glam 15% Off'])]
    #[TestWith(['manager.staff.index', '/manager/staff', 'Branch Staff', 'manager.center@signature.test'])]
    #[TestWith(['manager.audit.index', '/manager/audit', 'Branch Audit Log', 'Read-Only Ledger'])]
    public function test_manager_portal_route_renders_expected_view_and_content(
        string $routeName,
        string $expectedPath,
        string $expectedHeading,
        string $expectedBodyText,
    ): void {
        $this->assertSame(url($expectedPath), route($routeName));

        $this->get(route($routeName))
            ->assertOk()
            ->assertSee('Tagum City Center · TCC')
            ->assertSee($expectedHeading)
            ->assertSee($expectedBodyText)
            ->assertDontSee('&amp;amp;', false)
            ->assertDontSee('Soon');
    }

    public function test_manager_dashboard_and_inventory_render_9px_low_stock_badges(): void
    {
        $this->get(route('manager.dashboard'))
            ->assertOk()
            ->assertSee('sig-low-stock-badge');

        $this->get(route('manager.inventory.index'))
            ->assertOk()
            ->assertSee('sig-low-stock-badge');
    }

    #[TestWith(['cashier.pos', '/cashier', 'Current Ticket', 'Holiday Glam 15% Off (HQ Promo)'])]
    #[TestWith(['cashier.transactions.index', '/cashier/transactions', 'Shift Transactions', 'TX-TCC-041'])]
    public function test_cashier_portal_route_renders_expected_view_and_content(
        string $routeName,
        string $expectedPath,
        string $expectedHeading,
        string $expectedBodyText,
    ): void {
        $this->assertSame(url($expectedPath), route($routeName));

        $this->get(route($routeName))
            ->assertOk()
            ->assertSee('Tagum City Center · TCC')
            ->assertSee($expectedHeading)
            ->assertSee($expectedBodyText)
            ->assertDontSee('&amp;amp;', false)
            ->assertDontSee('Soon');
    }
}
