@props([
    'title' => 'Sign In',
])

<x-layouts.base :title="$title">
    <div class="relative min-h-screen overflow-hidden flex flex-col justify-between px-4 py-6 sm:px-6 lg:px-8">
        <div class="pointer-events-none fixed inset-0 z-0 bg-[#faf7f2] bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/login-background.png') }}')" aria-hidden="true">
            <div class="absolute inset-0 bg-[#eadcc3]/35 dark:bg-[#0e0e10]/75"></div>
        </div>

        <header class="relative z-10 mx-auto flex w-full max-w-6xl items-center justify-between gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-3 group">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-theme-strong bg-gold-soft font-display text-xl font-bold text-gold-accent transition group-hover:scale-105">
                    S
                </span>
                <div>
                    <span class="block font-display text-lg font-semibold tracking-tight text-theme-primary leading-none">
                        Signature
                    </span>
                    <span class="mt-1 block text-[10px] font-medium uppercase tracking-[0.2em] text-gold-accent">
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
        </footer>
    </div>
</x-layouts.base>
