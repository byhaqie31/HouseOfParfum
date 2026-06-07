<script setup lang="ts">
import {
  scentWorld, bottleSeed, FAMILIES, FAMILY_ORDER, type ScentFamilyKey,
} from '~/utils/scent'
import { familyOfTheHour } from '~/utils/wear'
import type { RegistryPerfume, CuratePayload } from '~/types/admin'

definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()
const route = useRoute()
const router = useRouter()
const canvas = useCanvasStore()
const isDark = computed(() => canvas.isDark)
const accent = computed(() => scentWorld(familyOfTheHour(), 0.5, isDark.value).accent)

type FilterKey = 'all' | 'incomplete' | 'uncategorised'

const perfumes = ref<RegistryPerfume[]>([])
const meta = ref<{ from: number; to: number; total: number; prev_page_url: string | null; next_page_url: string | null } | null>(null)
const incompleteTotal = ref(0)
const loading = ref(true)
const page = ref(1)

const search = ref('')
const filter = ref<FilterKey>((route.query.filter as FilterKey) || 'all')
const famFilter = ref<ScentFamilyKey | 'all'>('all')

const editing = ref<RegistryPerfume | null>(null)
const saving = ref(false)

const filterChips: { k: FilterKey; l: string }[] = [
  { k: 'all', l: 'All' },
  { k: 'incomplete', l: 'Incomplete' },
  { k: 'uncategorised', l: 'No family' },
]

// ── Completeness (client-side, mirrors the backend rule) ─────────────────────
function completeness(p: RegistryPerfume) {
  const missing = {
    family: !p.family,
    notes: !(p.notes?.top?.length || p.notes?.heart?.length || p.notes?.base?.length),
    image: !p.image,
    history: !p.history,
  }
  const done = 4 - Object.values(missing).filter(Boolean).length
  return { missing, pct: Math.round((done / 4) * 100) }
}
function worldOf(p: RegistryPerfume) {
  return p.family ? scentWorld(p.family, bottleSeed(String(p.id), p.name), isDark.value) : null
}

// ── Loading ──────────────────────────────────────────────────────────────────
async function load(p = 1) {
  loading.value = true
  page.value = p
  const params = new URLSearchParams({ page: String(p), filter: filter.value })
  if (search.value) params.set('search', search.value)
  if (famFilter.value !== 'all') params.set('family', famFilter.value)
  const res = await api.get(`/admin/perfumes?${params.toString()}`)
  perfumes.value = res.data
  meta.value = res
  incompleteTotal.value = res.incomplete_total ?? 0
  loading.value = false
}

let debounce: ReturnType<typeof setTimeout> | null = null
watch(search, () => {
  if (debounce) clearTimeout(debounce)
  debounce = setTimeout(() => load(1), 400)
})
watch([filter, famFilter], () => load(1))

onMounted(async () => {
  await load(1)
  // Deep-link from the dashboard: open a specific perfume's drawer.
  const focus = route.query.focus
  if (focus) {
    try { editing.value = await api.get(`/admin/perfumes/${focus}`) }
    catch { /* no-op if it was removed */ }
    router.replace({ query: { ...route.query, focus: undefined } })
  }
})

// ── Save ─────────────────────────────────────────────────────────────────────
async function onSave(payload: CuratePayload) {
  saving.value = true
  try {
    const updated: RegistryPerfume = await api.patch(`/admin/perfumes/${payload.id}`, {
      family: payload.family,
      notes: payload.notes,
      image: payload.image,
      history: payload.history,
    })
    const idx = perfumes.value.findIndex((p) => p.id === payload.id)
    if (idx !== -1) perfumes.value[idx] = updated
    editing.value = null
    // Keep the header badge honest after a save.
    await load(page.value)
  }
  finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <AdminPageHeader title="Perfume registry" sub="Curate the source of truth behind every colour. Daftar wangian.">
      <template #actions>
        <div class="text-right">
          <div class="fd" :style="{ fontSize: '26px', lineHeight: 1, color: incompleteTotal ? accent : 'var(--color-ink)' }">{{ incompleteTotal }}</div>
          <div class="fm mt-1 uppercase" style="font-size: 9px; letter-spacing: 0.1em; color: var(--color-ink-mute);">need curating</div>
        </div>
      </template>
    </AdminPageHeader>

    <!-- Controls -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
      <div class="relative max-w-xs flex-1" style="min-width: 220px;">
        <Icon name="lucide:search" size="15" class="absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--color-ink-mute);" />
        <input
          v-model="search"
          type="text"
          placeholder="Search name or brand…"
          class="reg-input fb w-full"
          style="padding-left: 36px;"
        >
      </div>

      <div class="flex gap-2">
        <ScentChip
          v-for="c in filterChips"
          :key="c.k"
          :active="filter === c.k"
          :world="null"
          @click="filter = c.k"
        >{{ c.l }}</ScentChip>
      </div>

      <div class="ml-auto">
        <select v-model="famFilter" class="reg-input fb cursor-pointer">
          <option value="all">All families</option>
          <option v-for="k in FAMILY_ORDER" :key="k" :value="k">{{ FAMILIES[k].label }}</option>
        </select>
      </div>
    </div>

    <!-- Loading -->
    <div
      v-if="loading"
      class="rounded-card border px-6 py-16 text-center fm uppercase"
      style="border-color: var(--color-rule); background: var(--color-surface); font-size: 11px; letter-spacing: 0.14em; color: var(--color-ink-mute);"
    >Loading…</div>

    <template v-else>
      <!-- Desktop table -->
      <div class="hidden overflow-hidden rounded-card border md:block" style="border-color: var(--color-rule); background: var(--color-surface);">
        <table class="w-full border-collapse">
          <thead>
            <tr style="border-bottom: 1px solid var(--color-rule);">
              <th class="reg-th" style="width: 30%;">Perfume</th>
              <th class="reg-th">Family</th>
              <th class="reg-th">Year</th>
              <th class="reg-th">Rating</th>
              <th class="reg-th">Wears</th>
              <th class="reg-th" style="text-align: center;">Fields</th>
              <th class="reg-th" />
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(p, i) in perfumes"
              :key="p.id"
              class="reg-row cursor-pointer"
              :style="{ borderTop: i ? '1px solid var(--color-rule)' : 'none' }"
              @click="editing = p"
            >
              <td class="px-4 py-3.5">
                <div class="flex items-center gap-3">
                  <ScentOrb v-if="worldOf(p)" :world="worldOf(p)!" :size="15" />
                  <span v-else class="shrink-0 rounded-full" title="No family" style="width: 15px; height: 15px; border: 1.5px dashed var(--color-ink-mute);" />
                  <div class="min-w-0">
                    <div class="fd truncate" style="font-size: 16px; line-height: 1.15; color: var(--color-ink);">{{ p.name }}</div>
                    <div class="fb truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ p.brand }}</div>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3.5">
                <span v-if="p.family" class="fb" style="font-size: 12.5px; color: var(--color-ink-soft);">{{ FAMILIES[p.family].label }}</span>
                <span
                  v-else
                  class="fm uppercase"
                  style="font-size: 9.5px; letter-spacing: 0.08em; color: oklch(0.6 0.15 25); background: oklch(0.6 0.15 25 / 0.1); padding: 4px 9px; border-radius: 999px;"
                >Unset</span>
              </td>
              <td class="reg-td">{{ p.year ?? '—' }}</td>
              <td class="reg-td">{{ p.rating != null ? p.rating : '—' }}</td>
              <td class="reg-td" style="color: var(--color-ink);">{{ p.wears.toLocaleString() }}</td>
              <td class="px-4 py-3.5">
                <div class="flex justify-center">
                  <AdminCompletenessPips :missing="completeness(p).missing" :accent="accent" />
                </div>
              </td>
              <td class="px-4 py-3.5 text-right">
                <span
                  class="fm uppercase"
                  :style="{ fontSize: '10px', letterSpacing: '0.1em', color: completeness(p).pct === 100 ? 'var(--color-ink-mute)' : accent }"
                >{{ completeness(p).pct === 100 ? 'Edit' : 'Curate' }} →</span>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!perfumes.length" class="fb px-10 py-12 text-center italic" style="font-size: 14px; color: var(--color-ink-mute);">Nothing matches. Tiada padanan.</div>
      </div>

      <!-- Mobile cards -->
      <div class="flex flex-col gap-3 md:hidden">
        <button
          v-for="p in perfumes"
          :key="p.id"
          type="button"
          class="rounded-card border p-4 text-left"
          style="border-color: var(--color-rule); background: var(--color-surface);"
          @click="editing = p"
        >
          <div class="flex items-center gap-3">
            <ScentOrb v-if="worldOf(p)" :world="worldOf(p)!" :size="15" />
            <span v-else class="shrink-0 rounded-full" style="width: 15px; height: 15px; border: 1.5px dashed var(--color-ink-mute);" />
            <div class="min-w-0 flex-1">
              <div class="fd truncate" style="font-size: 16px; line-height: 1.15; color: var(--color-ink);">{{ p.name }}</div>
              <div class="fb truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ p.brand }}</div>
            </div>
            <AdminCompletenessPips :missing="completeness(p).missing" :accent="accent" />
          </div>
          <div class="mt-3 flex items-center gap-4 fm" style="font-size: 11px; color: var(--color-ink-mute);">
            <span>{{ p.family ? FAMILIES[p.family].label : 'Unset' }}</span>
            <span>{{ p.year ?? '—' }}</span>
            <span>{{ p.rating != null ? `${p.rating} ★` : 'No rating' }}</span>
            <span class="ml-auto" :style="{ color: completeness(p).pct === 100 ? 'var(--color-ink-mute)' : accent }">
              {{ completeness(p).pct === 100 ? 'Edit' : 'Curate' }} →
            </span>
          </div>
        </button>
        <div v-if="!perfumes.length" class="fb px-6 py-12 text-center italic" style="font-size: 14px; color: var(--color-ink-mute);">Nothing matches. Tiada padanan.</div>
      </div>

      <!-- Pagination -->
      <div v-if="meta && perfumes.length" class="mt-5 flex items-center justify-between">
        <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">{{ meta.from }}–{{ meta.to }} of {{ meta.total }}</p>
        <div class="flex items-center gap-4">
          <button
            type="button"
            class="fm uppercase disabled:opacity-30"
            style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-soft);"
            :disabled="!meta.prev_page_url"
            @click="load(page - 1)"
          >← Prev</button>
          <button
            type="button"
            class="fm uppercase disabled:opacity-30"
            style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-soft);"
            :disabled="!meta.next_page_url"
            @click="load(page + 1)"
          >Next →</button>
        </div>
      </div>
    </template>

    <!-- Curate drawer -->
    <AdminCurateDrawer :perfume="editing" :saving="saving" @close="editing = null" @save="onSave" />
  </div>
</template>

<style scoped>
.reg-input {
  background: var(--color-surface);
  border: 1px solid var(--color-rule);
  border-radius: 11px;
  padding: 10px 14px;
  font-size: 13px;
  color: var(--color-ink);
  outline: none;
}
.reg-input::placeholder { color: var(--color-ink-mute); }
.reg-input:focus { border-color: var(--color-accent); }

.reg-th {
  padding: 11px 16px;
  text-align: left;
  font-family: var(--font-data);
  font-weight: 700;
  font-size: 9.5px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--color-ink-mute);
}
.reg-td {
  padding: 14px 16px;
  font-family: var(--font-data);
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  font-size: 12.5px;
  color: var(--color-ink-mute);
}
.reg-row { transition: background-color 0.12s; }
.reg-row:hover { background: var(--color-surface-2); }
</style>
