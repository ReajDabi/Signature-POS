@props([
    'model' => 'notice',
])

<div
    x-show="{{ $model }}"
    x-cloak
    class="flex items-center justify-between gap-3 rounded-xl border border-emerald-500/35 bg-emerald-500/10 px-4 py-3 text-xs text-emerald-800 dark:text-emerald-200"
>
    <div class="flex items-center gap-2.5">
        <x-ui.icon name="check" class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" />
        <span x-text="{{ $model }}"></span>
    </div>
    <button type="button" x-on:click="{{ $model }} = null" class="text-theme-muted hover:text-theme-primary cursor-pointer" aria-label="Dismiss">
        <x-ui.icon name="close" class="h-4 w-4" />
    </button>
</div>
