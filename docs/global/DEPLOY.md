# Deployment

Production runs on the same VPS as axelnova-dashboard, sharing the `axelnova-shared` Docker network and `axelnova-mysql` container.

## VPS topology

- `~/infra/` — axelnova-infra clone (mysql + phpmyadmin, always up)
- `~/HouseOfParfum/` — this repo (frontend + backend)
- `~/data/house-of-parfum/storage/` — host-mounted Laravel storage (logs, uploads, sessions)
- Reverse proxy (nginx on the host) terminates TLS for:
  - `https://houseofparfum.axelnova.tech` → `127.0.0.1:3001` (frontend)
  - `https://hop-api.axelnova.tech` → `127.0.0.1:8000` (backend API)

## First-time deploy

```bash
# 1. Clone
cd ~ && git clone https://github.com/<owner>/HouseOfParfum.git
cd HouseOfParfum

# 2. Fill in prod env
cp backend/.env.production.example backend/.env.production
# edit: APP_KEY, DB_PASSWORD (must match axelnova-infra's hop_user pw on this VPS), MAIL_*

# 3. Storage dir for backend
mkdir -p ~/data/house-of-parfum/storage/{app,framework/cache,framework/sessions,framework/views,logs}

# 4. Make sure shared infra is up
cd ~/infra && docker compose up -d

# 5. Build & boot HoP
cd ~/HouseOfParfum
docker compose -f docker-compose.prod.yml up -d --build

# 6. Migrate + seed (catalog: 13 brands, 42 perfumes)
docker compose -f docker-compose.prod.yml exec backend php artisan migrate --force
docker compose -f docker-compose.prod.yml exec backend php artisan db:seed --force
docker compose -f docker-compose.prod.yml exec backend php artisan config:cache route:cache
```

> Don't run `view:cache` — the API has no Blade views (the L11 upgrade removed them).

## Routine deploy

```bash
cd ~/HouseOfParfum
git pull
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml exec backend php artisan migrate --force
docker compose -f docker-compose.prod.yml exec backend php artisan config:cache route:cache
```

## Rollback

```bash
cd ~/HouseOfParfum
git log --oneline -10
git checkout <previous-sha>
docker compose -f docker-compose.prod.yml up -d --build
# If migrations were run, you may need to step them back manually.
```

## phpMyAdmin in prod

The shared `axelnova-phpmyadmin` (port 8080) is used for both dev and prod data. In prod, **do not** expose it publicly — leave it bound to `127.0.0.1` and reach it via SSH tunnel:

```bash
ssh -L 8080:127.0.0.1:8080 vps
# then open http://127.0.0.1:8080 on your Mac
```

If you ever need to expose it, gate it behind nginx basic-auth + IP allow-list. Never put phpMyAdmin on a public hostname unauthenticated.

## Logs

```bash
# Laravel
tail -f ~/data/house-of-parfum/storage/logs/laravel.log

# Container stdout
docker compose -f docker-compose.prod.yml logs -f --tail=200 backend frontend
```
