# House of Parfum

Laravel API + Nuxt SSR storefront. Part of the AxelNova umbrella — shares MySQL + phpMyAdmin with axelnova-dashboard via [axelnova-infra](../axelnova-infra/).

**Canonical docs:** [docs/global/README.md](docs/global/README.md). All `.md` files live under [docs/](docs/) — see CLAUDE.md for the convention.

## Quick start

```bash
# Once: boot shared infra
cd ../axelnova-infra && docker compose up -d

# Boot HoP
cd ../HouseOfParfum
cp backend/.env.example backend/.env
docker compose -f docker-compose.dev.yml up -d --build
docker compose -f docker-compose.dev.yml exec backend php artisan key:generate
docker compose -f docker-compose.dev.yml exec backend php artisan migrate --seed
```

Frontend: http://127.0.0.1:3005 · API: http://127.0.0.1:8000/api · phpMyAdmin: http://127.0.0.1:8080
