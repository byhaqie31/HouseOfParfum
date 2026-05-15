<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Welcome card — compact, minimal -->
      <section class="relative border border-rule bg-paper-deep overflow-hidden">
        <div class="absolute -top-px left-0 w-16 h-px bg-accent" />
        <div class="absolute -bottom-px right-0 w-10 h-px bg-accent" />

        <div class="flex items-center gap-6 sm:gap-8 px-5 sm:px-7 py-4">
          <!-- Round perfume flacon, sprays from the cap toward the greeting -->
          <div class="shrink-0 self-center z-0">
            <SprayBottle :size="64" />
          </div>

          <div class="relative z-10 min-w-0 flex-1">
            <h2 class="font-display text-xl sm:text-2xl text-ink tracking-[-0.005em] leading-[1.15]">
              Good {{ partOfDay }},
              <template v-if="firstName">
                <span class="capitalize">{{ firstName }}</span></template><template v-else>friend</template><span class="text-accent-deep">.</span>
            </h2>
            <p class="mt-2 font-display italic text-[12px] sm:text-[13px] text-ink-soft leading-snug">
              Smell good. Everytime, anywhere.
            </p>
          </div>

          <p class="hidden sm:block font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute shrink-0 self-center z-10">
            {{ formattedDate }}
          </p>
        </div>
      </section>

      <div class="mt-12" />

      <!-- Today's recommendation: mood/weather/occasion → matched bottle from the wardrobe -->
      <ScentMatcher />

      <div class="mt-16" />

      <!-- Wardrobe: empty-state CTA OR glance grid -->
      <template v-if="wardrobe.count === 0">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-ink-mute mb-6">
          <span class="text-accent-deep mr-2">/</span>Your wardrobe
        </p>
        <div class="border-t border-ink relative py-12 px-2">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <h2 class="font-display text-3xl sm:text-4xl text-ink tracking-tight leading-tight max-w-xl">
            Your shelf is bare<em class="text-ink-soft">.</em>
          </h2>
          <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-md">
            Add a bottle to track what you wear and when.
          </p>
          <NuxtLink
            to="/user/wardrobe/add"
            class="mt-8 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-6 py-3 hover:bg-ink-soft transition-colors"
          >
            Add a bottle
            <Icon name="lucide:arrow-right" size="14" />
          </NuxtLink>
        </div>
      </template>

      <!-- Wardrobe glance — visible when shelf has items -->
      <section v-else>
        <header class="flex items-baseline justify-between mb-6">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.24em] text-ink-mute">
            <span class="font-mono text-accent-deep mr-2">/</span>Your wardrobe
          </p>
          <NuxtLink
            to="/user/wardrobe"
            class="font-display italic text-[12px] text-ink-soft hover:text-ink transition-colors"
          >
            View all {{ wardrobe.count }} &rarr;
          </NuxtLink>
        </header>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
          <NuxtLink
            v-for="item in wardrobeGlance"
            :key="item.id"
            :to="`/user/wardrobe/${item.id}`"
            class="group aspect-3/4 bg-paper-deep border border-rule p-4 flex flex-col hover:bg-paper transition-colors duration-200"
          >
            <div class="flex-1 flex items-center justify-center">
              <BottleIcon :size="48" />
            </div>
            <p class="font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute leading-snug">
              {{ item.brand }}
            </p>
            <p class="mt-1 font-display text-[18px] text-ink group-hover:text-accent-deep leading-tight transition-colors">
              {{ item.name }}
            </p>
          </NuxtLink>
        </div>
      </section>

      <!-- Field notes — one perfumery fact per day, date-seeded -->
      <section v-if="knowledge" class="mt-16 pt-10 border-t border-ink relative">
        <div class="absolute -top-px left-0 w-20 h-px bg-accent" />

        <header class="mb-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep">
            Field notes
          </p>
          <h2 class="mt-1 font-display text-3xl text-ink tracking-tight leading-tight">
            <em class="text-ink-soft">One thing</em> about perfume.
          </h2>
        </header>

        <article class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-8 md:gap-12 bg-paper-deep border border-rule p-8 md:p-10 relative">
          <div class="absolute -top-px left-0 w-16 h-px bg-accent" />

          <div>
            <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-accent-deep">
              {{ knowledge.kicker }}
            </p>
            <h3 class="mt-3 font-display text-[26px] sm:text-[28px] text-ink leading-[1.1] tracking-tight">
              {{ knowledge.title }}
            </h3>
          </div>

          <p class="font-display italic text-[16px] sm:text-[17px] text-ink-soft leading-[1.6] max-w-prose">
            {{ knowledge.body }}
          </p>
        </article>

        <!-- Bridge into the long-form reference -->
        <div class="mt-8 flex justify-end">
          <NuxtLink
            to="/user/almanac"
            class="inline-flex items-center gap-2 font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          >
            Learn more in The Almanac
            <Icon name="lucide:arrow-right" size="14" />
          </NuxtLink>
        </div>
      </section>

      <!-- Recommendations (always visible when catalog has unowned perfumes) -->
      <section v-if="totalRecPages > 0" class="mt-16 pt-10 border-t border-ink relative">
        <div class="absolute -top-px left-0 w-20 h-px bg-accent" />

        <header class="flex items-baseline justify-between mb-6 gap-4">
          <div>
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep">
              Worth a try
            </p>
            <h2 class="mt-1 font-display text-3xl text-ink tracking-tight leading-tight">
              <em class="text-ink-soft">Two</em> from outside your shelf.
            </h2>
          </div>

          <div v-if="totalRecPages > 1" class="flex items-center gap-2 shrink-0">
            <button
              type="button"
              aria-label="Previous pair"
              class="w-9 h-9 flex items-center justify-center border border-rule bg-paper-deep text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
              @click="prevRecPage"
            >
              <Icon name="lucide:chevron-left" size="16" />
            </button>
            <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute min-w-[3.5em] text-center">
              {{ String(recPage + 1).padStart(2, '0') }} / {{ String(totalRecPages).padStart(2, '0') }}
            </span>
            <button
              type="button"
              aria-label="Next pair"
              class="w-9 h-9 flex items-center justify-center border border-rule bg-paper-deep text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
              @click="nextRecPage"
            >
              <Icon name="lucide:chevron-right" size="16" />
            </button>
          </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <NuxtLink
            v-for="rec in visibleRecommendations"
            :key="rec.id"
            :to="`/user/perfume/${rec.id}`"
            class="group grid grid-cols-[100px_1fr] sm:grid-cols-[120px_1fr] gap-4 bg-paper border border-rule p-4 hover:bg-paper-deep transition-colors duration-200"
          >
            <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center group-hover:bg-paper transition-colors duration-200">
              <BottleIcon :size="56" />
            </div>
            <div class="flex flex-col justify-center min-w-0">
              <p class="font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute">
                {{ rec.brand }}
              </p>
              <h3 class="mt-1 font-display text-[18px] text-ink leading-tight group-hover:text-accent-deep transition-colors">
                {{ rec.name }}
              </h3>
              <p
                v-if="rec.main_accord"
                class="mt-2 font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute leading-snug line-clamp-2"
              >
                {{ formatAccord(rec.main_accord) }}
              </p>
              <p class="mt-3 font-display italic text-[12px] text-ink group-hover:text-accent-deep border-b border-accent inline-block self-start pb-px transition-colors">
                View details &rarr;
              </p>
            </div>
          </NuxtLink>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

type Perfume = {
  id: number
  brand: string
  name: string
  year?: number | string
  main_accord?: string
}

const auth = useAuthStore()
const api = useApi()
const wardrobe = useWardrobeStore()

const now = new Date()

const formattedDate = computed(() =>
  now
    .toLocaleDateString('en-GB', {
      weekday: 'long',
      day: 'numeric',
      month: 'long',
    })
    .toUpperCase(),
)

const partOfDay = computed(() => {
  const h = now.getHours()
  if (h < 12) return 'morning'
  if (h < 18) return 'afternoon'
  return 'evening'
})

const firstName = computed(() => {
  const raw = auth.user?.name
  if (typeof raw !== 'string') return ''
  return raw.split(' ')[0]?.toLowerCase() ?? ''
})

// ──────────── Wardrobe glance (first 4 bottles, used on Today) ────────────
import type { WardrobeItem } from '~/stores/wardrobe'

const wardrobeGlance = computed(() => wardrobe.items.slice(0, 4))

// ──────────── Recommendations from catalog ────────────
const catalogPerfumes = ref<Perfume[]>([])
const shuffledRecs = ref<Perfume[]>([])
const recPage = ref(0)
const RECS_PER_PAGE = 2

// ──────────── Field notes — daily perfumery knowledge ────────────
const knowledge = useDailyKnowledge(now)

const formatAccord = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

const buildRecommendations = () => {
  const ownedCatalogIds = new Set(
    wardrobe.items
      .map((i: WardrobeItem) => i.catalog_id)
      .filter((id: number | null): id is number => typeof id === 'number'),
  )
  const candidates = catalogPerfumes.value.filter((p: Perfume) => !ownedCatalogIds.has(p.id))
  // Fisher-Yates
  const arr = [...candidates]
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1))
    const tmp = arr[i]!
    arr[i] = arr[j]!
    arr[j] = tmp
  }
  shuffledRecs.value = arr
  recPage.value = 0
}

const totalRecPages = computed(() =>
  Math.ceil(shuffledRecs.value.length / RECS_PER_PAGE),
)

const visibleRecommendations = computed(() => {
  const start = recPage.value * RECS_PER_PAGE
  return shuffledRecs.value.slice(start, start + RECS_PER_PAGE)
})

const nextRecPage = () => {
  if (totalRecPages.value === 0) return
  recPage.value = (recPage.value + 1) % totalRecPages.value
}

const prevRecPage = () => {
  if (totalRecPages.value === 0) return
  recPage.value = (recPage.value - 1 + totalRecPages.value) % totalRecPages.value
}

onMounted(async () => {
  try {
    // The catalog is 24k rows — pull the top-rated subset for the cross-sell.
    const res = await api.get('/perfume?sort=rating&direction=desc&per_page=100')
    catalogPerfumes.value = res.data ?? []
    buildRecommendations()
  } catch (e) {
    console.warn('[today] catalog load failed', e)
  }
})

// Rebuild "Worth a try" recommendations when the wardrobe changes so newly
// added bottles get excluded from the cross-sell.
watch(
  () => wardrobe.items.length,
  () => {
    if (catalogPerfumes.value.length > 0) buildRecommendations()
  },
)
</script>

