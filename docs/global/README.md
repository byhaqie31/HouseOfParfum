# House of Parfum — Repo Guide

Monorepo for the House of Parfum web app. Two services deployed together:

- [backend/](../../backend/) — **Laravel 11** REST API (PHP 8.4). Port `8000`. Docs: [docs/backend/](../backend/).
- [frontend/](../../frontend/) — **Nuxt 4** SSR (Vue 3 + TS + Tailwind 4 + Pinia). Port `3001`. Docs: [docs/frontend/](../frontend/).
- **MySQL 8** + **phpMyAdmin** are shared via the sibling [axelnova-infra](../../../axelnova-infra/) repo. Both apps join the external `axelnova-shared` Docker network and reach MySQL via hostname `mysql`.

## Local dev — first time

Shared infra must be up before HoP can connect to MySQL.

```bash
# 1. Boot shared infra (mysql + phpmyadmin) — one time per Mac boot
cd ../axelnova-infra && docker compose up -d

# 2. HoP backend env (one time per clone)
cp backend/.env.example backend/.env
docker compose -f docker-compose.dev.yml run --rm backend php artisan key:generate

# 3. Boot the HoP stack
cd ../HouseOfParfum
docker compose -f docker-compose.dev.yml up -d --build

# 4. Migrate + seed
docker compose -f docker-compose.dev.yml exec backend php artisan migrate --seed
```

## Local dev — daily

```bash
docker compose -f docker-compose.dev.yml up -d
docker compose -f docker-compose.dev.yml logs -f backend frontend
docker compose -f docker-compose.dev.yml down
```

## Endpoints

| Service | URL |
|---|---|
| Frontend | http://127.0.0.1:3001 |
| Backend API | http://127.0.0.1:8000/api |
| phpMyAdmin (shared) | http://127.0.0.1:8080 |
| MySQL (shared) | 127.0.0.1:3306 |

DB credentials for HoP — provisioned in [axelnova-infra/scripts/init-databases.sql](../../../axelnova-infra/scripts/init-databases.sql):

- database: `hop_db`
- user: `hop_user`
- password: `hop_local_pw`

## Running artisan / composer / npm

DB hostname `mysql` only resolves on the docker network — run these inside the container:

```bash
docker compose -f docker-compose.dev.yml exec backend  php artisan migrate
docker compose -f docker-compose.dev.yml exec backend  php artisan tinker
docker compose -f docker-compose.dev.yml exec backend  composer require <pkg>
docker compose -f docker-compose.dev.yml exec frontend npm install <pkg>
```

## Production

See [DEPLOY.md](DEPLOY.md).

## Port allocation

HoP is registered in [axelnova-infra/docs/port-allocation.md](../../../axelnova-infra/docs/port-allocation.md):

| Port | Service | Container |
|---|---|---|
| 3001 | Nuxt frontend | `frontend-dev` |
| 8000 | Laravel backend | `backend-dev` |
