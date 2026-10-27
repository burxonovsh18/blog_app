@extends('layouts.app')

@section('title', 'Your posts - ' . config('app.name'))

@section('content')
    <div class="row" style="justify-content: space-between; align-items:center; margin-top:0">
        <h1 style="margin:0">Your posts</h1>
        <button class="ghost" id="logout">Sign out</button>
    </div>

    <div class="card" style="margin-top:24px">
        <h2 id="form-title" style="margin-top:0">New post</h2>
        <form id="post-form">
            <input type="hidden" id="post-id">
            <label for="title">Title</label>
            <input type="text" id="title" required maxlength="200">
            <label for="excerpt">Summary (optional, shown in the post list)</label>
            <input type="text" id="excerpt" maxlength="300">
            <label for="body">Body (leave a blank line between paragraphs)</label>
            <textarea id="body" required></textarea>
            <div class="check">
                <input type="checkbox" id="published">
                <label for="published">Publish this post</label>
            </div>
            <div class="error" id="form-error" role="alert"></div>
            <div class="row">
                <button type="submit" id="save">Save post</button>
                <button type="button" class="ghost" id="cancel" hidden>Cancel editing</button>
                <span class="toast" id="toast" role="status"></span>
            </div>
        </form>
    </div>

    <div id="list"></div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('blog_token');
if (!token) window.location.href = '{{ route('login') }}';

const $ = id => document.getElementById(id);
let posts = [];

async function api(method, path, body) {
    const res = await fetch('/api/' + path, {
        method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token,
        },
        body: body ? JSON.stringify(body) : undefined,
    });
    if (res.status === 401) {
        localStorage.removeItem('blog_token');
        window.location.href = '{{ route('login') }}';
        return;
    }
    const data = res.status === 204 ? null : await res.json();
    if (!res.ok) {
        const msg = data && data.errors ? Object.values(data.errors)[0][0] : (data && data.message) || 'Request failed.';
        throw new Error(msg);
    }
    return data;
}

function toast(text) {
    $('toast').textContent = text;
    setTimeout(() => { $('toast').textContent = ''; }, 2500);
}

function resetForm() {
    $('post-form').reset();
    $('post-id').value = '';
    $('form-title').textContent = 'New post';
    $('cancel').hidden = true;
    $('form-error').textContent = '';
}

async function load() {
    const data = await api('GET', 'my/posts');
    posts = data.data;
    const list = $('list');
    list.innerHTML = '';
    if (!posts.length) {
        const p = document.createElement('p');
        p.className = 'empty';
        p.textContent = 'You have not written anything yet. Use the form above to start.';
        list.appendChild(p);
        return;
    }
    posts.forEach(post => {
        const row = document.createElement('div');
        row.className = 'my-post';

        const info = document.createElement('div');
        const strong = document.createElement('strong');
        strong.textContent = post.title;
        const badge = document.createElement('span');
        badge.className = 'badge';
        badge.textContent = post.published ? 'Published' : 'Draft';
        info.append(strong, badge);

        const actions = document.createElement('div');
        actions.className = 'row';
        actions.style.marginTop = '0';

        if (post.published) {
            const view = document.createElement('a');
            view.href = '/posts/' + post.slug;
            view.textContent = 'View';
            view.style.alignSelf = 'center';
            actions.appendChild(view);
        }
        const edit = document.createElement('button');
        edit.className = 'ghost';
        edit.textContent = 'Edit';
        edit.onclick = () => startEdit(post);
        const del = document.createElement('button');
        del.className = 'danger';
        del.textContent = 'Delete';
        del.onclick = () => remove(post);
        actions.append(edit, del);

        row.append(info, actions);
        list.appendChild(row);
    });
}

function startEdit(post) {
    $('post-id').value = post.id;
    $('title').value = post.title;
    $('excerpt').value = post.excerpt || '';
    $('body').value = post.body;
    $('published').checked = post.published;
    $('form-title').textContent = 'Edit post';
    $('cancel').hidden = false;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

async function remove(post) {
    if (!confirm('Delete "' + post.title + '"? This cannot be undone.')) return;
    try {
        await api('DELETE', 'posts/' + post.id);
        if ($('post-id').value == post.id) resetForm();
        toast('Post deleted.');
        load();
    } catch (e) { toast(e.message); }
}

$('post-form').addEventListener('submit', async e => {
    e.preventDefault();
    $('form-error').textContent = '';
    const id = $('post-id').value;
    const payload = {
        title: $('title').value,
        excerpt: $('excerpt').value || null,
        body: $('body').value,
        published: $('published').checked,
    };
    try {
        await api(id ? 'PUT' : 'POST', id ? 'posts/' + id : 'posts', payload);
        resetForm();
        toast('Post saved.');
        load();
    } catch (err) {
        $('form-error').textContent = err.message;
    }
});

$('cancel').addEventListener('click', resetForm);

$('logout').addEventListener('click', async () => {
    try { await api('POST', 'logout'); } catch (e) {}
    localStorage.removeItem('blog_token');
    localStorage.removeItem('blog_user');
    window.location.href = '{{ route('login') }}';
});

load().catch(e => { $('form-error').textContent = e.message; });
</script>
@endpush
