@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title.' · '.config('app.name', 'Signature POS') : config('app.name', 'Signature POS') }}</title>

        <script>
            (function () {
                var key = 'signature-pos-theme';
                var mode = 'dark';
                try {
                    var saved = window.localStorage.getItem(key);
                    if (saved === 'light' || saved === 'dark') {
                        mode = saved;
                    }
                } catch (e) {}
                document.documentElement.classList.toggle('dark', mode === 'dark');
                document.documentElement.dataset.theme = mode;
                window.signatureToggleTheme = function () {
                    var next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                    document.documentElement.classList.toggle('dark', next === 'dark');
                    document.documentElement.dataset.theme = next;
                    try {
                        window.localStorage.setItem(key, next);
                    } catch (e) {}
                    window.dispatchEvent(new CustomEvent('signature:theme-changed', {
                        detail: { mode: next, isDark: next === 'dark' },
                    }));
                    return next;
                };
            })();
        </script>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @stack('head')
    </head>
    <body {{ $attributes->merge(['class' => 'bg-theme-canvas text-theme-primary antialiased selection:bg-[#d9b77a]/30 selection:text-theme-primary']) }}>
        {{ $slot }}
        @stack('scripts')
    </body>
</html>
