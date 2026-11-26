@props([
    'name',
    'title',
    'subtitle' => null,
    'maxWidth' => 'max-w-lg',
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') { open = true }"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}' || !$event.detail) { open = false }"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
>
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="open = false"
        class="fixed inset-0 bg-black/70 backdrop-blur-xs"
    ></div>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="sig-card relative z-10 w-full {{ $maxWidth }} rounded-2xl overflow-hidden"
    >
        <div class="flex items-start justify-between gap-4 border-b border-theme px-6 py-4">
            <div>
                <h3 class="font-display text-xl font-semibold text-theme-primary">{{ $title }}</h3>
                @if ($subtitle)
                    <p class="mt-0.5 text-xs text-theme-muted">{{ $subtitle }}</p>
                @endif
            </div>
            <button
                type="button"
                x-on:click="open = false"
                class="rounded-lg p-1.5 text-theme-muted hover:bg-theme-elevated hover:text-theme-primary cursor-pointer"
                aria-label="Close modal"
            >
                <x-ui.icon name="close" class="h-5 w-5" />
            </button>
        </div>

        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
