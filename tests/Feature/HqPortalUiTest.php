<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class HqPortalUiTest extends TestCase
{
    #[TestWith(['hq.dashboard', '/hq', 'Executive Dashboard', 'Daily Revenue Comparison'])]
    #[TestWith(['hq.branches.index', '/hq/branches', 'Salon Branches', 'Tagum City Center'])]
    #[TestWith(['hq.services.index', '/hq/services', 'Global Services & Bill of Materials', 'Hair Color (Full)'])]
    #[TestWith(['hq.products.index', '/hq/products', 'Global Products Catalog', 'Hair Serum 100ml'])]
    #[TestWith(['hq.users.index', '/hq/users', 'Users & Role Assignments', 'hq@signature.test'])]
    #[TestWith(['hq.audit.index', '/hq/audit', 'Network Audit Trail', 'Append-Only'])]
    #[TestWith(['hq.reports.index', '/hq/reports', 'Cross-Branch Sales & Commission Reports', 'Technician Commission Breakdown'])]
    public function test_hq_portal_route_renders_expected_view_and_content(
        string $routeName,
        string $expectedPath,
        string $expectedHeading,
        string $expectedBodyText,
    ): void {
        $this->assertSame(url($expectedPath), route($routeName));

        $this->get(route($routeName))
            ->assertOk()
            ->assertSee('HQ Global Scope')
            ->assertSee($expectedHeading)
            ->assertSee($expectedBodyText)
            ->assertDontSee('&amp;amp;', false)
            ->assertDontSee('Soon');
    }

    public function test_hq_dashboard_and_products_render_9px_low_stock_badges(): void
    {
        $this->get(route('hq.dashboard'))
            ->assertOk()
            ->assertSee('sig-low-stock-badge')
            ->assertSee('Low-Stock Watchlist');

        $this->get(route('hq.products.index'))
            ->assertOk()
            ->assertSee('sig-low-stock-badge');
    }

    public function test_login_post_redirects_to_hq_dashboard_during_ui_phase(): void
    {
        $this->post(route('login.store'), [
            'email' => 'hq@signature.test',
            'password' => 'password',
        ])->assertRedirect(route('hq.dashboard'));
    }
}
