# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

E-commerce storefront for House of Parfum. Personal project. Part of the AxelNova umbrella (sibling to [axelnova-dashboard](../../axelnova-dashboard/), sharing MySQL + phpMyAdmin via [axelnova-infra](../../axelnova-infra/)).

## Documentation standard — CENTRALISED IN `docs/`

**All `.md` files in this repo live under [docs/](../docs/), categorised by scope. Filenames are ALL-CAPS-WITH-HYPHENS (`.md` extension stays lowercase).**

- `docs/global/` — repo-wide concerns (architecture, deploy, top-level features)
- `docs/frontend/` — anything Nuxt/UI specific
- `docs/backend/` — anything Laravel/API specific

Rules:
1. Never create a new `.md` file outside `docs/`. The only exceptions are the root `README.md` (GitHub landing page, thin pointer) and this `CLAUDE.md`.
2. New doc → pick the right subfolder (`global` / `frontend` / `backend`) → name in ALL-CAPS-WITH-HYPHENS (e.g. `QUOTE-BUILDER.md`, not `quote_builder.md`).
3. Top-level `README.md` per scope is fine; component-level inline READMEs get disambiguated names (e.g. `PERFUME-COMPONENTS.md`, not generic `README.md` in subfolders).
4. When moving or renaming a doc, grep the repo for the old path and fix every reference.

## Authoritative docs — read these before assuming

- [docs/global/README.md](../docs/global/README.md) — repo layout, full local-dev orchestration
- [docs/global/DEPLOY.md](../docs/global/DEPLOY.md) — VPS topology, deploy flow, phpMyAdmin SSH tunnel
- [docs/backend/README.md](../docs/backend/README.md) — API surface, seeding
- [docs/frontend/README.md](../docs/frontend/README.md) — Nuxt setup, API base resolution, useApi pattern

## Big picture

Monorepo, two apps deployed together:

- `backend/` — **Laravel 11** API (PHP 8.4). Port `8000`. Routes in [backend/routes/api.php](../backend/routes/api.php). Controllers in [backend/app/Http/Controllers/Api/](../backend/app/Http/Controllers/Api/).
- `frontend/` — **Nuxt 4** SSR (Vue 3 + TS + Tailwind 4 + Pinia). Port `3005`. Source in [frontend/app/](../frontend/app/).
- **MySQL 8 + phpMyAdmin** are shared via [axelnova-infra](../../axelnova-infra/) — both apps join its external `axelnova-shared` Docker network and reach MySQL via hostname `mysql`. HoP's DB `hop_db` + user `hop_user` are provisioned in [axelnova-infra/scripts/init-databases.sql](../../axelnova-infra/scripts/init-databases.sql).

## Common commands

Local dev runs entirely in Docker. Shared infra must be up first (`cd ../axelnova-infra && docker compose up -d`).

```bash
# Boot
docker compose -f docker-compose.dev.yml up -d --build

# Logs
docker compose -f docker-compose.dev.yml logs -f backend frontend

# Artisan / composer / npm — must run inside containers (DB_HOST=mysql only resolves on docker net)
docker compose -f docker-compose.dev.yml exec backend  php artisan migrate
docker compose -f docker-compose.dev.yml exec backend  php artisan tinker
docker compose -f docker-compose.dev.yml exec frontend npm install <pkg>

# Stop
docker compose -f docker-compose.dev.yml down            # or `down -v` to wipe volumes
```

Endpoints: backend `http://127.0.0.1:8000`, frontend `http://127.0.0.1:3005`, phpMyAdmin `http://127.0.0.1:8080`.

Production deploys via `docker compose -f docker-compose.prod.yml up -d --build` on the VPS — see [docs/global/DEPLOY.md](../docs/global/DEPLOY.md).

## Non-obvious rules

**Two API bases.** Frontend has separate `apiBase` (SSR, via docker net) and `public.apiBase` (browser, via host loopback or public hostname). [`useApi`](../frontend/app/composables/useApi.ts) picks the right one — never hand-roll `fetch` against `localhost`.

**Force-JSON middleware.** All `/api` responses go through `ForceJsonResponse` ([backend/app/Http/Middleware/](../backend/app/Http/Middleware/)) so Laravel never returns HTML error pages to the Nuxt client.

**Per-app docker hostname for MySQL is `mysql`.** That resolves only inside the `axelnova-shared` network. From the host (e.g. Mac terminal, native PHP), use `127.0.0.1:3306`. The `DB_HOST` override in docker-compose.dev.yml's `environment:` block intentionally shadows whatever's in `backend/.env`.

**phpMyAdmin in prod = SSH tunnel only.** Never expose the shared phpMyAdmin publicly. See [docs/global/DEPLOY.md](../docs/global/DEPLOY.md#phpmyadmin-in-prod).

## Conventions

- Port allocation is centrally tracked in [axelnova-infra/docs/port-allocation.md](../../axelnova-infra/docs/port-allocation.md) — HoP owns `3005` (frontend) and `8000` (backend). Update that file in the same PR that introduces a new port.
- Bind to `127.0.0.1` only — never `0.0.0.0` — so nothing leaks beyond the Mac.
- Icons: prefer Iconify (via Nuxt Icon or `<Icon>`). No emojis in UI.
- New `.md` doc → ALL-CAPS-WITH-HYPHENS filename under the right `docs/` subfolder.
