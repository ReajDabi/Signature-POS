<x-layouts.guest title="Sign In">
    <div class="w-full max-w-md space-y-5">
        <div class="sig-card rounded-3xl p-6 sm:p-8">
            <div class="mb-6 space-y-1.5 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-gold-accent">
                    Signature Salon & Nail Spa
                </p>
                <h1 class="font-display text-3xl font-semibold tracking-tight text-theme-primary">
                    Welcome Back
                </h1>
                <p class="text-xs text-theme-muted">
                    Sign in to continue
                </p>
            </div>

            <x-ui.alert />

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-medium text-theme-secondary">
                        Email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="you@signature.test"
                        class="sig-input w-full rounded-xl px-4 py-2.5 text-sm"
                    >
                </div>

                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <label for="password" class="block text-xs font-medium text-theme-secondary">
                        Password
                    </label>

                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            placeholder="Enter password"
                            class="sig-input w-full rounded-xl py-2.5 pl-4 pr-11 text-sm"
                        >
                        <button
                            type="button"
                            x-on:click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 text-theme-muted hover:text-gold-accent cursor-pointer"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                        >
                            <span x-show="!showPassword">
                                <x-ui.icon name="eye" class="h-4 w-4" />
                            </span>
                            <span x-show="showPassword" x-cloak>
                                <x-ui.icon name="eye-off" class="h-4 w-4" />
                            </span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 text-xs pt-1">
                    <label class="inline-flex items-center gap-2 text-theme-secondary cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-theme accent-[#d9b77a]"
                        >
                        <span>Keep signed in</span>
                    </label>

                    <span class="inline-flex items-center gap-1 text-theme-muted">
                        <x-ui.icon name="lock" class="h-3.5 w-3.5 text-gold-accent" />
                        <span>PHT</span>
                    </span>
                </div>

                <button
                    type="submit"
                    class="sig-btn-gold w-full rounded-xl px-5 py-3 text-sm tracking-wide cursor-pointer shadow-sm"
                >
                    Sign In to Signature POS
                </button>
            </form>
        </div>

        @if (app()->environment(['local', 'testing']) && Route::has('ui.foundation'))
            <div class="sig-card rounded-2xl p-4 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-gold-accent">
                        Demo Portals
                    </p>
                    @if (Route::has('hq.dashboard'))
                        <a href="{{ route('hq.dashboard') }}" class="text-xs font-medium text-gold-accent hover:underline">
                            Open HQ Portal →
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <a
                        href="{{ Route::has('hq.dashboard') ? route('hq.dashboard') : route('ui.foundation', ['portal' => 'hq']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        HQ Portal
                    </a>
                    <a
                        href="{{ Route::has('manager.dashboard') ? route('manager.dashboard') : route('ui.foundation', ['portal' => 'manager']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        Manager
                    </a>
                    <a
                        href="{{ Route::has('cashier.pos') ? route('cashier.pos') : route('ui.foundation', ['portal' => 'cashier']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        Cashier POS
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-layouts.guest>
