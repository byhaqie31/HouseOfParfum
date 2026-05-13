<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
      <!-- Greeting -->
      <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-ink-mute leading-[1.7]">
        Good {{ partOfDay }}, {{ firstName || 'friend' }}
        <span class="text-accent-deep mx-1">·</span>
        {{ formattedDate }}
      </p>

      <!-- Section divider with gold accent -->
      <div class="relative border-b border-ink mt-4 mb-10">
        <div class="absolute -bottom-px left-0 w-20 h-px bg-accent" />
      </div>

      <!-- Empty vanity → invite to add -->
      <template v-if="vanity.count === 0">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
          Today
        </p>
        <div class="border-y border-ink relative py-12 px-2">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <h2 class="font-display text-3xl sm:text-4xl text-ink tracking-tight leading-tight max-w-xl">
            Your shelf is bare<em class="text-ink-soft">.</em>
          </h2>
          <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-md">
            Add a bottle to your vanity and the daily pick begins.
          </p>
          <NuxtLink
            to="/vanity/add"
            class="mt-8 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-6 py-3 hover:bg-ink-soft transition-colors"
          >
            Add a bottle
            <Icon name="lucide:arrow-right" size="14" />
          </NuxtLink>
        </div>
      </template>

      <!-- Today's pick + glance -->
      <template v-else-if="todayPick">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
          Today
        </p>

        <section class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-8 sm:gap-10 border-b border-ink pb-10">
          <!-- Bottle frame -->
          <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center">
            <BottleIcon :size="64" />
          </div>

          <div>
            <p class="font-display italic text-[15px] text-ink-soft leading-tight">
              {{ todayPick.brand }}
            </p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl tracking-[-0.005em] leading-none">
              <NuxtLink
                :to="`/vanity/${todayPick.id}`"
                class="text-ink hover:text-accent-deep transition-colors"
              >
                {{ todayPick.name }}
              </NuxtLink>
            </h1>
            <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ todayPick.size }} ML
              <template v-if="todayPick.acquired"> &middot; {{ todayPick.acquired }}</template>
            </p>

            <p
              class="mt-5 pt-4 border-t border-rule font-display italic text-[15px] leading-normal"
              :class="isPickWornToday ? 'text-accent-deep' : 'text-ink-soft'"
            >
              <template v-if="isPickWornToday">
                You're wearing this now.
              </template>
              <template v-else>
                {{ pickReason }}
              </template>
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
              <NuxtLink
                v-if="isPickWornToday"
                :to="`/vanity/${todayPick.id}`"
                class="inline-flex items-center gap-2 bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors"
              >
                Update diary
                <Icon name="lucide:arrow-right" size="14" />
              </NuxtLink>
              <button
                v-else
                type="button"
                class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors"
                @click="wearThis"
              >
                I'm wearing this
              </button>
              <button
                v-if="orderedCandidates.length > 1 && !isPickWornToday"
                type="button"
                class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
                @click="showAnother"
              >
                Show me another
              </button>
            </div>
          </div>
        </section>

        <!-- Your Vanity glance -->
        <section class="mt-12">
          <header class="flex items-baseline justify-between mb-6">
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.24em] text-ink-mute">
              <span class="font-mono text-accent-deep mr-2">/</span>Your vanity
            </p>
            <NuxtLink
              to="/vanity"
              class="font-display italic text-[12px] text-ink-soft hover:text-ink transition-colors"
            >
              View all {{ vanity.count }} &rarr;
            </NuxtLink>
          </header>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <NuxtLink
              v-for="item in vanityGlance"
              :key="item.id"
              :to="`/vanity/${item.id}`"
              class="group aspect-3/4 bg-paper-deep border border-rule p-3 flex flex-col hover:bg-paper transition-colors duration-200"
            >
              <div class="flex-1 flex items-center justify-center">
                <BottleIcon :size="44" />
              </div>
              <p class="font-mono text-[7px] uppercase tracking-[0.14em] text-ink-mute leading-snug">
                {{ item.brand }}
              </p>
              <p class="font-display text-[11px] text-ink group-hover:text-accent-deep leading-[1.15] mt-0.5 transition-colors">
                {{ item.name }}
              </p>
            </NuxtLink>
          </div>
        </section>
      </template>

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
            :to="`/perfume/${rec.id}`"
            class="group grid grid-cols-[100px_1fr] sm:grid-cols-[120px_1fr] gap-4 bg-paper border border-rule p-4 hover:bg-paper-deep transition-colors duration-200"
          >
            <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center group-hover:bg-paper transition-colors duration-200">
              <BottleIcon :size="56" />
            </div>
            <div class="flex flex-col justify-center min-w-0">
              <p class="font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute">
                {{ brandByCode[rec.brand]?.name || rec.brand }}
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

type Brand = { id: number; code: string; name: string }
type Perfume = {
  id: number
  brand: string
  name: string
  size: number
  year?: string
  main_accord?: string
}

const auth = useAuthStore()
const api = useApi()
const vanity = useVanityStore()
const journal = useJournalStore()

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

// ──────────── Today's pick from vanity ────────────
//
// orderedCandidates is a SNAPSHOT taken at page mount (and when vanity changes).
// We deliberately don't re-derive from journal.lastWornAt on every wear, so
// clicking "I'm wearing this" doesn't shuffle the displayed pick to the bottom
// of the ranking and replace it with the next candidate.
import type { VanityItem } from '~/stores/vanity'

const orderedCandidates = ref<VanityItem[]>([])
const pickIndex = ref(0)

const sortByLastWorn = (items: VanityItem[]): VanityItem[] => {
  return [...items].sort((a, b) => {
    const la = journal.lastWornAt(a.id)
    const lb = journal.lastWornAt(b.id)
    if (!la && !lb) return 0
    if (!la) return -1
    if (!lb) return 1
    return la < lb ? -1 : la > lb ? 1 : 0
  })
}

const refreshCandidates = () => {
  orderedCandidates.value = sortByLastWorn(vanity.items)
  pickIndex.value = 0
}

const todayPick = computed(() => orderedCandidates.value[pickIndex.value] ?? null)

const vanityGlance = computed(() => vanity.items.slice(0, 4))

const isSameDay = (iso: string) => {
  const a = new Date(iso)
  const b = new Date()
  return (
    a.getFullYear() === b.getFullYear()
    && a.getMonth() === b.getMonth()
    && a.getDate() === b.getDate()
  )
}

// Reactively detect whether the currently-displayed pick already has a wear logged today.
const isPickWornToday = computed(() => {
  const p = todayPick.value
  if (!p) return false
  return journal.entries.some(
    e => e.vanity_item_id === p.id && isSameDay(e.worn_at),
  )
})

const pickReason = computed(() => {
  if (!todayPick.value) return ''
  const last = journal.lastWornAt(todayPick.value.id)
  if (!last) return 'Newly on the shelf — try it today.'
  const days = Math.floor(
    (Date.now() - new Date(last).getTime()) / (1000 * 60 * 60 * 24),
  )
  if (days === 0) return 'You wore this earlier today.'
  if (days === 1) return 'Worn yesterday — give it another day.'
  if (days < 7) return `You haven't worn this in ${days} days.`
  if (days < 30)
    return `You haven't reached for this in ${days} days — give it some air.`
  if (days < 365) {
    const months = Math.floor(days / 30)
    return `It's been ${months} ${months === 1 ? 'month' : 'months'} since you last wore this.`
  }
  return "It's been over a year since you last wore this."
})

const wearThis = () => {
  if (!todayPick.value || isPickWornToday.value) return
  journal.log({
    vanity_item_id: todayPick.value.id,
    brand: todayPick.value.brand,
    name: todayPick.value.name,
  })
}

const showAnother = () => {
  if (orderedCandidates.value.length === 0) return
  pickIndex.value = (pickIndex.value + 1) % orderedCandidates.value.length
}

// ──────────── Recommendations from catalog ────────────
const catalogPerfumes = ref<Perfume[]>([])
const brands = ref<Brand[]>([])
const shuffledRecs = ref<Perfume[]>([])
const recPage = ref(0)
const RECS_PER_PAGE = 2

const brandByCode = computed(() =>
  Object.fromEntries(brands.value.map((b: Brand) => [b.code, b])),
)

const formatAccord = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

const buildRecommendations = () => {
  const ownedCatalogIds = new Set(
    vanity.items
      .map(i => i.catalog_id)
      .filter((id): id is number => typeof id === 'number'),
  )
  const candidates = catalogPerfumes.value.filter(p => !ownedCatalogIds.has(p.id))
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
  // Snapshot today's pick ordering once on mount — see the comment on
  // orderedCandidates above for why we don't recompute on every journal change.
  refreshCandidates()

  try {
    const [perfumeData, brandData] = await Promise.all([
      api.get('/perfume'),
      api.get('/brand'),
    ])
    catalogPerfumes.value = perfumeData
    brands.value = brandData
    buildRecommendations()
  } catch (e) {
    console.warn('[today] catalog load failed', e)
  }
})

// Re-snapshot when vanity changes (bottle added/removed). Wear-clicks don't
// change vanity.items.length, so the pick stays locked when only journal updates.
watch(
  () => vanity.items.length,
  () => {
    refreshCandidates()
    if (catalogPerfumes.value.length > 0) buildRecommendations()
  },
)
</script>
