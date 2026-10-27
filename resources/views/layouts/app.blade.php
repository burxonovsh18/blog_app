<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700&family=Literata:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fb; --surface: #ffffff; --ink: #1b2430; --muted: #5d6b7e;
            --line: #dfe5ee; --accent: #2457d6; --danger: #b3261e;
        }
        @media (prefers-color-scheme: dark) {
            :root { --bg: #11161d; --surface: #181f29; --ink: #e8edf4; --muted: #93a1b5; --line: #263041; --accent: #7aa2ff; --danger: #ff8a80; }
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--bg); color: var(--ink); font: 18px/1.7 'Literata', Georgia, serif; }
        a { color: var(--accent); }
        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
        h1, h2, h3, .brand, button, label, nav a { font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        header.site { border-bottom: 1px solid var(--line); background: var(--surface); }
        header.site div { max-width: 720px; margin: 0 auto; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-weight: 700; font-size: 1.25rem; color: var(--ink); text-decoration: none; }
        nav a { margin-left: 18px; text-decoration: none; font-size: .95rem; }
        main { max-width: 720px; margin: 0 auto; padding: 40px 20px 80px; }
        .post-item { padding: 26px 0; border-bottom: 1px solid var(--line); }
        .post-item h2 { margin: 0 0 4px; font-size: 1.6rem; line-height: 1.25; }
        .post-item h2 a { color: var(--ink); text-decoration: none; }
        .post-item h2 a:hover { color: var(--accent); }
        .meta { color: var(--muted); font-size: .9rem; font-family: 'Bricolage Grotesque', system-ui, sans-serif; }
        article h1 { font-size: 2.4rem; line-height: 1.15; margin: 0 0 8px; }
        article .body { margin-top: 28px; }
        .empty { color: var(--muted); padding: 40px 0; }
        .pager { display: flex; justify-content: space-between; margin-top: 28px; font-family: 'Bricolage Grotesque', sans-serif; }
        .pager svg { width: 16px; height: 16px; }
        /* forms */
        .card { background: var(--surface); border: 1px solid var(--line); border-radius: 10px; padding: 24px; margin-bottom: 28px; }
        label { display: block; font-weight: 500; margin: 14px 0 4px; font-size: .95rem; }
        input[type=text], input[type=email], input[type=password], textarea {
            width: 100%; padding: 10px 12px; font: inherit; font-size: 1rem; color: var(--ink);
            background: var(--bg); border: 1px solid var(--line); border-radius: 6px;
        }
        textarea { min-height: 220px; resize: vertical; }
        .check { display: flex; align-items: center; gap: 8px; margin-top: 14px; }
        .check input { width: 18px; height: 18px; }
        .check label { margin: 0; }
        button { cursor: pointer; font-size: .95rem; padding: 9px 16px; border-radius: 6px; border: 1px solid var(--accent); background: var(--accent); color: #fff; }
        button.ghost { background: transparent; color: var(--accent); }
        button.danger { background: transparent; border-color: var(--danger); color: var(--danger); }
        .row { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
        .error { color: var(--danger); font-size: .9rem; margin-top: 8px; min-height: 1.2em; }
        .toast { color: var(--muted); font-size: .9rem; min-height: 1.2em; }
        .my-post { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 14px 0; border-bottom: 1px solid var(--line); }
        .my-post strong { font-family: 'Bricolage Grotesque', sans-serif; }
        .badge { font-size: .8rem; padding: 2px 8px; border-radius: 99px; border: 1px solid var(--line); color: var(--muted); margin-left: 8px; }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>
<body>
<header class="site">
    <div>
        <a class="brand" href="{{ route('blog.index') }}">{{ config('app.name') }}</a>
        <nav>
            <a href="{{ route('blog.index') }}">Posts</a>
            <a href="{{ route('dashboard') }}">Write</a>
        </nav>
    </div>
</header>
<main>
    @yield('content')
</main>
@stack('scripts')
</body>
</html>
