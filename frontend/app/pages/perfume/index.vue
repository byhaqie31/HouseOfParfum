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
          Browse what's on file, filter by house, and add what's already on your shelf.
        </p>
      </header>

      <!-- Filters row -->
      <div class="mt-10 flex flex-col lg:flex-row lg:items-start gap-6">
        <!-- House: full-width dropdown on mobile, chip row on desktop -->
        <div class="flex-1 min-w-0 order-2 lg:order-1">
          <label for="catalog-house" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
            House
          </label>

          <!-- Mobile dropdown (custom editorial styling to match the site) -->
          <EditorialSelect
            v-model="selectedBrandCode"
            :options="brandOptions"
            class="lg:hidden"
          />

          <!-- Desktop chips -->
          <div class="hidden lg:flex flex-wrap gap-2">
            <button
              type="button"
              class="px-4 py-2 font-mono text-[10px] uppercase tracking-[0.14em] border transition-colors"
              :class="selectedBrandCode === 'all'
                ? 'bg-accent-soft border-accent text-accent-deep font-medium'
                : 'bg-paper-deep border-rule text-ink hover:border-ink-soft'"
              @click="selectedBrandCode = 'all'"
            >
              All
              <span class="font-mono text-[9px] text-ink-mute ml-1">{{ perfumes.length }}</span>
            </button>
            <button
              v-for="b in brands"
              :key="b.code"
              type="button"
              class="px-4 py-2 font-mono text-[10px] uppercase tracking-[0.14em] border transition-colors"
              :class="selectedBrandCode === b.code
                ? 'bg-accent-soft border-accent text-accent-deep font-medium'
                : 'bg-paper-deep border-rule text-ink hover:border-ink-soft'"
              @click="selectedBrandCode = b.code"
            >
              {{ b.name }}
              <span class="font-mono text-[9px] text-ink-mute ml-1">
                {{ countByBrand[b.code] ?? 0 }}
              </span>
            </button>
          </div>
        </div>

        <!-- Search -->
        <div class="lg:w-72 order-1 lg:order-2">
          <label for="catalog-search" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
            Search
          </label>
          <input
            id="catalog-search"
            v-model="searchQuery"
            type="text"
            autocomplete="off"
            placeholder="Brand or name…"
            class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
          >
        </div>
      </div>

      <!-- Result count + active filter chip -->
      <p class="mt-8 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
        Showing {{ filtered.length }}
        <template v-if="selectedBrandCode !== 'all' || searchQuery">
          of {{ perfumes.length }}
        </template>
      </p>

      <!-- Loading -->
      <p v-if="loading" class="mt-16 text-center font-display italic text-ink-soft">
        Drawing from the cabinet…
      </p>

      <!-- Empty -->
      <p v-else-if="filtered.length === 0" class="mt-16 text-center font-display italic text-ink-soft">
        Nothing matches that. Try a different house or clear the search.
      </p>

      <!-- Grid: 2 cols × 4 rows = 8 on mobile, 4 cols × 3 rows = 12 on desktop -->
      <ul v-else class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4">
        <li v-for="p in paged" :key="p.id">
          <NuxtLink
            :to="`/perfume/${p.id}`"
            class="group block bg-paper border border-rule p-5 hover:bg-paper-deep transition-colors duration-200"
          >
            <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center group-hover:bg-paper transition-colors duration-200">
              <BottleIcon :size="64" />
            </div>
            <p class="mt-4 font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute">
              {{ brandByCode[p.brand]?.name || p.brand }}
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
            <p class="mt-2 font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute">
              {{ p.size }} ml
              <template v-if="p.year">
                <span class="text-accent-deep mx-1">·</span>{{ p.year }}
              </template>
            </p>
            <p class="mt-5 font-display italic text-[12px] text-ink hover:text-accent-deep border-b border-accent inline-block pb-px">
              View details →
            </p>
          </NuxtLink>
        </li>
      </ul>

      <!-- Pagination -->
      <div
        v-if="!loading && totalPages > 1"
        class="mt-12 flex items-center justify-center gap-6 font-mono text-[10px] uppercase tracking-[0.24em]"
      >
        <button
          type="button"
          class="text-ink hover:text-accent-deep transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
          :disabled="pageIndex === 0"
          @click="pageIndex--"
        >
          &larr; Previous
        </button>
        <span class="text-ink-mute">
          Page {{ pageIndex + 1 }} / {{ totalPages }}
        </span>
        <button
          type="button"
          class="text-ink hover:text-accent-deep transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
          :disabled="pageIndex >= totalPages - 1"
          @click="pageIndex++"
        >
          Next &rarr;
        </button>
      </div>
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

const api = useApi()

const perfumes = ref<Perfume[]>([])
const brands = ref<Brand[]>([])
const loading = ref(true)

const selectedBrandCode = ref<'all' | string>('all')
const searchQuery = ref('')

// Responsive page size: 8 on mobile (2 cols × 4 rows), 12 on desktop (4 cols × 3 rows)
const isLargeScreen = ref(false)
const perPage = computed(() => (isLargeScreen.value ? 16 : 8))
const pageIndex = ref(0)

const brandByCode = computed(() =>
  Object.fromEntries(brands.value.map((b: Brand) => [b.code, b])),
)

const countByBrand = computed(() => {
  const acc: Record<string, number> = {}
  for (const p of perfumes.value) {
    acc[p.brand] = (acc[p.brand] ?? 0) + 1
  }
  return acc
})

// Shape brands for the mobile EditorialSelect (label + count + "all" sentinel)
const brandOptions = computed(() => [
  { value: 'all', label: 'All houses', count: perfumes.value.length },
  ...brands.value.map((b: Brand) => ({
    value: b.code,
    label: b.name,
    count: countByBrand.value[b.code] ?? 0,
  })),
])

const filtered = computed(() => {
  let list = perfumes.value
  if (selectedBrandCode.value !== 'all') {
    list = list.filter(p => p.brand === selectedBrandCode.value)
  }
  const q = searchQuery.value.trim().toLowerCase()
  if (q.length >= 2) {
    list = list.filter((p) => {
      const brandName = brandByCode.value[p.brand]?.name?.toLowerCase() ?? ''
      return p.name.toLowerCase().includes(q) || brandName.includes(q)
    })
  }
  return list
})

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage.value)))

const paged = computed(() => {
  const start = pageIndex.value * perPage.value
  return filtered.value.slice(start, start + perPage.value)
})

// Snap back to page 1 whenever the filter set changes.
watch([selectedBrandCode, searchQuery], () => {
  pageIndex.value = 0
})

// Keep the current page valid when perPage shrinks/grows (e.g. user resizes
// from desktop to mobile mid-browse and the existing page index would be empty).
watch(totalPages, (n) => {
  if (pageIndex.value >= n) pageIndex.value = Math.max(0, n - 1)
})

const formatAccord = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

onMounted(async () => {
  // Track viewport so per-page count adapts to mobile vs desktop. lg = 1024px.
  if (typeof window !== 'undefined') {
    const mq = window.matchMedia('(min-width: 1024px)')
    isLargeScreen.value = mq.matches
    mq.addEventListener('change', (e) => {
      isLargeScreen.value = e.matches
    })
  }

  try {
    const [perfumeData, brandData] = await Promise.all([
      api.get('/perfume'),
      api.get('/brand'),
    ])
    perfumes.value = perfumeData
    brands.value = brandData
  } finally {
    loading.value = false
  }
})
</script>
