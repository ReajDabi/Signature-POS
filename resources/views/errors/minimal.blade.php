@php
    $resolvedCode = $code ?? (trim($__env->yieldContent('code')) ?: '500');
    $resolvedMessage = $message ?? (trim($__env->yieldContent('message')) ?: 'An unexpected error occurred.');
    $resolvedTitle = trim($__env->yieldContent('title')) ?: $resolvedCode;
@endphp
<!doctype html>
<html lang="en" class="dark" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $resolvedTitle }} · {{ config('app.name') }}</title>
    <script>
        (function () {
            try {
                var saved = window.localStorage.getItem('signature-pos-theme');
                if (saved === 'light' || saved === 'dark') {
                    document.documentElement.classList.toggle('dark', saved === 'dark');
                    document.documentElement.dataset.theme = saved;
                }
            } catch (e) {}
        })();
    </script>
    <style>
        :root {
            --bg: #faf7f2;
            --surface: #ffffff;
            --border: rgba(140, 102, 40, 0.22);
            --gold: #9d7636;
            --text: #16161a;
            --muted: #585249;
        }
        .dark {
            --bg: #0e0e10;
            --surface: #16161a;
            --border: rgba(217, 183, 122, 0.22);
            --gold: #d9b77a;
            --text: #f5f1ea;
            --muted: #b4aea3;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: var(--bg);
            color: var(--text);
            font-family: "DM Sans", ui-sans-serif, system-ui, sans-serif;
            padding: 1.5rem;
        }
        main {
            max-width: 30rem;
            width: 100%;
            padding: 2.25rem;
            text-align: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
        }
        h1 {
            font-family: "Outfit", "DM Sans", ui-sans-serif, system-ui, sans-serif;
            letter-spacing: -0.025em;
            font-size: 4rem;
            line-height: 1;
            margin: 0 0 0.75rem;
            color: var(--gold);
        }
        p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <main>
        <h1>{{ $resolvedCode }}</h1>
        <p>{{ $resolvedMessage }}</p>
        <a href="{{ url('/') }}">Return to Signature POS</a>
    </main>
</body>
</html>
