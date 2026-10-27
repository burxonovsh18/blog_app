@extends('layouts.app')

@section('title', 'Sign in - ' . config('app.name'))

@section('content')
    <div class="card">
        <h1>Sign in</h1>
        <form id="login-form">
            <label for="l-email">Email</label>
            <input type="email" id="l-email" required autocomplete="email">
            <label for="l-pass">Password</label>
            <input type="password" id="l-pass" required autocomplete="current-password">
            <div class="error" id="login-error" role="alert"></div>
            <div class="row"><button type="submit">Sign in</button></div>
        </form>
    </div>

    <div class="card">
        <h2>Create an account</h2>
        <form id="register-form">
            <label for="r-name">Name</label>
            <input type="text" id="r-name" required autocomplete="name">
            <label for="r-email">Email</label>
            <input type="email" id="r-email" required autocomplete="email">
            <label for="r-pass">Password (8+ characters)</label>
            <input type="password" id="r-pass" required minlength="8" autocomplete="new-password">
            <div class="error" id="register-error" role="alert"></div>
            <div class="row"><button type="submit" class="ghost">Create account</button></div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
async function auth(path, payload, errorEl) {
    errorEl.textContent = '';
    try {
        const res = await fetch('/api/' + path, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (!res.ok) {
            const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Something went wrong.');
            errorEl.textContent = first;
            return;
        }
        localStorage.setItem('blog_token', data.token);
        localStorage.setItem('blog_user', data.user.name);
        window.location.href = '{{ route('dashboard') }}';
    } catch (e) {
        errorEl.textContent = 'Could not reach the server.';
    }
}

document.getElementById('login-form').addEventListener('submit', e => {
    e.preventDefault();
    auth('login', {
        email: document.getElementById('l-email').value,
        password: document.getElementById('l-pass').value,
    }, document.getElementById('login-error'));
});

document.getElementById('register-form').addEventListener('submit', e => {
    e.preventDefault();
    auth('register', {
        name: document.getElementById('r-name').value,
        email: document.getElementById('r-email').value,
        password: document.getElementById('r-pass').value,
    }, document.getElementById('register-error'));
});
</script>
@endpush
