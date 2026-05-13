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
          <span class="font-mono text-[14px] uppercase tracking-[0.16em] text-ink-mute align-middle ml-3">
            {{ String(perfumes.length).padStart(2, '0') }}
          </span>
        </h1>
        <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-xl">
          Browse what's on file, filter by house, and add what's already on your shelf.
        </p>
      </header>

      <!-- Filters row -->
      <div class="mt-10 flex flex-col lg:flex-row lg:items-start gap-6">
        <!-- Brand chips -->
        <div class="flex-1 min-w-0 order-2 lg:order-1">
          <p class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
            House
          </p>
          <div class="flex flex-wrap gap-2">
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

      <!-- Grid -->
      <ul v-else class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <li v-for="p in filtered" :key="p.id">
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
            <h3 class="mt-1 font-display text-[18px] text-ink leading-tight">
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

const formatAccord = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

onMounted(async () => {
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
