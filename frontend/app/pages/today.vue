<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
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

      <!-- Empty wardrobe → invite to add -->
      <template v-if="wardrobe.count === 0">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
          Today
        </p>
        <div class="border-t border-ink relative py-12 px-2">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <h2 class="font-display text-3xl sm:text-4xl text-ink tracking-tight leading-tight max-w-xl">
            Your shelf is bare<em class="text-ink-soft">.</em>
          </h2>
          <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-md">
            Add a bottle to your wardrobe and the daily pick begins.
          </p>
          <NuxtLink
            to="/wardrobe/add"
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
          Today's recommendation
        </p>

        <!-- Framed pick card: bottle in a narrow column, identity + story + actions in the wide column -->
        <section class="relative border border-ink bg-paper">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <div class="absolute -bottom-px right-0 w-12 h-px bg-accent" />

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-0">
            <!-- Left column: bottle frame capped + centered so the 1:1 column doesn't stretch it absurdly tall -->
            <div class="p-8 md:p-10 md:border-r md:border-rule flex items-center justify-center">
              <div class="aspect-3/4 w-full max-w-[280px] bg-paper-deep border border-rule flex items-center justify-center">
                <BottleIcon :size="120" />
              </div>
            </div>

            <!-- Right column: identity, story, actions (flex-col so the action row anchors to the bottom) -->
            <div class="p-8 md:p-10 flex flex-col min-w-0">
              <!-- Identity: brand + name + meta — name has the whole column width so long names fit on one line -->
              <p class="font-display italic text-[15px] text-ink-soft leading-tight">
                {{ todayPick.brand }}
              </p>
              <h1 class="mt-1 font-display text-3xl sm:text-4xl text-ink tracking-[-0.005em] leading-[1.05]">
                <NuxtLink
                  :to="`/wardrobe/${todayPick.id}`"
                  class="hover:text-accent-deep transition-colors"
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

              <!-- Accord chips -->
              <div v-if="pickAccordChips.length" class="mt-6">
                <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-3">
                  <span class="text-accent-deep mr-1.5">/</span>Accord
                </p>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="chip in pickAccordChips"
                    :key="chip"
                    class="px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-ink bg-paper-deep border border-rule"
                  >
                    {{ chip }}
                  </span>
                </div>
              </div>

              <!-- Environment recommendation (season + time of day) -->
              <div v-if="pickEnvironmentLine" class="mt-6">
                <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-2">
                  <span class="text-accent-deep mr-1.5">/</span>Where it wears
                </p>
                <p class="font-display italic text-[15px] text-ink leading-normal">
                  {{ pickEnvironmentLine }}
                </p>
              </div>

              <!-- History excerpt -->
              <div v-if="pickHistoryExcerpt" class="mt-6">
                <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-2">
                  <span class="text-accent-deep mr-1.5">/</span>The history
                </p>
                <p class="font-display italic text-[14px] text-ink-soft leading-normal max-w-prose">
                  {{ pickHistoryExcerpt }}
                </p>
              </div>

              <NuxtLink
                v-if="todayPick?.catalog_id && pickCatalogEntry"
                :to="`/perfume/${todayPick.catalog_id}`"
                class="mt-5 inline-block self-start font-display italic text-[13px] text-ink hover:text-accent-deep pb-px border-b border-accent transition-colors"
              >
                {{ pickHistoryWasTruncated ? 'Continue reading' : 'Open the dossier' }} &rarr;
              </NuxtLink>

              <!-- Action row pinned to the bottom of the right column -->
              <div class="mt-auto pt-8">
                <div class="border-t border-rule pt-6 flex flex-wrap items-center justify-between gap-4">
                  <button
                    v-if="orderedCandidates.length > 1 && !isPickWornToday"
                    type="button"
                    class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
                    @click="showAnother"
                  >
                    Show me another
                  </button>
                  <span v-else aria-hidden="true" />

                  <NuxtLink
                    v-if="isPickWornToday"
                    :to="`/wardrobe/${todayPick.id}`"
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
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Your Wardrobe glance -->
        <section class="mt-12">
          <header class="flex items-baseline justify-between mb-6">
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.24em] text-ink-mute">
              <span class="font-mono text-accent-deep mr-2">/</span>Your wardrobe
            </p>
            <NuxtLink
              to="/wardrobe"
              class="font-display italic text-[12px] text-ink-soft hover:text-ink transition-colors"
            >
              View all {{ wardrobe.count }} &rarr;
            </NuxtLink>
          </header>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <NuxtLink
              v-for="item in wardrobeGlance"
              :key="item.id"
              :to="`/wardrobe/${item.id}`"
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
      </template>

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
            to="/almanac"
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
  history?: string
  suit_season?: string
  suit_time?: string
}

const HISTORY_EXCERPT_LIMIT = 240

const auth = useAuthStore()
const api = useApi()
const wardrobe = useWardrobeStore()
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

// ──────────── Today's pick from wardrobe ────────────
//
// orderedCandidates is a SNAPSHOT taken at page mount (and when wardrobe changes).
// We deliberately don't re-derive from journal.lastWornAt on every wear, so
// clicking "I'm wearing this" doesn't shuffle the displayed pick to the bottom
// of the ranking and replace it with the next candidate.
import type { WardrobeItem } from '~/stores/wardrobe'
import type { JournalEntry } from '~/stores/journal'

const orderedCandidates = ref<WardrobeItem[]>([])
const pickIndex = ref(0)

const sortByLastWorn = (items: WardrobeItem[]): WardrobeItem[] => {
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
  orderedCandidates.value = sortByLastWorn(wardrobe.items)
  pickIndex.value = 0
}

const todayPick = computed(() => orderedCandidates.value[pickIndex.value] ?? null)

const wardrobeGlance = computed(() => wardrobe.items.slice(0, 4))

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
    (e: JournalEntry) => e.wardrobe_item_id === p.id && isSameDay(e.worn_at),
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
    wardrobe_item_id: todayPick.value.id,
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

// ──────────── Today's pick — catalog enrichment ────────────
// The wardrobe item carries `catalog_id` when it was added from /perfume.
// When present, we look up accord + history from the loaded catalog and
// surface them in the right-column story panel. Free-entered bottles
// (catalog_id === null) silently skip the block.
const pickCatalogEntry = computed<Perfume | null>(() => {
  const id = todayPick.value?.catalog_id
  if (typeof id !== 'number') return null
  return catalogPerfumes.value.find((p: Perfume) => p.id === id) ?? null
})

const pickAccordChips = computed<string[]>(() => {
  const raw = pickCatalogEntry.value?.main_accord
  if (!raw || typeof raw !== 'string') return []
  return raw.split(',').map(s => s.trim()).filter(Boolean)
})

const pickHistoryRaw = computed(() => pickCatalogEntry.value?.history?.trim() ?? '')

const pickHistoryExcerpt = computed(() => {
  const raw = pickHistoryRaw.value
  if (!raw) return ''
  if (raw.length <= HISTORY_EXCERPT_LIMIT) return raw
  // Trim at the last sentence/word boundary before the limit so we don't cut a word in half.
  const slice = raw.slice(0, HISTORY_EXCERPT_LIMIT)
  const cut = Math.max(slice.lastIndexOf('. '), slice.lastIndexOf('? '), slice.lastIndexOf('! '))
  if (cut > HISTORY_EXCERPT_LIMIT * 0.5) return `${slice.slice(0, cut + 1)}…`
  const lastSpace = slice.lastIndexOf(' ')
  return `${slice.slice(0, lastSpace > 0 ? lastSpace : HISTORY_EXCERPT_LIMIT)}…`
})

const pickHistoryWasTruncated = computed(
  () => pickHistoryRaw.value.length > HISTORY_EXCERPT_LIMIT,
)

const pickEnvironmentLine = computed(() => {
  const entry = pickCatalogEntry.value
  if (!entry) return ''
  const season = entry.suit_season?.trim().toLowerCase()
  const time = entry.suit_time?.trim().toLowerCase()
  if (!season && !time) return ''
  if (season && time) return `Best worn through ${season}, suited to the ${time}.`
  if (season) return `Best worn through ${season}.`
  return `Suited to the ${time}.`
})

// ──────────── Field notes — daily perfumery knowledge ────────────
const knowledge = useDailyKnowledge(now)

const brandByCode = computed(() =>
  Object.fromEntries(brands.value.map((b: Brand) => [b.code, b])),
)

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

// Re-snapshot when wardrobe changes (bottle added/removed). Wear-clicks don't
// change wardrobe.items.length, so the pick stays locked when only journal updates.
watch(
  () => wardrobe.items.length,
  () => {
    refreshCandidates()
    if (catalogPerfumes.value.length > 0) buildRecommendations()
  },
)
</script>

