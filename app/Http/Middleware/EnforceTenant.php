<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Branch;
use App\Models\User;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fails closed: a branch user without an active branch gets a 403, HQ users
 * run unscoped, and everyone else is pinned to their own branch.
 */
final class EnforceTenant
{
    public function __construct(private readonly TenantContext $tenant) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user instanceof User || $request->routeIs('logout')) {
            return $next($request);
        }

        if (! $user->is_active) {
            Auth::guard('web')->logout();
            abort(403, 'Your account is disabled. Please contact your administrator.');
        }

        if ($user->isHq()) {
            $this->tenant->set(null);

            return $next($request);
        }

        $branchIsActive = $user->branch_id !== null
            && Branch::query()->whereKey($user->branch_id)->where('is_active', true)->exists();

        if (! $branchIsActive) {
            Log::warning('Branch user blocked: missing or inactive branch', ['user_id' => $user->id]);
            abort(403, 'Your branch is not available. Please contact HQ.');
        }

        $this->tenant->set($user->branch_id);

        return $next($request);
    }
}
