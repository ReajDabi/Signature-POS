<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} · {{ config('app.name') }}</title>
    <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0e0e10;color:#f3efe7;font-family:system-ui,sans-serif}
        main{max-width:30rem;padding:2rem;text-align:center}
        h1{font-size:4rem;margin:0;color:#d9b77a}
        p{color:#9b978d}
    </style>
</head>
<body>
    <main>
        <h1>{{ $code }}</h1>
        <p>{{ $message }}</p>
    </main>
</body>
</html>
