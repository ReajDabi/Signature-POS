<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Support\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Turns a BusinessException into a safe response. Users get a friendly
 * message and a reference code; the log gets the full context.
 */
final class ErrorResponder
{
    public static function render(BusinessException $e, Request $request): Response
    {
        $reference = strtoupper(Str::random(8));

        Log::warning($e->getMessage(), [
            'reference' => $reference,
            'code' => $e->errorCode(),
            'user_id' => $request->user()?->getAuthIdentifier(),
            'branch_id' => app(TenantContext::class)->branchId(),
            'route' => $request->path(),
        ]);

        if ($request->expectsJson()) {
            return new JsonResponse([
                'message' => $e->userMessage(),
                'code' => $e->errorCode(),
                'reference' => $reference,
            ], $e->status());
        }

        if ($e->status() === 403 || $e->status() >= 500) {
            return response()->view('errors.minimal', [
                'code' => $e->status(),
                'message' => $e->userMessage().' (ref '.$reference.')',
            ], $e->status());
        }

        return back()->withInput()->with('error', [
            'message' => $e->userMessage(),
            'reference' => $reference,
        ]);
    }
}
