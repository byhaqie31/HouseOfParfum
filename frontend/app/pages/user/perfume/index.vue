<template>
  <div>
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <header class="border-b border-rule pb-8">
        <p class="kicker">The catalog</p>
        <h1 class="mt-3 fd text-5xl sm:text-6xl tracking-tight leading-[1.05]" style="color: var(--color-ink);">
          Discover
        </h1>
        <p class="mt-4 fd italic text-[15px] max-w-xl" style="color: var(--color-ink-soft);">
          Browse the catalogue, search by house or name, and add what's already on your shelf.
        </p>
      </header>

      <!-- Primary controls: search + house + sex (equal thirds) -->
      <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label for="catalog-search" class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
            Search
          </label>
          <input
            id="catalog-search"
            v-model="searchQuery"
            type="text"
            autocomplete="off"
            placeholder="Brand or name…"
            class="w-full h-[46px] rounded-field bg-paper-deep border border-rule px-4 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
          >
        </div>
        <div>
          <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
            House
          </label>
          <EditorialSelect
            v-model="brand"
            :options="brandSelectOptions"
            searchable
            placeholder="All houses"
            search-placeholder="Search houses…"
          />
        </div>
        <div>
          <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
            For
          </label>
          <EditorialSelect v-model="gender" :options="SEX_OPTIONS" placeholder="Anyone" />
        </div>
      </div>

      <!-- Filters toggle — left, below the search row -->
      <div class="mt-6">
        <button
          type="button"
          class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-mono text-[10px] uppercase tracking-[0.16em] border transition-colors"
          :class="showFilters ? '' : 'bg-paper-deep border-rule text-ink hover:border-ink-soft'"
          :style="showFilters ? { background: houseWorld.soft, borderColor: houseWorld.accent, color: houseWorld.accent } : undefined"
          @click="showFilters = !showFilters"
        >
          <Icon name="lucide:sliders-horizontal" size="13" />
          Filters
          <span
            v-if="activeFilterCount"
            class="font-mono text-[9px] rounded-full px-1.5 py-px"
            :style="{ background: houseWorld.accent, color: 'var(--color-canvas)' }"
          >{{ activeFilterCount }}</span>
        </button>
      </div>

      <!-- Advanced filter panel -->
      <div v-if="showFilters" class="mt-4 rounded-card border border-rule bg-paper-deep p-5 sm:p-6">
        <!-- A–Z name index -->
        <div>
          <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
            Name index
          </label>
          <div class="flex flex-wrap items-center gap-1.5">
            <button
              type="button"
              class="px-2.5 py-1 rounded-full font-mono text-[10px] uppercase tracking-widest border transition-colors"
              :class="letter === '' ? '' : 'bg-paper border-rule text-ink-soft hover:border-ink-soft'"
              :style="letter === '' ? { background: houseWorld.soft, borderColor: houseWorld.accent, color: houseWorld.accent } : undefined"
              @click="letter = ''"
            >
              All
            </button>
            <button
              v-for="l in LETTERS"
              :key="l"
              type="button"
              class="w-7 py-1 rounded-full font-mono text-[10px] border transition-colors"
              :class="letter === l ? '' : 'bg-paper border-rule text-ink-soft hover:border-ink-soft'"
              :style="letter === l ? { background: houseWorld.soft, borderColor: houseWorld.accent, color: houseWorld.accent } : undefined"
              @click="letter = l"
            >
              {{ l }}
            </button>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-[1fr_200px_180px] gap-6">
          <!-- Notes (multi-select, match all) — picker above, chosen notes below -->
          <div>
            <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
              Notes <span class="normal-case tracking-normal" style="color: var(--color-ink-mute);">— must contain all</span>
            </label>
            <EditorialSelect
              :model-value="''"
              :options="noteSelectOptions"
              searchable
              placeholder="Add a note…"
              search-placeholder="Search notes…"
              @update:model-value="addNote"
            />
            <div v-if="selectedNotes.length" class="flex flex-wrap gap-1.5 mt-2">
              <button
                v-for="n in selectedNotes"
                :key="n"
                type="button"
                class="inline-flex items-center gap-1 rounded-full px-2 py-1 font-mono text-[9px] uppercase tracking-widest border"
                :style="{ background: houseWorld.soft, borderColor: houseWorld.accent, color: houseWorld.accent }"
                @click="removeNote(n)"
              >
                {{ n }}
                <Icon name="lucide:x" size="10" />
              </button>
            </div>
          </div>

          <!-- Season -->
          <div>
            <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
              Season
            </label>
            <EditorialSelect v-model="season" :options="SEASON_OPTIONS" placeholder="Any season" />
          </div>

          <!-- Rating -->
          <div>
            <label class="block fd font-medium text-[10px] uppercase tracking-[0.22em] mb-2" style="color: var(--color-ink-soft);">
              Rating
            </label>
            <EditorialSelect v-model="ratingMin" :options="RATING_OPTIONS" placeholder="Any rating" />
          </div>
        </div>

        <div v-if="activeFilterCount" class="mt-5 pt-4 border-t border-rule">
          <button
            type="button"
            class="fd italic text-[13px] transition-colors"
            style="color: var(--color-ink-soft);"
            @click="clearFilters"
          >
            Clear filters
          </button>
        </div>
      </div>

      <!-- Control bar: count (left) · sort (right) -->
      <div class="mt-6 flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
        <p class="font-mono text-[10px] uppercase tracking-[0.18em]" style="color: var(--color-ink-mute);">
          {{ total.toLocaleString() }} {{ total === 1 ? 'fragrance' : 'fragrances' }}
          <template v-if="!loading && perfumes.length">
            <span class="mx-1" :style="{ color: houseWorld.accent }">·</span>showing {{ perfumes.length }}
          </template>
        </p>

        <div class="flex flex-wrap items-center gap-x-2 gap-y-1.5">
          <span class="font-mono text-[10px] uppercase tracking-[0.14em] mr-1" style="color: var(--color-ink-mute);">Sort</span>
          <button
            v-for="opt in SORT_OPTIONS"
            :key="opt.value"
            type="button"
            class="px-2.5 py-1 rounded-full font-mono text-[11px] uppercase tracking-[0.12em] border transition-colors"
            :class="sort === opt.value ? '' : 'bg-paper-deep border-rule text-ink-soft hover:border-ink-soft hover:text-ink'"
            :style="sort === opt.value ? { background: houseWorld.soft, borderColor: houseWorld.accent, color: houseWorld.accent } : undefined"
            @click="sort = opt.value"
          >
            {{ opt.label }}
          </button>
        </div>
      </div>

      <!-- Loading -->
      <p v-if="loading" class="mt-16 text-center fd italic" style="color: var(--color-ink-soft);">
        Drawing from the cabinet…
      </p>

      <!-- Empty -->
      <p v-else-if="perfumes.length === 0" class="mt-16 text-center fd italic" style="color: var(--color-ink-soft);">
        Nothing matches that. Loosen a filter or clear the search.
      </p>

      <!-- Grid: 2 cols × 4 rows = 8 on mobile, 4 cols × 4 rows = 16 on desktop.
           Each card wears the colour of its scent family (derived from main_accord). -->
      <ul v-else class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-4">
        <li v-for="p in perfumes" :key="p.id">
          <DiscoverCard :perfume="p" />
        </li>
      </ul>

      <!-- Pagination -->
      <div
        v-if="!loading && lastPage > 1"
        class="mt-12 flex items-center justify-center gap-6 font-mono text-[10px] uppercase tracking-[0.24em]"
      >
        <button
          type="button"
          class="text-ink hover:text-accent-deep transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
          :disabled="page <= 1"
          @click="goToPage(page - 1)"
        >
          &larr; Previous
        </button>
        <span style="color: var(--color-ink-mute);">
          Page {{ page }} / {{ lastPage.toLocaleString() }}
        </span>
        <button
          type="button"
          class="text-ink hover:text-accent-deep transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
          :disabled="page >= lastPage"
          @click="goToPage(page + 1)"
        >
          Next &rarr;
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'
import type { ScentFamilyKey } from '~/utils/scent'

definePageMeta({ layout: 'app', middleware: 'auth' })

type Perfume = {
  id: number
  brand: string
  name: string
  main_accord?: string | null
  rating?: number | null
  family?: ScentFamilyKey | null
}

const api = useApi()

// The day's accent tints the active chips, like the rest of the app's chrome.
const { worldFor } = useScentWorld()
const houseWorld = worldFor(() => familyOfTheHour())

const perfumes = ref<Perfume[]>([])
const total = ref(0)
const lastPage = ref(1)
const loading = ref(true)
const page = ref(1)

// Filters — `''` / `[]` mean "no filter".
const searchQuery = ref('')
const brand = ref('')
const gender = ref('')
const letter = ref('')
const season = ref('')
const ratingMin = ref('')
const selectedNotes = ref<string[]>([])
const sort = ref('name_asc')
const showFilters = ref(false)

// Facets (brand list + note vocabulary) for the pickers.
const brandOptions = ref<string[]>([])
const noteOptions = ref<string[]>([])

const LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')

// Static dropdown option sets.
const SEX_OPTIONS = [
  { value: '', label: 'Anyone' },
  { value: 'men', label: 'Men' },
  { value: 'women', label: 'Women' },
  { value: 'unisex', label: 'Unisex' },
]
const SORT_OPTIONS = [
  { value: 'name_asc', label: 'A - Z' },
  { value: 'name_desc', label: 'Z - A' },
  { value: 'rating', label: 'Most rated' },
  { value: 'votes', label: 'Most liked' },
]
const SEASON_OPTIONS = [
  { value: '', label: 'Any season' },
  { value: 'Summer', label: 'Summer' },
  { value: 'Autumn', label: 'Autumn' },
  { value: 'Winter', label: 'Winter' },
  { value: 'Spring', label: 'Spring' },
]
const RATING_OPTIONS = [
  { value: '', label: 'Any rating' },
  { value: '3.5', label: '3.5+' },
  { value: '4', label: '4.0+' },
  { value: '4.5', label: '4.5+' },
]

// Facet-backed option sets.
const brandSelectOptions = computed(() => [
  { value: '', label: 'All houses' },
  ...brandOptions.value.map((b) => ({ value: b, label: b })),
])
const noteSelectOptions = computed(() =>
  noteOptions.value
    .filter((n) => !selectedNotes.value.includes(n))
    .map((n) => ({ value: n, label: n })),
)

// Responsive page size: 8 on mobile (2 cols × 4 rows), 16 on desktop (4 cols × 4 rows)
const isLargeScreen = ref(false)
const perPage = computed(() => (isLargeScreen.value ? 16 : 8))

const activeFilterCount = computed(
  () =>
    (letter.value ? 1 : 0)
    + (season.value ? 1 : 0)
    + (ratingMin.value ? 1 : 0)
    + selectedNotes.value.length,
)

function addNote(n: string) {
  if (n && !selectedNotes.value.includes(n)) selectedNotes.value.push(n)
}

function removeNote(n: string) {
  selectedNotes.value = selectedNotes.value.filter((x) => x !== n)
}

function clearFilters() {
  letter.value = ''
  season.value = ''
  ratingMin.value = ''
  selectedNotes.value = []
}

// The catalog is 24k rows — every filter is applied server-side via /api/perfume.
async function fetchPage() {
  loading.value = true
  try {
    const params = new URLSearchParams({
      per_page: String(perPage.value),
      page: String(page.value),
    })
    if (sort.value === 'name_asc') {
      params.set('sort', 'name')
      params.set('direction', 'asc')
    } else if (sort.value === 'name_desc') {
      params.set('sort', 'name')
      params.set('direction', 'desc')
    } else if (sort.value === 'votes') {
      params.set('sort', 'votes')
      params.set('direction', 'desc')
    } else {
      params.set('sort', 'rating')
      params.set('direction', 'desc')
    }

    const q = searchQuery.value.trim()
    if (q) params.set('search', q)
    if (brand.value) params.set('brand', brand.value)
    if (gender.value) params.set('gender', gender.value)
    if (letter.value) params.set('letter', letter.value)
    if (season.value) params.set('season', season.value)
    if (ratingMin.value) params.set('rating_min', ratingMin.value)
    if (selectedNotes.value.length) params.set('notes', selectedNotes.value.join(','))

    const res = await api.get(`/perfume?${params.toString()}`)
    perfumes.value = res.data ?? []
    total.value = res.total ?? 0
    lastPage.value = res.last_page ?? 1
  } finally {
    loading.value = false
  }
}

function goToPage(n: number) {
  page.value = Math.min(Math.max(n, 1), lastPage.value)
  fetchPage()
}

// Any filter change snaps back to page 1 and refetches.
function resetAndFetch() {
  page.value = 1
  fetchPage()
}

// perPage is intentionally NOT watched here — it changes when isLargeScreen is
// first set in onMounted, which would fire a fetch on top of the explicit one.
// Viewport changes refetch via the matchMedia listener below.
watch(
  [brand, gender, letter, season, ratingMin, sort, selectedNotes],
  resetAndFetch,
  { deep: true },
)

// Search is debounced so each keystroke doesn't hit the API.
let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(searchQuery, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(resetAndFetch, 300)
})

onMounted(async () => {
  // Track viewport so per-page count adapts to mobile vs desktop. lg = 1024px.
  // Setting isLargeScreen here updates perPage before the first fetchPage()
  // below; later viewport changes refetch explicitly.
  if (typeof window !== 'undefined') {
    const mq = window.matchMedia('(min-width: 1024px)')
    isLargeScreen.value = mq.matches
    mq.addEventListener('change', (e) => {
      isLargeScreen.value = e.matches
      resetAndFetch()
    })
  }

  try {
    const facets = await api.get('/perfume-facets')
    brandOptions.value = facets.brands ?? []
    noteOptions.value = facets.notes ?? []
  } catch (e) {
    console.warn('[perfume] facets load failed', e)
  }

  fetchPage()
})
</script>
