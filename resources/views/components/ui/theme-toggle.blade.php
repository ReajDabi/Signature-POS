@props([
    'compact' => false,
])

<button
    type="button"
    x-data="{
        isDark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            if (window.Alpine && window.Alpine.store('theme')) {
                window.Alpine.store('theme').toggle();
                this.isDark = window.Alpine.store('theme').isDark;
            } else if (window.signatureToggleTheme) {
                this.isDark = window.signatureToggleTheme() === 'dark';
            }
        }
    }"
    x-on:signature:theme-changed.window="isDark = $event.detail.isDark"
    x-on:click="toggleTheme()"
    :aria-pressed="isDark.toString()"
    :title="isDark ? 'Switch to Champagne Light Mode' : 'Switch to Dark Glam Mode'"
    {{ $attributes->merge([
        'class' => 'sig-btn-ghost inline-flex items-center justify-center gap-2 rounded-xl px-3 py-2 text-xs font-medium cursor-pointer select-none',
    ]) }}
>
    <span class="text-gold-accent shrink-0" x-show="isDark" x-cloak>
        <x-ui.icon name="sun" class="h-4 w-4" />
    </span>
    <span class="text-gold-accent shrink-0" x-show="!isDark">
        <x-ui.icon name="moon" class="h-4 w-4" />
    </span>

    @unless ($compact)
        <span x-text="isDark ? 'Light Mode' : 'Dark Glam'">Dark Glam</span>
    @endunless
</button>
