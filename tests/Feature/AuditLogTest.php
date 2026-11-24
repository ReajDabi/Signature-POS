<?php

namespace Tests\Feature;

use App\Exceptions\ImmutableRecordException;
use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Technician;
use App\Models\User;
use App\Services\AuditLogger;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_records_who_what_and_where(): void
    {
        $branch = Branch::factory()->create();
        $manager = User::factory()->manager($branch)->create();
        $technician = Technician::factory()->create(['branch_id' => $branch->id]);

        $this->actingAs($manager);
        app(TenantContext::class)->set($branch->id);

        $log = app(AuditLogger::class)->record('technician.rate_changed', $technician, ['rate' => '10.00'], ['rate' => '12.00']);

        $this->assertSame($branch->id, $log->branch_id);
        $this->assertSame($manager->id, $log->user_id);
        $this->assertSame('Technician', $log->subject_type);
        $this->assertSame(['rate' => '12.00'], $log->new_value);
    }

    public function test_audit_rows_cannot_be_updated(): void
    {
        $log = AuditLog::create(['action' => 'test']);

        $this->expectException(ImmutableRecordException::class);

        $log->update(['action' => 'tampered']);
    }

    public function test_audit_rows_cannot_be_deleted(): void
    {
        $log = AuditLog::create(['action' => 'test']);

        $this->expectException(ImmutableRecordException::class);

        $log->delete();
    }

    public function test_a_branch_only_reads_its_own_audit_rows(): void
    {
        [$a, $b] = [Branch::factory()->create(), Branch::factory()->create()];
        AuditLog::create(['action' => 'a', 'branch_id' => $a->id]);
        AuditLog::create(['action' => 'b', 'branch_id' => $b->id]);

        app(TenantContext::class)->set($a->id);

        $this->assertSame(1, AuditLog::count());
    }
}
