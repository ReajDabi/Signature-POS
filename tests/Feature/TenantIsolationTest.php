<?php

namespace Tests\Feature;

use App\Exceptions\TenantViolationException;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Technician;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private function actAs(?Branch $branch): void
    {
        app(TenantContext::class)->set($branch?->id);
    }

    public function test_scoped_queries_only_return_own_branch_rows(): void
    {
        [$a, $b] = [Branch::factory()->create(), Branch::factory()->create()];
        Technician::factory()->create(['branch_id' => $a->id]);
        Technician::factory()->count(2)->create(['branch_id' => $b->id]);

        $this->actAs($a);

        $this->assertSame(1, Technician::count());
    }

    public function test_hq_context_sees_every_branch(): void
    {
        Technician::factory()->count(2)->create();

        $this->actAs(null);

        $this->assertSame(2, Technician::count());
    }

    public function test_branch_id_is_filled_from_the_context(): void
    {
        $a = Branch::factory()->create();
        $this->actAs($a);

        $technician = Technician::create(['name' => 'Test Tech', 'commission_rate' => '12.00']);

        $this->assertSame($a->id, $technician->branch_id);
    }

    public function test_writing_to_another_branch_is_refused(): void
    {
        [$a, $b] = [Branch::factory()->create(), Branch::factory()->create()];
        $this->actAs($a);

        $this->expectException(TenantViolationException::class);

        Technician::create(['branch_id' => $b->id, 'name' => 'Intruder', 'commission_rate' => '10.00']);
    }

    public function test_a_row_cannot_be_moved_to_another_branch(): void
    {
        [$a, $b] = [Branch::factory()->create(), Branch::factory()->create()];
        $technician = Technician::factory()->create(['branch_id' => $a->id]);

        $this->expectException(TenantViolationException::class);

        $technician->update(['branch_id' => $b->id]);
    }

    public function test_catalog_shows_global_services_plus_own_branch_services(): void
    {
        [$a, $b] = [Branch::factory()->create(), Branch::factory()->create()];
        Service::factory()->create();
        Service::factory()->forBranch($a)->create();
        Service::factory()->forBranch($b)->create();

        $this->actAs($a);

        $this->assertSame(2, Service::count());
    }

    public function test_branch_user_is_pinned_to_their_branch(): void
    {
        Route::middleware('web')->get('/_probe', fn () => response()->json(['branch' => app(TenantContext::class)->branchId()]));
        $branch = Branch::factory()->create();

        $this->actingAs(User::factory()->manager($branch)->create())
            ->getJson('/_probe')
            ->assertOk()
            ->assertJson(['branch' => $branch->id]);
    }

    public function test_hq_user_runs_unscoped(): void
    {
        Route::middleware('web')->get('/_probe', fn () => response()->json(['branch' => app(TenantContext::class)->branchId()]));

        $this->actingAs(User::factory()->hq()->create())
            ->getJson('/_probe')
            ->assertOk()
            ->assertJson(['branch' => null]);
    }

    public function test_branch_user_without_a_branch_is_blocked(): void
    {
        Route::middleware('web')->get('/_probe', fn () => response()->json(['ok' => true]));

        $this->actingAs(User::factory()->manager()->state(['branch_id' => null])->create())
            ->getJson('/_probe')
            ->assertForbidden();
    }

    public function test_users_of_an_inactive_branch_are_blocked(): void
    {
        Route::middleware('web')->get('/_probe', fn () => response()->json(['ok' => true]));
        $branch = Branch::factory()->inactive()->create();

        $this->actingAs(User::factory()->cashier($branch)->create())
            ->getJson('/_probe')
            ->assertForbidden();
    }

    public function test_disabled_users_are_blocked(): void
    {
        Route::middleware('web')->get('/_probe', fn () => response()->json(['ok' => true]));

        $this->actingAs(User::factory()->hq()->inactive()->create())
            ->getJson('/_probe')
            ->assertForbidden();
    }
}
