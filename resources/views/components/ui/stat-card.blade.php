@props([
    'label',
    'value',
    'hint' => null,
    'icon' => 'sparkle',
    'badge' => null,
    'badgeTone' => 'gold',
])

@php
    $badgeClasses = match ($badgeTone) {
        'emerald' => 'bg-emerald-500/12 text-emerald-700 dark:text-emerald-300 border-emerald-500/25',
        'rose' => 'bg-rose-500/12 text-rose-700 dark:text-rose-300 border-rose-500/25',
        'amber' => 'bg-amber-500/15 text-amber-800 dark:text-amber-300 border-amber-500/30',
        default => 'bg-gold-soft text-gold-accent border-theme-strong',
    };
@endphp

<div {{ $attributes->merge(['class' => 'sig-card rounded-2xl p-5 flex flex-col justify-between gap-4']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="space-y-1">
            <p class="text-xs font-medium uppercase tracking-[0.14em] text-theme-secondary">
                {{ $label }}
            </p>
            <p class="font-display text-3xl font-semibold tracking-tight text-theme-primary tabular-nums">
                {{ $value }}
            </p>
        </div>

        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gold-soft text-gold-accent border border-theme">
            <x-ui.icon :name="$icon" class="h-5 w-5" />
        </span>
    </div>

    @if ($hint || $badge)
        <div class="flex items-center justify-between gap-2 border-t border-theme pt-3 text-xs">
            @if ($hint)
                <span class="text-theme-muted">{{ $hint }}</span>
            @else
                <span></span>
            @endif

            @if ($badge)
                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[11px] font-medium {{ $badgeClasses }}">
                    {{ $badge }}
                </span>
            @endif
        </div>
    @endif
</div>
