<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-3xl mx-auto">
      <!-- Heading -->
      <h1 class="font-display text-4xl sm:text-5xl text-ink tracking-[-0.005em] leading-[1.1]">
        A new bottle <em class="text-ink">in the library.</em>
      </h1>
      <p class="mt-2 font-display italic text-[15px] text-ink-soft">
        <template v-if="form.catalog_id">
          From the catalog — adjust the details below.
        </template>
        <template v-else>
          Search the catalog, browse the full list, or enter freely.
        </template>
      </p>
      <div class="mt-3 w-9 h-px bg-accent" />

      <!-- Search -->
      <div class="mt-10 relative">
        <div class="flex items-baseline justify-between mb-2">
          <label for="search" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
            Search the catalog
          </label>
          <NuxtLink
            to="/perfume"
            class="font-display italic text-[12px] text-ink hover:text-accent-deep pb-px border-b border-accent transition-colors"
          >
            Browse the catalog &rarr;
          </NuxtLink>
        </div>
        <input
          id="search"
          v-model="searchQuery"
          type="text"
          autocomplete="off"
          placeholder="Search by brand or name…"
          class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
          @focus="searchFocused = true"
        >

        <!-- Autocomplete dropdown -->
        <div
          v-if="showAutocomplete"
          class="mt-1.5 bg-paper border border-rule max-h-65 overflow-y-auto"
        >
          <button
            v-for="match in matches"
            :key="match.id"
            type="button"
            class="w-full flex items-center gap-3 px-3.5 py-2.5 text-left border-b border-rule last:border-b-0 hover:bg-paper-deep transition-colors"
            @click="pickFromCatalog(match)"
          >
            <div class="w-7 h-9 bg-paper-deep border border-rule flex items-center justify-center shrink-0">
              <BottleIcon :size="16" />
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-mono text-[8px] uppercase tracking-[0.14em] text-ink-mute">
                {{ match.brand }}
              </p>
              <p class="font-display italic text-[14px] text-ink truncate">
                {{ match.name }}
              </p>
            </div>
          </button>
        </div>
      </div>

      <!-- Divider -->
      <div class="mt-10 flex items-center gap-4">
        <div class="flex-1 h-px bg-rule" />
        <span class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute shrink-0">or enter manually</span>
        <div class="flex-1 h-px bg-rule" />
      </div>

      <!-- Form -->
      <div class="mt-8 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label for="brand" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2">
              Brand
            </label>
            <input
              id="brand"
              v-model="form.brand"
              type="text"
              placeholder="e.g. Chanel"
              class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
            >
          </div>
          <div>
            <label for="name" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2">
              Name
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              placeholder="e.g. No. 5"
              class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
            >
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label for="size" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2">
              Size (ml)
            </label>
            <input
              id="size"
              v-model="form.size"
              type="text"
              inputmode="numeric"
              placeholder="e.g. 50"
              class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
            >
          </div>
          <div>
            <label for="acquired" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2">
              Acquired
            </label>
            <input
              id="acquired"
              v-model="form.acquired"
              type="text"
              :placeholder="`e.g. ${defaultAcquired}`"
              class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
            >
          </div>
        </div>

        <div>
          <label for="notes" class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2">
            Personal notes
          </label>
          <input
            id="notes"
            v-model="form.notes"
            type="text"
            placeholder="e.g. Smells like a hotel lobby in Kyoto…"
            class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
          >
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-8 pt-5 border-t border-rule flex items-center justify-between">
        <button
          type="button"
          class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          @click="cancel"
        >
          Cancel
        </button>
        <button
          type="button"
          :disabled="submitting || !canSubmit"
          class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-7 py-3 hover:bg-ink-soft transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
          @click="submit"
        >
          {{ submitting ? 'Adding…' : 'Add to wardrobe' }}
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
  size?: number | null
}

const api = useApi()
const router = useRouter()
const route = useRoute()
const wardrobe = useWardrobeStore()
const toast = useToast()

const searchQuery = ref('')
const searchFocused = ref(false)
const matches = ref<Perfume[]>([])

const form = reactive({
  brand: '',
  name: '',
  size: '',
  acquired: '',
  notes: '',
  catalog_id: null as number | null,
})

const submitting = ref(false)

const defaultAcquired = computed(() => {
  const now = new Date()
  return now.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' })
})

const showAutocomplete = computed(
  () => searchFocused.value && matches.value.length > 0,
)

const canSubmit = computed(
  () => form.brand.trim().length > 0 && form.name.trim().length > 0,
)

let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(searchQuery, (q) => {
  clearTimeout(searchTimer)
  const term = q.trim()
  if (term.length < 2) {
    matches.value = []
    return
  }
  searchTimer = setTimeout(async () => {
    try {
      const res = await api.get(`/perfume?search=${encodeURIComponent(term)}&per_page=8`)
      matches.value = res.data ?? []
    } catch {
      matches.value = []
    }
  }, 300)
})

const pickFromCatalog = (perfume: Perfume) => {
  form.brand = perfume.brand
  form.name = perfume.name
  if (perfume.size != null) form.size = String(perfume.size)
  form.catalog_id = perfume.id
  searchQuery.value = ''
  matches.value = []
  searchFocused.value = false
}

const cancel = () => router.push('/wardrobe')

const submit = async () => {
  if (!canSubmit.value || submitting.value) return
  submitting.value = true
  try {
    await wardrobe.add({
      catalog_id: form.catalog_id,
      brand: form.brand.trim(),
      name: form.name.trim(),
      size: Number.parseInt(form.size, 10) || 0,
      acquired: form.acquired.trim() || defaultAcquired.value,
      notes: form.notes.trim(),
    })
    toast.success('Bottle added to your wardrobe.')
    await new Promise(r => setTimeout(r, 400))
    router.push('/wardrobe')
  } catch {
    toast.error('Could not add the bottle — please try again.')
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  const raw = route.query.catalog_id
  if (!raw) return
  const id = Number(Array.isArray(raw) ? raw[0] : raw)
  if (!Number.isFinite(id)) return
  try {
    const perfume = await api.get(`/perfume/${id}`)
    if (perfume?.id) pickFromCatalog(perfume)
  } catch {
    toast.error('Could not prefill from catalog.')
  }
})
</script>
