@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-5 sm:p-6',
])

<div {{ $attributes->merge(['class' => 'sig-card rounded-2xl']) }}>
    @if ($title || isset($actions))
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-theme px-5 py-3.5 sm:px-6">
            <div>
                @if ($title)
                    <h3 class="font-display text-base font-semibold text-theme-primary">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="mt-0.5 text-xs text-theme-muted">{{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($actions))
                <div class="flex flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>
