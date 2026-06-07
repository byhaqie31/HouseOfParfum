<script setup lang="ts">
import {
  scentWorld, hashSeed, FAMILIES, FAMILY_ORDER, type ScentFamilyKey,
} from '~/utils/scent'
import { familyOfTheHour } from '~/utils/wear'

definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()
const canvas = useCanvasStore()
const isDark = computed(() => canvas.isDark)
const houseWorld = computed(() => scentWorld(familyOfTheHour(), 0.5, isDark.value))

interface FamilyRow { key: ScentFamilyKey; catalogue: number; wears: number }
interface TopRow { name: string; brand: string; wears: number; family: ScentFamilyKey | null; perfume_id: number | null }
interface Dashboard {
  stats: { users: number; perfumes: number; wardrobe_items: number; journal_entries: number }
  growth: { users: number; perfumes: number; wardrobe_items: number; journal_entries: number }
  families: { rows: FamilyRow[]; uncategorised: number }
  wear_series: { date: string; value: number }[]
  top_logged: TopRow[]
  completeness: {
    overall_pct: number; fully_done: number; total: number
    missing: { family: number; notes: number; image: number; history: number }
  }
}

const data = ref<Dashboard | null>(null)
const loading = ref(true)

onMounted(async () => {
  try { data.value = await api.get('/admin/dashboard') }
  finally { loading.value = false }
})

// ── Stat tiles ──────────────────────────────────────────────────────────────
const tiles = computed(() => [
  { label: 'Members', my: 'ahli', value: data.value?.stats.users ?? null, growth: data.value?.growth.users },
  { label: 'Perfume registry', my: 'daftar wangian', value: data.value?.stats.perfumes ?? null, growth: data.value?.growth.perfumes },
  { label: 'Wardrobe items', my: 'koleksi', value: data.value?.stats.wardrobe_items ?? null, growth: data.value?.growth.wardrobe_items },
  { label: 'Wears logged', my: 'rekod pakaian', value: data.value?.stats.journal_entries ?? null, growth: data.value?.growth.journal_entries },
])

// ── Scent spectrum (family distribution) ─────────────────────────────────────
const metric = ref<'wears' | 'catalogue'>('wears')
const spectrum = computed(() => {
  const rows = data.value?.families.rows ?? []
  const max = Math.max(1, ...rows.map((r) => (metric.value === 'wears' ? r.wears : r.catalogue)))
  return rows.map((r) => {
    const val = metric.value === 'wears' ? r.wears : r.catalogue
    return {
      key: r.key,
      label: FAMILIES[r.key].label,
      world: scentWorld(r.key, 0.5, isDark.value),
      val,
      pct: Math.max(3, (val / max) * 100),
    }
  })
})

// ── Wears over time ──────────────────────────────────────────────────────────
const wearSeries = computed(() => data.value?.wear_series ?? [])
const wearMax = computed(() => Math.max(1, ...wearSeries.value.map((d) => d.value)))
const wearTotal = computed(() => wearSeries.value.reduce((s, d) => s + d.value, 0))
const dayOf = (iso: string) => new Date(iso + 'T00:00:00').getDate()

// ── Catalogue health ring ────────────────────────────────────────────────────
const RING_R = 52
const RING_C = 2 * Math.PI * RING_R
const ringOffset = computed(() => RING_C * (1 - (data.value?.completeness.overall_pct ?? 0) / 100))
const healthRows = computed(() => {
  const m = data.value?.completeness.missing
  return [
    { label: 'Scent family', n: m?.family ?? 0, drives: true },
    { label: 'Notes pyramid', n: m?.notes ?? 0, drives: true },
    { label: 'Image', n: m?.image ?? 0, drives: false },
    { label: 'History', n: m?.history ?? 0, drives: false },
  ]
})

// ── Most logged ──────────────────────────────────────────────────────────────
const topLogged = computed(() => (data.value?.top_logged ?? []).map((p, i) => ({
  ...p,
  rank: String(i + 1).padStart(2, '0'),
  world: p.family ? scentWorld(p.family, hashSeed(p.name + p.brand), isDark.value) : null,
})))

function openPerfume(p: TopRow) {
  navigateTo(p.perfume_id ? `/admin/perfumes?focus=${p.perfume_id}` : '/admin/perfumes')
}

const cardStyle = 'background: var(--color-surface); border-color: var(--color-rule);'
</script>

<template>
  <div>
    <AdminPageHeader title="Overview" sub="The house at a glance. Pandangan keseluruhan." />

    <!-- Stat tiles -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
      <AdminStatTile
        v-for="t in tiles"
        :key="t.label"
        :label="t.label"
        :my="t.my"
        :value="t.value"
        :growth="t.growth"
      />
    </div>

    <!-- Spectrum + wears trend -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
      <!-- Scent spectrum -->
      <section class="rounded-panel border p-[26px]" :style="cardStyle">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="fd" style="font-size: 22px; color: var(--color-ink);">Scent spectrum</h2>
            <p class="fb mt-0.5 italic" style="font-size: 12.5px; color: var(--color-ink-mute);">How the catalogue and your members lean. Kecenderungan keluarga wangian.</p>
          </div>
          <div class="flex shrink-0 gap-1.5 rounded-full p-1" style="background: var(--color-surface-2);">
            <button
              v-for="opt in [{ k: 'wears', l: 'By wears' }, { k: 'catalogue', l: 'By catalogue' }]"
              :key="opt.k"
              type="button"
              class="fm rounded-full uppercase"
              :style="{
                padding: '7px 13px', fontSize: '10px', letterSpacing: '0.08em',
                background: metric === opt.k ? 'var(--color-surface)' : 'transparent',
                color: metric === opt.k ? 'var(--color-ink)' : 'var(--color-ink-mute)',
                boxShadow: metric === opt.k ? '0 1px 4px -1px rgba(0,0,0,0.15)' : 'none',
              }"
              @click="metric = opt.k as 'wears' | 'catalogue'"
            >{{ opt.l }}</button>
          </div>
        </div>

        <div class="mt-5 flex flex-col gap-3.5">
          <div v-for="r in spectrum" :key="r.key" class="flex items-center gap-3.5">
            <div class="flex w-[130px] shrink-0 items-center gap-2.5">
              <ScentOrb :world="r.world" :size="13" />
              <span class="fb truncate" style="font-size: 13px; font-weight: 600; color: var(--color-ink);">{{ r.label }}</span>
            </div>
            <div class="relative h-[26px] flex-1 overflow-hidden rounded-full" style="background: var(--color-surface-2);">
              <div
                class="h-full rounded-full"
                :style="{ width: `${r.pct}%`, background: r.world.gradient, transition: 'width .4s cubic-bezier(.2,.8,.2,1)' }"
              />
            </div>
            <div class="fm w-[54px] text-right" style="font-size: 13px; color: var(--color-ink);">{{ r.val.toLocaleString() }}</div>
          </div>
        </div>

        <div
          v-if="(data?.families.uncategorised ?? 0) > 0"
          class="mt-4 flex items-center gap-2.5 border-t pt-4"
          style="border-color: var(--color-rule);"
        >
          <span class="shrink-0 rounded-full" style="width: 13px; height: 13px; border: 1.5px dashed var(--color-ink-mute);" />
          <span class="fb" style="font-size: 12.5px; color: var(--color-ink-soft);">
            <strong style="color: var(--color-ink);">{{ data?.families.uncategorised }} perfumes</strong>
            have no scent family yet, so they generate no colour. Belum ada keluarga.
          </span>
        </div>
      </section>

      <!-- Wears over time -->
      <section class="rounded-panel border p-[26px]" :style="cardStyle">
        <div class="flex items-start justify-between">
          <div>
            <h2 class="fd whitespace-nowrap" style="font-size: 22px; color: var(--color-ink);">Wears logged</h2>
            <p class="fb mt-0.5 italic" style="font-size: 12.5px; color: var(--color-ink-mute);">Last 14 days</p>
          </div>
          <div class="text-right">
            <div class="fd" style="font-size: 30px; line-height: 1; color: var(--color-ink);">{{ wearTotal.toLocaleString() }}</div>
            <div class="fm mt-1 uppercase" style="font-size: 9.5px; letter-spacing: 0.1em; color: var(--color-ink-mute);">total</div>
          </div>
        </div>
        <div class="mt-5 flex h-[140px] items-end gap-1.5">
          <div
            v-for="(d, i) in wearSeries"
            :key="d.date"
            class="flex h-full flex-1 flex-col items-center justify-end gap-2"
          >
            <div
              class="w-full"
              :title="`${d.date}: ${d.value}`"
              :style="{
                height: `${Math.max(6, (d.value / wearMax) * 100)}%`,
                borderRadius: '7px 7px 3px 3px',
                background: i === wearSeries.length - 1 ? houseWorld.gradient : 'var(--color-surface-2)',
                border: i === wearSeries.length - 1 ? 'none' : '1px solid var(--color-rule)',
              }"
            />
            <span class="fm" style="font-size: 8px; color: var(--color-ink-mute);">{{ i % 2 === 0 ? dayOf(d.date) : '' }}</span>
          </div>
        </div>
      </section>
    </div>

    <!-- Catalogue health + most logged -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
      <!-- Catalogue health -->
      <section class="rounded-panel border p-[26px]" :style="cardStyle">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="fd" style="font-size: 22px; color: var(--color-ink);">Catalogue health</h2>
            <p class="fb mt-0.5 italic" style="font-size: 12.5px; color: var(--color-ink-mute);">The colour system is only as good as this.</p>
          </div>
          <NuxtLink
            to="/admin/perfumes?filter=incomplete"
            class="fm shrink-0 rounded-[11px] border uppercase"
            style="padding: 9px 14px; border-color: var(--color-rule); color: var(--color-ink-soft); font-size: 10px; letter-spacing: 0.08em;"
          >Fix gaps →</NuxtLink>
        </div>

        <div class="mt-4 flex items-center gap-7">
          <div class="relative shrink-0" style="width: 128px; height: 128px;">
            <svg width="128" height="128" viewBox="0 0 128 128" style="transform: rotate(-90deg);">
              <circle cx="64" cy="64" :r="RING_R" fill="none" stroke="var(--color-surface-2)" stroke-width="11" />
              <circle
                cx="64" cy="64" :r="RING_R" fill="none" :stroke="houseWorld.accent" stroke-width="11" stroke-linecap="round"
                :stroke-dasharray="RING_C" :stroke-dashoffset="ringOffset" style="transition: stroke-dashoffset .5s ease;"
              />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="fd" style="font-size: 30px; line-height: 1; color: var(--color-ink);">{{ data?.completeness.overall_pct ?? 0 }}%</span>
              <span class="fm mt-0.5 uppercase" style="font-size: 8px; letter-spacing: 0.1em; color: var(--color-ink-mute);">complete</span>
            </div>
          </div>
          <div class="flex flex-1 flex-col gap-2.5">
            <div v-for="row in healthRows" :key="row.label" class="flex items-center justify-between gap-2.5">
              <span class="fb" style="font-size: 13px; color: var(--color-ink-soft);">
                {{ row.label }}
                <span v-if="row.drives" class="fb italic" :style="{ fontSize: '10px', color: houseWorld.accent, marginLeft: '6px' }">drives colour</span>
              </span>
              <span class="fm" :style="{ fontSize: '12px', color: row.n ? 'var(--color-ink)' : 'var(--color-ink-mute)' }">
                {{ row.n ? `${row.n} missing` : 'all set' }}
              </span>
            </div>
          </div>
        </div>

        <div class="fb mt-4 border-t pt-3.5" style="font-size: 12px; color: var(--color-ink-mute); border-color: var(--color-rule);">
          <strong style="color: var(--color-ink);">{{ data?.completeness.fully_done ?? 0 }} of {{ data?.completeness.total ?? 0 }}</strong>
          perfumes are fully curated.
        </div>
      </section>

      <!-- Most logged -->
      <section class="rounded-panel border p-[26px]" :style="cardStyle">
        <h2 class="fd" style="font-size: 22px; color: var(--color-ink);">Most logged</h2>
        <p class="fb mt-0.5 italic" style="font-size: 12.5px; color: var(--color-ink-mute);">Your members’ signatures. Wangian paling kerap.</p>

        <div v-if="topLogged.length" class="mt-4">
          <button
            v-for="(p, i) in topLogged"
            :key="p.name + p.brand"
            type="button"
            class="flex w-full items-center gap-3.5 py-3 text-left"
            :style="{ borderTop: i ? '1px solid var(--color-rule)' : 'none' }"
            @click="openPerfume(p)"
          >
            <span class="fm" style="width: 18px; font-size: 12px; color: var(--color-ink-mute);">{{ p.rank }}</span>
            <ScentOrb v-if="p.world" :world="p.world!" :size="14" />
            <span v-else class="shrink-0 rounded-full" style="width: 14px; height: 14px; border: 1.5px dashed var(--color-ink-mute);" />
            <span class="min-w-0 flex-1">
              <span class="fd block truncate" style="font-size: 16px; line-height: 1.1; color: var(--color-ink);">{{ p.name }}</span>
              <span class="fb block truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ p.brand }}</span>
            </span>
            <span class="fm whitespace-nowrap" style="font-size: 13px; color: var(--color-ink);">{{ p.wears.toLocaleString() }}</span>
          </button>
        </div>
        <div v-else-if="!loading" class="fb mt-6 italic" style="font-size: 13px; color: var(--color-ink-mute);">No wears logged yet. Belum ada rekod.</div>
      </section>
    </div>
  </div>
</template>
