# Backend — House of Parfum API

Laravel 11 REST API powering the HoP Nuxt frontend.

- PHP 8.4 (php-fpm + nginx via supervisord), MySQL 8 (shared via [axelnova-infra](../../../axelnova-infra/))
- Routes live in [backend/routes/api.php](../../backend/routes/api.php)
- Controllers in [backend/app/Http/Controllers/Api/](../../backend/app/Http/Controllers/Api/)
- Seeders in [backend/database/seeders/](../../backend/database/seeders/)
- Sanctum bearer-token auth (no SPA cookies; pure `Authorization: Bearer …`)

See [docs/global/README.md](../global/README.md) for boot instructions. All artisan / composer commands must run inside the container — `DB_HOST=mysql` only resolves on the docker network.

## API surface

All responses are forced to JSON via the `ForceJsonResponse` middleware (registered in [backend/bootstrap/app.php](../../backend/bootstrap/app.php)).

**Public**

| Method | Path | Handler |
|---|---|---|
| `POST` | `/api/register` | `AuthController@register` — returns sanctum token + user |
| `POST` | `/api/login` | `AuthController@login` — returns sanctum token + user |
| `GET`  | `/api/brand` | `BrandController@index` — 13 seeded brands |
| `GET`  | `/api/brand/{id}` | `BrandController@show` |
| `GET`  | `/api/perfume` | `PerfumeController@index` — 42 seeded perfumes |
| `GET`  | `/api/perfume/{id}` | `PerfumeController@show` |
| `GET`  | `/up` | L11 framework health check (200 if booted) |

**Authenticated** (`Authorization: Bearer <token>`, middleware `auth:sanctum`)

| Method | Path | Handler |
|---|---|---|
| `POST` | `/api/logout` | `AuthController@logout` — revokes current access token |
| `GET`  | `/api/user` | returns the authenticated user |
| `POST` `PUT` `PATCH` `DELETE` | `/api/brand{/id}` | `BrandController` store/update/destroy (admin-only; no UI yet) |
| `POST` `PUT` `PATCH` `DELETE` | `/api/perfume{/id}` | `PerfumeController` store/update/destroy (admin-only; no UI yet) |

> Cart / order / transaction / purchase / chat / profile / type / season endpoints **were dropped** during the L11 upgrade (they were never wired to the current Nuxt frontend). Git history before `chore/laravel-11-upgrade` preserves them if needed.

## Seeding

```bash
docker compose -f docker-compose.dev.yml exec backend php artisan db:seed
```

Seeders: `BrandSeeder`, `PerfumeSeeder` (see [backend/database/seeders/](../../backend/database/seeders/)). Both use `DB::table()->delete()` + bulk `insert()` for idempotent re-runs — `TRUNCATE` silently no-ops inside this Docker MySQL setup.

## L11 architectural notes

- **No `app/Http/Kernel.php`, no `app/Console/Kernel.php`, no `app/Exceptions/Handler.php`.** L11 moves all three into [`bootstrap/app.php`](../../backend/bootstrap/app.php). HoP's customisations:
  - `ForceJsonResponse` is prepended to the `api` middleware group
  - All `api/*` requests render exceptions as JSON via `shouldRenderJsonWhen`
- **Providers live in [`bootstrap/providers.php`](../../backend/bootstrap/providers.php)**, not `config/app.php`.
- **Sanctum 4** in pure-token mode. Stateful SPA domains aren't used; the Nuxt frontend stores the token in localStorage and sends `Authorization: Bearer …`.
- **CORS** allowed origins are env-driven via `CORS_ALLOWED_ORIGINS` (comma-separated) — see [.env.example](../../backend/.env.example).
