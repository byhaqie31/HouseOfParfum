# Showcase Marquee Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add an auto-drifting marquee of the 12 top-rated catalogue perfumes to the public `/about` page, per the approved spec in [SHOWCASE-MARQUEE-DESIGN.md](SHOWCASE-MARQUEE-DESIGN.md).

**Architecture:** A data-only placeholder-image module, one new `ShowcaseMarquee.vue` component (pure-CSS infinite drift, duplicated track), and a small fetch + section addition to `pages/about.vue`. No backend changes.

**Tech Stack:** Nuxt 4 (Vue 3 `<script setup lang="ts">`), Tailwind 4 utilities + the repo's CSS-variable design system, existing `useApi` composable.

## Global Constraints

- **No new npm dependencies** (spec). There is also **no frontend test runner** in this repo, so every task's test cycle is exact shell commands / browser checks with expected output — do not add a test framework.
- All API calls go through `useApi()` — never hand-rolled `fetch` against localhost (CLAUDE.md).
- Docker-only dev: shared infra first (`cd ../axelnova-infra && docker compose up -d`), then `docker compose -f docker-compose.dev.yml up -d --build`. Frontend: `http://127.0.0.1:3005`, backend: `http://127.0.0.1:8000`.
- Icons via Iconify `<Icon>` only; no emojis in UI. (`★` in `DiscoverCard.vue` is a text glyph — reusing it is fine.)
- New `.md` files only under `docs/` with ALL-CAPS-WITH-HYPHENS names.
- Work on branch `feat/about-showcase-marquee` (already created; spec is committed there).
- `/` keeps redirecting to `/auth/login` — do not touch `pages/index.vue`.

---

### Task 1: Placeholder image pool

**Files:**
- Create: `frontend/app/data/showcase-images.ts`

**Interfaces:**
- Consumes: nothing.
- Produces: `showcaseImageFor(id: number): string` — deterministic Unsplash URL for a perfume id. Task 2 imports it as `import { showcaseImageFor } from '~/data/showcase-images'`.

- [ ] **Step 1: Write the module**

All 9 URLs below were verified on 2026-07-11: HTTP 200 and visually confirmed to be perfume-bottle photography (a 10th candidate was a 404 and two others were dropped as non-perfume/404).

```ts
/**
 * PLACEHOLDER ART for the /about showcase marquee.
 *
 * The discovery catalogue (~24k rows) ships no product photography, so the
 * landing-page showcase leans on a small pool of Unsplash bottle shots until
 * real imagery lands. Photos do NOT depict the perfume they're paired with —
 * assignment is deterministic (`id % pool.length`) purely so a card keeps
 * the same photo across visits and SSR/client renders match.
 *
 * Swap the pool for real imagery later; keep `showcaseImageFor`'s signature.
 * Note: source.unsplash.com is dead — only direct images.unsplash.com URLs.
 */
const POOL = [
  'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1615634260167-c8cdede054de?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1619994403073-2cec844b8e63?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1563170351-be82bc888aa4?q=80&w=640&auto=format&fit=crop',
]

export function showcaseImageFor(id: number): string {
  return POOL[Math.abs(id) % POOL.length]!
}
```

- [ ] **Step 2: Verify every URL still resolves**

Run:

```bash
grep -o "https://[^']*" frontend/app/data/showcase-images.ts | while read u; do
  echo "$(curl -s -o /dev/null -w '%{http_code}' --max-time 10 "$u") $u"
done
```

Expected: exactly 9 lines, every line starting with `200`. If any URL is not 200, remove it from `POOL` (the modulo handles any pool size ≥ 1) and note it in the commit message.

- [ ] **Step 3: Commit**

```bash
git add frontend/app/data/showcase-images.ts
git commit -m "feat: add placeholder Unsplash pool for about showcase"
```

---

### Task 2: ShowcaseMarquee component + /about integration

**Files:**
- Create: `frontend/app/components/ShowcaseMarquee.vue`
- Modify: `frontend/app/pages/about.vue` (script block ~lines 93–119; template between the hero `</section>` at line 36 and the `<!-- Three pillars -->` comment at line 38)

**Interfaces:**
- Consumes: `showcaseImageFor(id: number): string` from Task 1; existing `scentWorld(family, seed, isDark)`, `bottleSeed(id: string, name: string)`, `type ScentFamilyKey`, `type ScentWorld` from `~/utils/scent`; `deriveFamily(accords: string[])` from `~/utils/scentFamily`; `useScentWorld().isDark`; `useApi().get`.
- Produces: `<ShowcaseMarquee :perfumes="..." />` (Nuxt auto-imports it) with prop `perfumes: Array<{ id: number; brand: string; name: string; main_accord?: string | null; rating?: number | null; family?: ScentFamilyKey | null }>` — the exact shape `GET /api/perfume` rows already have.

- [ ] **Step 1: Boot the dev stack and capture the baseline**

```bash
cd ../axelnova-infra && docker compose up -d && cd ../HouseOfParfum
docker compose -f docker-compose.dev.yml up -d --build
```

Wait for boot, then confirm the API slice the page will use:

```bash
curl -s "http://127.0.0.1:8000/api/perfume?sort=rating&per_page=12" | python3 -c "import json,sys; d=json.load(sys.stdin)['data']; print(len(d), d[0]['name'], d[0]['rating'])"
```

Expected: `12 <some perfume name> <rating ≥ 4>`. Also confirm `/about` renders today:

```bash
curl -s http://127.0.0.1:3005/about | grep -c "Wear what moves you"
```

Expected: `1` (or more).

- [ ] **Step 2: Create the component**

`frontend/app/components/ShowcaseMarquee.vue` — complete file:

```vue
<script setup lang="ts">
import { scentWorld, bottleSeed, type ScentFamilyKey, type ScentWorld } from '~/utils/scent'
import { deriveFamily } from '~/utils/scentFamily'
import { showcaseImageFor } from '~/data/showcase-images'

interface ShowcasePerfume {
  id: number
  brand: string
  name: string
  main_accord?: string | null
  rating?: number | null
  family?: ScentFamilyKey | null
}

const props = defineProps<{ perfumes: ShowcasePerfume[] }>()

const { isDark } = useScentWorld()

// ~5s of drift per card keeps apparent speed constant however many load.
const duration = computed(() => `${props.perfumes.length * 5}s`)

// Same colour derivation as DiscoverCard: curated family wins, else derive
// from the accord vocabulary. Pure function of the row + canvas mode.
function worldOf(p: ShowcasePerfume): ScentWorld {
  const accords = (p.main_accord ?? '').split(',').map((s) => s.trim()).filter(Boolean)
  return scentWorld(p.family ?? deriveFamily(accords), bottleSeed(String(p.id), p.name), isDark.value)
}
</script>

<template>
  <div class="marquee" :style="{ '--marquee-duration': duration }">
    <div class="marquee-rail">
      <!-- Track is rendered twice for the seamless -50% loop; the duplicate is
           inert for screen readers and keyboards. -->
      <div
        v-for="copy in 2"
        :key="copy"
        class="marquee-track"
        :aria-hidden="copy === 2 ? 'true' : undefined"
      >
        <NuxtLink
          v-for="p in perfumes"
          :key="`${copy}-${p.id}`"
          :to="`/user/perfume/${p.id}`"
          :tabindex="copy === 2 ? -1 : undefined"
          class="showcase-card block w-56 shrink-0"
        >
          <div
            class="relative aspect-3/4 overflow-hidden rounded-card border"
            style="border-color: var(--color-rule); background: var(--color-surface-2);"
          >
            <img
              :src="showcaseImageFor(p.id)"
              alt=""
              loading="lazy"
              class="absolute inset-0 h-full w-full object-cover"
            >
            <span
              class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1"
              style="background: rgba(20, 20, 20, 0.35); backdrop-filter: blur(6px);"
            >
              <span class="inline-block rounded-full" :style="{ width: '7px', height: '7px', background: worldOf(p).c1 }" />
              <span class="fm uppercase" style="font-size: 8.5px; letter-spacing: 0.12em; color: #fff;">{{ worldOf(p).family.label }}</span>
            </span>
          </div>
          <p class="mt-3 fm uppercase" style="font-size: 9px; letter-spacing: 0.16em; color: var(--color-ink-mute);">{{ p.brand }}</p>
          <h3 class="mt-1 fd line-clamp-1" style="font-size: 17px; color: var(--color-ink);">{{ p.name }}</h3>
          <p v-if="p.rating != null" class="mt-1 fm" style="font-size: 11px; color: var(--color-ink-mute);">★ {{ p.rating }}</p>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.marquee {
  overflow: hidden;
  -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
  mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
}

.marquee-rail {
  display: flex;
  width: max-content;
  animation: marquee-drift var(--marquee-duration, 60s) linear infinite;
}

.marquee:hover .marquee-rail {
  animation-play-state: paused;
}

/* gap + matching padding-right make each track exactly N*(card+gap) wide,
   so translateX(-50%) lands precisely one track over: a seamless loop. */
.marquee-track {
  display: flex;
  gap: 24px;
  padding-right: 24px;
}

@keyframes marquee-drift {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

@media (prefers-reduced-motion: reduce) {
  .marquee { overflow-x: auto; }
  .marquee-rail { animation: none; }
  .marquee-track[aria-hidden='true'] { display: none; }
}
</style>
```

- [ ] **Step 3: Wire it into about.vue**

In `frontend/app/pages/about.vue`, add to the `<script setup>` block, directly after `const world = worldFor(() => familyOfTheHour())`:

```ts
const api = useApi()

// Public top-rated slice for the showcase marquee. Any failure (backend down,
// empty catalogue) resolves to [] and the section hides itself — a marketing
// page shows no spinners and no error states.
const { data: showcase } = await useAsyncData(
  'about-showcase',
  () => api.get('/perfume?sort=rating&per_page=12').then((res) => res.data ?? []),
  { default: () => [] },
)
```

In the template, insert between the hero section's closing `</section>` and the `<!-- Three pillars -->` comment:

```vue
    <!-- Showcase marquee — top-rated bottles from the public catalogue -->
    <section v-if="showcase.length" class="pb-24">
      <p class="kicker text-center" :style="{ color: world.accent }">
        From the catalogue
      </p>
      <h2 class="fd mt-4 text-center" style="font-size: clamp(32px, 5vw, 52px); line-height: 1.1; letter-spacing: -0.01em; color: var(--color-ink);">
        The bottles people rate highest.
      </h2>
      <ShowcaseMarquee :perfumes="showcase" class="mt-14" />
    </section>
```

- [ ] **Step 4: Verify SSR output**

```bash
curl -s http://127.0.0.1:3005/about | grep -o 'showcase-card' | wc -l
```

Expected: `24` (12 perfumes × 2 tracks — proves the cards are server-rendered, not client-only). Then:

```bash
curl -s http://127.0.0.1:3005/about | grep -c 'From the catalogue'
```

Expected: `1` or more. If `showcase-card` count is `0`, check `docker compose -f docker-compose.dev.yml logs frontend` for compile errors before anything else.

- [ ] **Step 5: Verify motion in the browser**

Open `http://127.0.0.1:3005/about`:

- The strip below "From the catalogue" drifts left continuously with soft fades at both edges.
- Hovering anywhere on the strip pauses it; leaving resumes it.
- Clicking a card while logged out lands on `/auth/login` (auth middleware funnel — expected).
- No horizontal scrollbar on the page body.

- [ ] **Step 6: Verify reduced-motion fallback**

DevTools → Command Menu (Cmd+Shift+P) → "Show Rendering" → *Emulate CSS media feature prefers-reduced-motion* → `reduce`. Expected: the strip stops animating, becomes horizontally scrollable by trackpad/drag-scroll, and shows each perfume only once (duplicate track hidden).

- [ ] **Step 7: Verify graceful degradation with the backend down**

```bash
docker compose -f docker-compose.dev.yml stop backend
curl -s http://127.0.0.1:3005/about | grep -o 'showcase-card' | wc -l   # expected: 0
curl -s http://127.0.0.1:3005/about | grep -c "Wear what moves you"     # expected: ≥ 1
docker compose -f docker-compose.dev.yml start backend
```

The page must render fully (hero, pillars, CTA) with the showcase section absent — no error page, no hydration warnings in the frontend logs.

- [ ] **Step 8: Commit**

```bash
git add frontend/app/components/ShowcaseMarquee.vue frontend/app/pages/about.vue
git commit -m "feat: add top-rated showcase marquee to about page"
```

---

## Self-review notes

- Spec coverage: placement (Task 2 Step 3), top-rated ×12 (Steps 1/3), CSS marquee + hover pause + edge fade + a11y + reduced motion (Step 2), Unsplash determinism (Task 1), hide-on-failure (Steps 3/7), no-deps (no install steps anywhere). Spec's verification section maps 1:1 to Steps 4–7.
- No placeholders: every code step carries the complete code; every command carries expected output.
- Type consistency: `ShowcasePerfume` matches `PerfumeTransformer::toCatalog` field names (`main_accord`, `rating`, `family`); `showcaseImageFor(id: number): string` is used exactly as declared in Task 1.
