# Backend — House of Parfum API

Laravel 11 REST API powering the HoP frontend.

- PHP 8.4, MySQL 8 (shared via [axelnova-infra](../../../axelnova-infra/))
- Routes live in [backend/routes/api.php](../../backend/routes/api.php)
- Controllers in [backend/app/Http/Controllers/Api/](../../backend/app/Http/Controllers/Api/)
- Seeders in [backend/database/seeders/](../../backend/database/seeders/)

See [docs/global/README.md](../global/README.md) for boot instructions. All artisan / composer commands must run inside the container — `DB_HOST=mysql` only resolves on the docker network.

## API surface

Public endpoints under `/api`:

| Resource | Controller |
|---|---|
| Auth (register/login) | `AuthController` |
| Brands | `BrandController` |
| Perfumes | `PerfumeController` |
| Cart | `CartController` |
| Orders | `OrderController` |
| Profile | `ProfileController` |
| Purchases | `PurchaseController` |
| Seasons | `SeasonController` |
| Transactions | `TransactionController` |
| Types | `TypeController` |
| Chat | `ChatController` |

All responses are forced to JSON via `ForceJsonResponse` middleware.

## Seeding

```bash
docker compose -f docker-compose.dev.yml exec backend php artisan db:seed
```

Seeders: `BrandSeeder`, `PerfumeSeeder` (see [backend/database/seeders/](../../backend/database/seeders/)).
