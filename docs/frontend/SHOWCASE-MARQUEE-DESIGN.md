# Showcase Marquee on /about — Design

**Date:** 2026-07-11
**Status:** Approved, pending implementation
**Scope:** Frontend only ([frontend/app/](../../frontend/app/)). No backend changes.

## Goal

Give the public `/about` landing page a perfume showcase: an auto-drifting
carousel ("marquee") of the catalogue's top-rated fragrances, placed between
the hero and the three-pillars section. The root `/` route keeps redirecting
to `/auth/login` — this change touches `/about` only.

## Decisions (settled during brainstorming)

| Question | Decision |
| --- | --- |
| Target page | `/about` only; `/` keeps its login redirect |
| Placement | New full-width section directly after the hero, before the pillars |
| Which perfumes | Top-rated: `GET /api/perfume?sort=rating&per_page=12` (public endpoint) |
| Motion | Auto-drift marquee; pauses on hover; reduced-motion fallback |
| Build approach | Pure CSS keyframes marquee — **no new npm dependencies** |
| Imagery | Curated Unsplash placeholder photos (catalogue has no product images) |

## Architecture

### Data flow

`about.vue` fetches once via `useAsyncData` + the existing
[`useApi`](../../frontend/app/composables/useApi.ts) composable (SSR uses
`apiBase` over the docker network; browser uses `public.apiBase` — never
hand-rolled fetch):

```
GET /api/perfume?sort=rating&per_page=12
→ Laravel paginator envelope; take `.data` (12 top-rated perfumes)
→ pass to <ShowcaseMarquee :perfumes="..." />
```

Error handling: if the request fails or returns an empty list, the whole
section is skipped with `v-if` — the page renders exactly as it does today.
No spinner, no error state on a marketing page.

### New component: `ShowcaseMarquee.vue`

`frontend/app/components/ShowcaseMarquee.vue`

- **Props:** `perfumes` — array of the catalogue shape already used by
  [DiscoverCard.vue](../../frontend/app/components/DiscoverCard.vue):
  `{ id, brand, name, main_accord?, rating?, family? }`.
- **Track:** rendered twice back-to-back inside an overflow-hidden strip; a
  CSS `@keyframes` animation translates the track `-50%` and loops, giving a
  seamless infinite drift. Duration is proportional to card count so drift
  speed stays constant regardless of how many items load.
- **Pause on hover:** `animation-play-state: paused` on strip hover.
- **Edge fade:** CSS `mask-image` linear gradients on both ends so cards
  dissolve in/out instead of clipping.
- **Accessibility:** the duplicate track is `aria-hidden="true"`; the section
  carries an accessible heading. Under `prefers-reduced-motion: reduce` the
  animation is removed and the strip becomes a plain horizontally scrollable
  row (`overflow-x: auto`), duplicate track hidden.

### Cards

Rendered inside `ShowcaseMarquee` (no separate card component — single
consumer):

- Unsplash photo, 3:4 aspect ratio, `loading="lazy"`, fixed aspect box so
  there is zero layout shift.
- Frosted scent-family pill overlaid on the photo, reusing the existing
  scent-world colour system (`scentWorld` / `deriveFamily` from
  [utils/scent.ts](../../frontend/app/utils/scent.ts) and
  [utils/scentFamily.ts](../../frontend/app/utils/scentFamily.ts)).
- Brand / name / rating below, same editorial type classes (`fm`, `fd`)
  and CSS variables as `DiscoverCard`.
- Whole card links to `/user/perfume/{id}`. Logged-out visitors get bounced
  to login by the existing auth middleware — intentional sign-up funnel.

### Placeholder imagery

`frontend/app/data/showcase-images.ts` — a curated pool of ~10 direct
`images.unsplash.com` perfume-bottle photo URLs. Assignment is deterministic:
`pool[id % pool.length]`, so a perfume keeps the same photo across visits and
SSR/client renders match. The file is explicitly commented as **placeholder
art** to be swapped for real product imagery later. (`source.unsplash.com`
is dead — direct image URLs only.)

### Section chrome in `about.vue`

Kicker "From the catalogue" + short display heading in the existing hero
type style, marquee below, on the plain canvas background (`--color-canvas`)
so the strip breathes between the hero and the bordered pillars section.

## Out of scope

- No backend changes (no "featured" flag, no random sort).
- No new npm dependencies.
- No changes to `/` routing, DiscoverCard, or the perfume detail page.

## Verification

1. `docker compose -f docker-compose.dev.yml up -d --build` (infra first).
2. Load `http://127.0.0.1:3005/about`: 12 top-rated cards render, drift
   continuously, pause on hover, links point at `/user/perfume/{id}`.
3. View-source / curl the SSR HTML: cards are server-rendered.
4. DevTools → emulate `prefers-reduced-motion: reduce`: no animation,
   strip scrolls natively.
5. Stop the backend container, reload `/about`: page renders without the
   showcase section and without errors.
