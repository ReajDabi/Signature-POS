<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

Route::get('/', fn (): View => view('auth.login'))->name('home');

Route::get('/login', fn (): View => view('auth.login'))->name('login');

// Temporary UI-phase handler until Authentication backend step replaces it.
Route::post('/login', function (): RedirectResponse {
    return redirect()
        ->route('ui.foundation', ['portal' => 'hq'])
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
