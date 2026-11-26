@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="space-y-1">
        @if ($eyebrow)
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-gold-accent">
                {{ $eyebrow }}
            </p>
        @endif
        <h2 class="font-display text-2xl font-semibold tracking-tight text-theme-primary sm:text-[1.75rem] leading-tight">
            {{ $title }}
        </h2>
        @if ($description)
            <p class="text-sm text-theme-secondary">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex flex-wrap items-center gap-2.5">
            {{ $actions }}
        </div>
    @endif
</div>
