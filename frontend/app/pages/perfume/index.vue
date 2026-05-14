<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <header class="border-b border-rule pb-8">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          The catalog
        </p>
        <h1 class="mt-3 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
          Discover
        </h1>
        <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-xl">
          Browse the catalogue, search by house or name, and add what's already on your shelf.
        </p>
      </header>

      <!-- Search -->
      <div class="mt-10">
        <label for="catalog-search" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
          Search
        </label>
        <input
          id="catalog-search"
          v-model="searchQuery"
          type="text"
          autocomplete="off"
          placeholder="Brand or name…"
          class="w-full lg:w-96 bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
        >
      </div>

      <!-- Result count -->
      <p class="mt-8 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
        {{ total.toLocaleString() }} {{ total === 1 ? 'fragrance' : 'fragrances' }}
        <template v-if="lastPage > 1">
          <span class="text-accent-deep mx-1">·</span>page {{ page }} of {{ lastPage.toLocaleString() }}
        </template>
      </p>

      <!-- Loading -->
      <p v-if="loading" class="mt-16 text-center font-display italic text-ink-soft">
        Drawing from the cabinet…
      </p>

      <!-- Empty -->
      <p v-else-if="perfumes.length === 0" class="mt-16 text-center font-display italic text-ink-soft">
        Nothing matches that. Try a different house or name.
      </p>

      <!-- Grid: 2 cols × 4 rows = 8 on mobile, 4 cols × 4 rows = 16 on desktop -->
      <ul v-else class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4">
        <li v-for="p in perfumes" :key="p.id">
          <NuxtLink
            :to="`/perfume/${p.id}`"
            class="group block bg-paper border border-rule p-5 hover:bg-paper-deep transition-colors duration-200"
          >
            <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center group-hover:bg-paper transition-colors duration-200">
              <BottleIcon :size="64" />
            </div>
            <p class="mt-4 font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute">
              {{ p.brand }}
            </p>
            <h3 class="mt-1 font-display text-[18px] text-ink leading-tight min-h-[2.5em] line-clamp-2">
              {{ p.name }}
            </h3>
            <p
              v-if="p.main_accord"
              class="mt-3 font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute leading-snug"
            >
              {{ formatAccord(p.main_accord) }}
            </p>
            <p v-if="p.year" class="mt-2 font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute">
              {{ p.year }}
            </p>
            <p class="mt-5 font-display italic text-[12px] text-ink hover:text-accent-deep border-b border-accent inline-block pb-px">
              View details →
            </p>
          </NuxtLink>
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
        <span class="text-ink-mute">
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
definePageMeta({ middleware: 'auth' })

type Perfume = {
  id: number
  brand: string
  name: string
  year?: number | null
  main_accord?: string | null
}

const api = useApi()

const perfumes = ref<Perfume[]>([])
const total = ref(0)
const lastPage = ref(1)
const loading = ref(true)

const searchQuery = ref('')
const page = ref(1)

// Responsive page size: 8 on mobile (2 cols × 4 rows), 16 on desktop (4 cols × 4 rows)
const isLargeScreen = ref(false)
const perPage = computed(() => (isLargeScreen.value ? 16 : 8))

// The catalog is 24k rows — paginate + search server-side via /api/perfume,
// which is sorted highest-rated first so page 1 is the strongest fragrances.
async function fetchPage() {
  loading.value = true
  try {
    const params = new URLSearchParams({
      sort: 'rating',
      direction: 'desc',
      per_page: String(perPage.value),
      page: String(page.value),
    })
    const q = searchQuery.value.trim()
    if (q) params.set('search', q)

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

// Debounced search — snap back to page 1 and refetch.
let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(searchQuery, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    page.value = 1
    fetchPage()
  }, 300)
})

// Viewport change alters perPage (mobile ↔ desktop) — snap to page 1 and refetch.
watch(perPage, () => {
  page.value = 1
  fetchPage()
})

const formatAccord = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

onMounted(() => {
  // Track viewport so per-page count adapts to mobile vs desktop. lg = 1024px.
  if (typeof window !== 'undefined') {
    const mq = window.matchMedia('(min-width: 1024px)')
    isLargeScreen.value = mq.matches
    mq.addEventListener('change', (e) => {
      isLargeScreen.value = e.matches
    })
  }

  fetchPage()
})
</script>
