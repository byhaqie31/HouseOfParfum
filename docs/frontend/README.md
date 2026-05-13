# Frontend — House of Parfum

Nuxt 4 SSR storefront for House of Parfum.

- Nuxt 4 (default `app/` dir), Vue 3 + TypeScript
- Tailwind 4 (CSS-first via `@tailwindcss/vite`, no `tailwind.config.ts`)
- Pinia (auth store) + custom `useApi` composable
- Source lives under [frontend/app/](../../frontend/app/)

See [docs/global/README.md](../global/README.md) for boot instructions.

## API base resolution

Two runtime config keys (set by docker-compose env):

| Key | Env var | Used by |
|---|---|---|
| `apiBase` (private) | `NUXT_API_BASE` | SSR — resolves over docker network to `http://backend:8000/api` |
| `public.apiBase` | `NUXT_PUBLIC_API_BASE` | Browser — resolves via host loopback or public hostname |

[`useApi`](../../frontend/app/composables/useApi.ts) picks the right one based on `import.meta.server`. Always go through `useApi` — don't hand-roll `fetch` calls against `localhost`.

## Layouts / middleware

- [`default.vue`](../../frontend/app/layouts/default.vue) — global shell (AppNavbar + AppFooter)
- [`auth.vue`](../../frontend/app/layouts/auth.vue) — login/register shell
- [`middleware/auth.ts`](../../frontend/app/middleware/auth.ts) — gate routes requiring a token
- [`middleware/guest.ts`](../../frontend/app/middleware/guest.ts) — gate login/register from logged-in users

## Pages

`/`, `/perfume`, `/perfume/[id]`, `/cart`, `/order`, `/chat`, `/profile`, `/login`, `/register`.

## Adding deps

```bash
docker compose -f docker-compose.dev.yml exec frontend npm install <pkg>
```

Host `npm install` won't reach the container's isolated `node_modules` volume. Either install inside the container as above, or rebuild the image.
