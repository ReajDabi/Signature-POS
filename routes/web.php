<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::get('/', fn (): View => view('auth.login'))->name('home');

Route::get('/login', fn (): View => view('auth.login'))->name('login');

// Temporary UI-phase handler until Authentication backend step replaces it.
Route::post('/login', function (): RedirectResponse {
    return redirect()
        ->route('hq.dashboard')
        ->with('status', 'UI prototype preview active. Authentication backend will be wired after the UI phase.');
})->name('login.store');

Route::post('/logout', function (): RedirectResponse {
    return redirect()
        ->route('login')
        ->with('status', 'Signed out of Signature POS.');
})->name('logout');

Route::get('/ui-foundation/{portal?}', function (string $portal = 'hq'): View {
    if (! in_array($portal, ['hq', 'manager', 'cashier'], true)) {
        abort(404);
    }

    return view('ui.shell-preview', ['portal' => $portal]);
})->name('ui.foundation');

// HQ Super Admin Portal UI routes (UI Step 2 prototype views)
Route::prefix('hq')->name('hq.')->group(function (): void {
    Route::get('/', fn (): View => view('hq.dashboard'))->name('dashboard');
    Route::get('/branches', fn (): View => view('hq.branches.index'))->name('branches.index');
    Route::get('/services', fn (): View => view('hq.services.index'))->name('services.index');
    Route::get('/products', fn (): View => view('hq.products.index'))->name('products.index');
    Route::get('/users', fn (): View => view('hq.users.index'))->name('users.index');
    Route::get('/audit', fn (): View => view('hq.audit.index'))->name('audit.index');
    Route::get('/reports', fn (): View => view('hq.reports.index'))->name('reports.index');
});
