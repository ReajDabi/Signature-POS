@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between']) }}>
    <div class="space-y-0.5">
        @if ($eyebrow)
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-gold-accent">
                {{ $eyebrow }}
            </p>
        @endif
        <h2 class="font-display text-xl font-semibold tracking-tight text-theme-primary sm:text-2xl leading-tight">
            {{ $title }}
        </h2>
        @if ($description)
            <p class="text-xs text-theme-muted">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
