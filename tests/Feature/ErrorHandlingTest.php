<?php

namespace Tests\Feature;

use App\Exceptions\TenantViolationException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/** Fails until the withExceptions() snippet from SETUP.md is added to bootstrap/app.php. */
class ErrorHandlingTest extends TestCase
{
    public function test_business_exceptions_render_json_with_a_reference_code(): void
    {
        Route::middleware('web')->get('/_boom', fn () => throw new TenantViolationException);

        $this->getJson('/_boom')
            ->assertForbidden()
            ->assertJsonPath('code', 'TENANT_VIOLATION')
            ->assertJsonStructure(['message', 'code', 'reference']);
    }
}
