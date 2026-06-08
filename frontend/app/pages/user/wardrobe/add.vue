<script setup lang="ts">
import { FAMILY_ORDER, scentWorld, type ScentFamilyKey } from '~/utils/scent'
import { deriveFamily } from '~/utils/scentFamily'

definePageMeta({ layout: 'app', middleware: 'auth' })
useHead({ title: 'Add a bottle' })

type Perfume = {
  id: number
  brand: string
  name: string
  size?: number | null
  main_accord?: string | null
  top_notes?: string | null
  middle_notes?: string | null
  base_notes?: string | null
}

const api = useApi()
const router = useRouter()
const route = useRoute()
const wardrobe = useWardrobeStore()
const toast = useToast()
const { worldFor, isDark } = useScentWorld()

const searchQuery = ref('')
const searchFocused = ref(false)
const matches = ref<Perfume[]>([])

const form = reactive({
  brand: '',
  name: '',
  family: null as ScentFamilyKey | null,
  concentration: '',
  size: '',
  acquired: '',
  notes: '',
  catalog_id: null as number | null,
  notes_top: null as string[] | null,
  notes_heart: null as string[] | null,
  notes_base: null as string[] | null,
})

const submitting = ref(false)

const previewWorld = worldFor(() => form.family ?? 'woody', () => form.brand + form.name)

const defaultAcquired = computed(() =>
  new Date().toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }),
)
const showAutocomplete = computed(() => searchFocused.value && matches.value.length > 0)
const canSubmit = computed(() =>
  form.brand.trim().length > 0
  && form.name.trim().length > 0
  && !!form.family
  && Number.parseInt(form.size, 10) > 0,
)

function familyWorld(key: ScentFamilyKey) {
  return scentWorld(key, 0.5, isDark.value)
}
function splitNotes(s?: string | null): string[] | null {
  if (!s) return null
  const arr = s.split(',').map((x) => x.trim()).filter(Boolean)
  return arr.length ? arr : null
}

let searchTimer: ReturnType<typeof setTimeout> | undefined
watch(searchQuery, (q) => {
  clearTimeout(searchTimer)
  const term = q.trim()
  if (term.length < 2) { matches.value = []; return }
  searchTimer = setTimeout(async () => {
    try {
      const res = await api.get(`/perfume?search=${encodeURIComponent(term)}&per_page=8`)
      matches.value = res.data ?? []
    } catch { matches.value = [] }
  }, 300)
})

// Pull full catalog detail so we can derive the family + notes pyramid.
async function applyCatalog(p: Perfume) {
  form.brand = p.brand
  form.name = p.name
  form.catalog_id = p.id
  if (p.size != null) form.size = String(p.size)
  try {
    const detail = await api.get(`/perfume/${p.id}`) as Perfume
    const accords = (detail.main_accord ?? '').split(',').map((x) => x.trim()).filter(Boolean)
    if (accords.length) form.family = deriveFamily(accords)
    form.notes_top = splitNotes(detail.top_notes)
    form.notes_heart = splitNotes(detail.middle_notes)
    form.notes_base = splitNotes(detail.base_notes)
  } catch { /* keep manual fields */ }
}

function pickFromCatalog(p: Perfume) {
  applyCatalog(p)
  searchQuery.value = ''
  matches.value = []
  searchFocused.value = false
}

function cancel() { router.push('/user/wardrobe') }

async function submit() {
  if (!canSubmit.value || submitting.value) return
  submitting.value = true
  try {
    await wardrobe.add({
      catalog_id: form.catalog_id,
      brand: form.brand.trim(),
      name: form.name.trim(),
      family: form.family,
      concentration: form.concentration.trim() || null,
      notes_top: form.notes_top,
      notes_heart: form.notes_heart,
      notes_base: form.notes_base,
      size: Number.parseInt(form.size, 10) || 0,
      acquired: form.acquired.trim(),
      notes: form.notes.trim(),
    })
    toast.success('Bottle added to your shelf.')
    await new Promise((r) => setTimeout(r, 350))
    router.push('/user/wardrobe')
  } catch {
    toast.error('Could not add the bottle. Try again.')
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
    const p = await api.get(`/perfume/${id}`)
    if (p?.id) await applyCatalog(p)
  } catch { /* ignore */ }
})

// Standard fragrance concentrations, strongest → lightest.
const CONCENTRATIONS = ['Extrait de Parfum', 'Parfum', 'Elixir', 'Eau de Parfum', 'Eau de Toilette', 'Eau de Cologne', 'Eau Fraîche']

const inputClass =
  'w-full rounded-field border px-3.5 py-2.5 fb focus:outline-none'
const inputStyle =
  'border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink); font-size: 14px;'
</script>

<template>
  <div class="mx-auto max-w-2xl">
    <header class="mb-6">
      <div class="kicker">Your vanity</div>
      <h1 class="fd mt-1" style="font-size: clamp(28px, 5vw, 38px); line-height: 1.05;">Add a bottle</h1>
      <p class="fb mt-2 italic" style="font-size: 14px; color: var(--color-ink-soft);">Pick a scent family and the bottle finds its colour.</p>
    </header>

    <!-- live preview -->
    <div
      class="mb-8 flex items-center gap-5 rounded-hero p-6"
      :style="{ background: previewWorld.bloom, color: previewWorld.onGrad }"
    >
      <ScentFlacon :world="previewWorld" :size="64" />
      <div class="min-w-0">
        <div class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; opacity: 0.8;">{{ form.brand || 'House' }}</div>
        <div class="fd leading-tight" style="font-size: 26px;">{{ form.name || 'Your bottle' }}</div>
        <div class="fm mt-1 uppercase" style="font-size: 10px; letter-spacing: 0.16em; opacity: 0.85;">{{ previewWorld.family.label }}</div>
      </div>
    </div>

    <!-- catalog search -->
    <div class="relative mb-6">
      <div class="mb-2 flex items-baseline justify-between">
        <label for="search" class="fb" style="font-size: 13px; color: var(--color-ink-soft);">Search the catalogue</label>
        <NuxtLink to="/user/perfume" class="fb italic" style="font-size: 12px; color: var(--color-ink);">Browse all →</NuxtLink>
      </div>
      <input
        id="search"
        v-model="searchQuery"
        type="text"
        autocomplete="off"
        placeholder="Search by house or name…"
        :class="inputClass"
        :style="inputStyle"
        @focus="searchFocused = true"
      >
      <div
        v-if="showAutocomplete"
        class="absolute z-10 mt-1.5 max-h-64 w-full overflow-y-auto rounded-field border"
        style="border-color: var(--color-rule); background: var(--color-surface);"
      >
        <button
          v-for="m in matches"
          :key="m.id"
          type="button"
          class="flex w-full items-center gap-3 border-b px-3.5 py-2.5 text-left last:border-b-0"
          style="border-color: var(--color-rule);"
          @click="pickFromCatalog(m)"
        >
          <div class="min-w-0">
            <p class="kicker">{{ m.brand }}</p>
            <p class="fb truncate" style="font-size: 14px; color: var(--color-ink);">{{ m.name }}</p>
          </div>
        </button>
      </div>
    </div>

    <!-- family chips (drive the colour) -->
    <div class="mb-6">
      <div class="mb-2 fb" style="font-size: 13px; color: var(--color-ink-soft);">Scent family <span :style="{ color: previewWorld.accent }">*</span></div>
      <div class="flex flex-wrap gap-2">
        <ScentChip
          v-for="key in FAMILY_ORDER"
          :key="key"
          :active="form.family === key"
          :world="familyWorld(key)"
          @click="form.family = key"
        >{{ familyWorld(key).family.label }}</ScentChip>
      </div>
    </div>

    <!-- fields -->
    <div class="space-y-4">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label for="brand" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">House *</label>
          <input id="brand" v-model="form.brand" type="text" placeholder="e.g. Maison Selat" :class="inputClass" :style="inputStyle">
        </div>
        <div>
          <label for="name" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">Name *</label>
          <input id="name" v-model="form.name" type="text" placeholder="e.g. Limau Pagi" :class="inputClass" :style="inputStyle">
        </div>
      </div>
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label for="size" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">Size (ml) *</label>
          <input id="size" v-model="form.size" type="text" inputmode="numeric" placeholder="e.g. 50" :class="inputClass" :style="inputStyle">
        </div>
        <div>
          <label for="conc" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">Concentration</label>
          <div class="relative">
            <select
              id="conc"
              v-model="form.concentration"
              :class="inputClass"
              :style="[inputStyle, {
                appearance: 'none',
                WebkitAppearance: 'none',
                paddingRight: '40px',
                cursor: 'pointer',
                color: form.concentration ? 'var(--color-ink)' : 'var(--color-ink-mute)',
              }]"
            >
              <option value="">Select concentration…</option>
              <option v-for="c in CONCENTRATIONS" :key="c" :value="c">{{ c }}</option>
            </select>
            <Icon
              name="lucide:chevron-down"
              size="16"
              class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2"
              style="color: var(--color-ink-mute);"
            />
          </div>
        </div>
      </div>
      <div>
        <label for="acquired" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">Acquired</label>
        <input id="acquired" v-model="form.acquired" type="text" :placeholder="`e.g. ${defaultAcquired}`" :class="inputClass" :style="inputStyle">
      </div>
      <div>
        <label for="notes" class="mb-1.5 block fb" style="font-size: 13px; color: var(--color-ink-soft);">A personal note</label>
        <input id="notes" v-model="form.notes" type="text" placeholder="e.g. The one she noticed first." :class="inputClass" :style="inputStyle">
      </div>
    </div>

    <!-- actions -->
    <div class="mt-7 flex items-center justify-between border-t pt-5" style="border-color: var(--color-rule);">
      <button type="button" class="fb italic" style="font-size: 14px; color: var(--color-ink-soft);" @click="cancel">Cancel</button>
      <button
        type="button"
        :disabled="submitting || !canSubmit"
        class="flex items-center gap-2 rounded-full px-6 py-3 disabled:opacity-40"
        :style="{ background: previewWorld.gradient, color: previewWorld.onGrad }"
        @click="submit"
      >
        <Icon name="lucide:plus" size="16" />
        <span class="fb" style="font-weight: 700;">{{ submitting ? 'Adding…' : 'Add to shelf' }}</span>
      </button>
    </div>
  </div>
</template>
