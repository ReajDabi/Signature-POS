<x-layouts.guest title="Sign In">
    <div class="w-full max-w-md space-y-6">
        <div class="sig-card rounded-3xl p-6 sm:p-8">
            <div class="mb-7 space-y-2 text-center">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gold-accent">
                    Signature Salon &amp; Nail Spa
                </p>
                <h1 class="font-display text-4xl font-semibold tracking-tight text-theme-primary">
                    Welcome Back
                </h1>
                <p class="text-sm text-theme-secondary">
                    Sign in to access your HQ console, branch workspace, or cashier POS terminal.
                </p>
            </div>

            <x-ui.alert />

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold uppercase tracking-[0.14em] text-theme-secondary">
                        Email Address
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
                        class="sig-input w-full rounded-xl px-4 py-3 text-sm"
                    >
                </div>

                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-[0.14em] text-theme-secondary">
                            Password
                        </label>
                        <span class="text-[11px] text-theme-muted">Branch-locked access</span>
                    </div>

                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="sig-input w-full rounded-xl py-3 pl-4 pr-11 text-sm"
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

                <div class="flex items-center justify-between gap-3 text-xs">
                    <label class="inline-flex items-center gap-2 text-theme-secondary cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-theme accent-[#d9b77a]"
                        >
                        <span>Keep terminal signed in</span>
                    </label>

                    <span class="inline-flex items-center gap-1 text-theme-muted">
                        <x-ui.icon name="lock" class="h-3.5 w-3.5 text-gold-accent" />
                        <span>Asia/Manila</span>
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
            <div class="sig-card rounded-2xl p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gold-accent">
                        UI Prototype Previews
                    </p>
                    <span class="text-[11px] text-theme-muted">Step 2 · HQ Ready</span>
                </div>

                @if (Route::has('hq.dashboard'))
                    <a
                        href="{{ route('hq.dashboard') }}"
                        class="sig-btn-gold flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold"
                    >
                        <span>Open HQ Super Admin Portal</span>
                    </a>
                @endif

                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <a
                        href="{{ route('ui.foundation', ['portal' => 'hq']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        HQ Shell
                    </a>
                    <a
                        href="{{ route('ui.foundation', ['portal' => 'manager']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        Manager Shell
                    </a>
                    <a
                        href="{{ route('ui.foundation', ['portal' => 'cashier']) }}"
                        class="sig-btn-ghost rounded-xl px-3 py-2 font-medium"
                    >
                        Cashier Shell
                    </a>
                </div>

                <div class="border-t border-theme pt-2.5 text-[11px] text-theme-muted space-y-1">
                    <p>Seeded demo logins (password: <span class="font-mono text-theme-secondary">password</span>):</p>
                    <p class="font-mono text-[10px] text-theme-secondary">hq@signature.test · manager.center@signature.test · cashier.center@signature.test</p>
                </div>
            </div>
        @endif
    </div>
</x-layouts.guest>
