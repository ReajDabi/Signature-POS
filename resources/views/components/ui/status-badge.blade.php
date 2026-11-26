@props([
    'status' => 'active',
    'label' => null,
])

@php
    $normalized = strtolower((string) $status);
    $styles = match ($normalized) {
        'active', 'completed', 'paid', 'ok' => 'border-emerald-500/35 bg-emerald-500/12 text-emerald-700 dark:text-emerald-300',
        'voided', 'failed', 'disabled', 'inactive' => 'border-rose-500/35 bg-rose-500/12 text-rose-700 dark:text-rose-300',
        'pending', 'unpaid', 'processing' => 'border-amber-500/40 bg-amber-500/12 text-amber-800 dark:text-amber-300',
        default => 'border-theme-strong bg-gold-soft text-gold-accent',
    };
    $display = $label ?? ucfirst($normalized);
@endphp

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center rounded-lg border px-2.5 py-0.5 text-xs font-medium '.$styles,
]) }}>
    {{ $display }}
</span>
