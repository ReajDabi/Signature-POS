@props([
    'status' => 'low',
    'label' => null,
    'qty' => null,
    'unit' => null,
])

@php
    $tone = match ($status) {
        'out' => [
            'classes' => 'border-rose-500/40 bg-rose-500/12 text-rose-700 dark:text-rose-300',
            'dot' => 'bg-rose-500',
            'text' => $label ?? 'Out of Stock',
        ],
        'ok' => [
            'classes' => 'border-emerald-500/35 bg-emerald-500/10 text-emerald-800 dark:text-emerald-300',
            'dot' => 'bg-emerald-500',
            'text' => $label ?? 'Healthy Stock',
        ],
        default => [
            'classes' => 'border-amber-500/45 bg-amber-500/14 text-amber-900 dark:text-amber-300',
            'dot' => 'bg-amber-500',
            'text' => $label ?? 'Low Stock',
        ],
    };
@endphp

<span {{ $attributes->merge([
    'class' => 'sig-low-stock-badge inline-flex items-center gap-2 border px-2.5 py-1 text-xs font-semibold tracking-wide '.$tone['classes'],
]) }}>
    <span class="h-1.5 w-1.5 rounded-full shrink-0 {{ $tone['dot'] }}"></span>
    <span>{{ $tone['text'] }}</span>
    @if ($qty !== null)
        <span class="tabular-nums opacity-85">
            · {{ $qty }}@if ($unit) {{ $unit }}@endif
        </span>
    @endif
</span>
