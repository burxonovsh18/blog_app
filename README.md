# Blog

A Laravel blog application with a browser-based sign-in and registration page, a public post feed, and a dashboard for creating and managing posts. The application also provides a JSON API for authentication and post management.

## Requirements

- PHP 8.3 or later
- Composer
- SQLite (included with PHP via the PDO SQLite extension)
- Node.js and npm, if you need to build the Vite frontend assets

## Setup

Run these commands from the project directory in PowerShell:

```powershell
composer install
if (!(Test-Path .env)) {
    Copy-Item .env.example .env
}
php artisan key:generate

if (!(Test-Path database\database.sqlite)) {
    New-Item -ItemType File database\database.sqlite | Out-Null
}

php artisan migrate
```

If `.env` already exists, keep it rather than overwriting it with `.env.example`. The database migrations create the users, posts, and API token tables.

Start the local server:

```powershell
php artisan serve
```

Open the URL printed by Artisan (typically `http://127.0.0.1:8000`). The root page (`/`) contains both sign-in and account registration forms.

### Change the application name

Set `APP_NAME` in `.env`; this name appears in the site header and page titles:

```env
APP_NAME="My Blog"
```

If the name does not update, clear Laravel's cached configuration:

```powershell
php artisan config:clear
```

### Build frontend assets (optional)

If you change the Vite-managed frontend assets, install the Node dependencies and build them:

```powershell
npm install
npm run build
```

## Using the application

- Visit `/` to sign in or register.
- Visit `/posts` to browse published posts.
- Visit `/posts/{slug}` to read a published post.
- After signing in, use `/dashboard` to create, edit, publish, and delete your posts.

The browser forms use the JSON API. Successful registration and login return a Sanctum bearer token, which the dashboard stores in browser local storage for authenticated API requests.

## API

All API routes are prefixed with `/api`. Requests and responses use JSON.

| Method | Endpoint | Access | Description |
| --- | --- | --- | --- |
| `POST` | `/api/register` | Public | Create an account and receive an access token |
| `POST` | `/api/login` | Public | Sign in and receive an access token |
| `POST` | `/api/logout` | Authenticated | Revoke the current access token |
| `GET` | `/api/posts` | Public | List published posts |
| `GET` | `/api/posts/{slug}` | Public | View a published post by slug |
| `GET` | `/api/my/posts` | Authenticated | List the signed-in user's posts |
| `POST` | `/api/posts` | Authenticated | Create a post |
| `PUT` | `/api/posts/{id}` | Authenticated, owner only | Update one of the user's posts |
| `DELETE` | `/api/posts/{id}` | Authenticated, owner only | Delete one of the user's posts |

For authenticated requests, send the token returned by login or registration in the `Authorization` header:

```http
Authorization: Bearer YOUR_TOKEN
Accept: application/json
```

Post creation and updates accept `title`, `body`, `published`, and an optional `excerpt`. Posts are drafts when `published` is `false`; published posts appear in the public feed.

## Tests

Run the test suite with:

```powershell
php artisan test
```

## Technology

- Laravel 13
- PHP 8.3+
- SQLite
- Laravel Sanctum for API token authentication
- Blade templates and Vite
