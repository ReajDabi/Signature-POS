@props([
    'title' => 'Sign In',
])

<x-layouts.base :title="$title">
    <div class="relative min-h-screen overflow-hidden flex flex-col justify-between px-4 py-6 sm:px-6 lg:px-8">
        {{-- Ambient champagne aura --}}
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-36 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-[#d9b77a]/12 blur-3xl"></div>
            <div class="absolute -bottom-40 right-1/4 h-80 w-80 rounded-full bg-[#d9b77a]/8 blur-3xl"></div>
        </div>

        <header class="relative z-10 mx-auto flex w-full max-w-6xl items-center justify-between gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3 group">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-theme-strong bg-gold-soft font-display text-2xl font-semibold text-gold-accent transition group-hover:scale-105">
                    S
                </span>
                <div>
                    <span class="block font-display text-xl font-semibold tracking-wide text-theme-primary leading-none">
                        Signature
                    </span>
                    <span class="mt-1 block text-[10px] font-medium uppercase tracking-[0.22em] text-gold-accent">
                        Styled in the Cloud
                    </span>
                </div>
            </a>

            <x-ui.theme-toggle />
        </header>

        <main class="relative z-10 my-8 flex flex-1 items-center justify-center">
            {{ $slot }}
        </main>

        <footer class="relative z-10 mx-auto flex w-full max-w-6xl flex-col items-center justify-between gap-2 border-t border-theme pt-4 text-xs text-theme-muted sm:flex-row">
            <p>Signature Salon &amp; Nail Spa · Tagum City</p>
            <p>Multi-Tenant Branch Governance &amp; POS · PHP (₱) · Asia/Manila</p>
        </footer>
    </div>
</x-layouts.base>
