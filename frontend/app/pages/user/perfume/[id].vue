<script setup lang="ts">
import { type ScentFamilyKey } from '~/utils/scent'
import { deriveFamily } from '~/utils/scentFamily'

definePageMeta({ layout: 'app', middleware: 'auth' })

type Bar = { label: string; value: number }

const route = useRoute()
const router = useRouter()
const api = useApi()
const wardrobe = useWardrobeStore()
const { worldFor } = useScentWorld()

const perfume = ref<any>(null)
const loading = ref(true)

function splitAccords(raw?: string | null): string[] {
  return (raw ?? '').split(',').map((s) => s.trim()).filter(Boolean)
}

// Colour the whole page from the fragrance's own scent world — a curated family
// wins, else derive it from the catalogue's main_accord vocabulary.
const family = computed<ScentFamilyKey>(
  () => perfume.value?.family ?? deriveFamily(splitAccords(perfume.value?.main_accord)),
)
const world = worldFor(
  () => family.value,
  () => `${perfume.value?.id ?? ''}${perfume.value?.name ?? ''}`,
)

const ownedItem = computed(() =>
  perfume.value ? wardrobe.items.find((i) => i.catalog_id === perfume.value.id) ?? null : null,
)

const accordChips = computed<string[]>(() => splitAccords(perfume.value?.main_accord))

const formatNotes = (raw: string) =>
  raw.split(',').map((s) => s.trim()).filter(Boolean).join(' · ')

const hasAnyNotes = computed(
  () => perfume.value?.top_notes || perfume.value?.middle_notes || perfume.value?.base_notes,
)

const noteTiers = computed(() => [
  { key: 'top', label: 'Top', heading: 'What you smell first', notes: perfume.value?.top_notes as string | null },
  { key: 'heart', label: 'Heart', heading: 'What it settles into', notes: perfume.value?.middle_notes as string | null },
  { key: 'base', label: 'Base', heading: 'What lingers on skin', notes: perfume.value?.base_notes as string | null },
])

const seasons = computed<Bar[]>(() => {
  const p = perfume.value
  if (!p) return []
  return [
    { label: 'Summer', value: p.percent_summer ?? 0 },
    { label: 'Autumn', value: p.percent_autumn ?? 0 },
    { label: 'Winter', value: p.percent_winter ?? 0 },
    { label: 'Spring', value: p.percent_spring ?? 0 },
  ]
})

const times = computed<Bar[]>(() => {
  const p = perfume.value
  if (!p) return []
  return [
    { label: 'Day', value: p.percent_day ?? 0 },
    { label: 'Night', value: p.percent_night ?? 0 },
  ]
})

const hasWearBars = computed(
  () => seasons.value.some((s) => s.value > 0) || times.value.some((t) => t.value > 0),
)

const goBack = () => {
  if (window.history.length > 1) router.back()
  else router.push('/user/perfume')
}

onMounted(async () => {
  try {
    perfume.value = await api.get(`/perfume/${route.params.id}`)
  } catch {
    // perfume stays null → "not on file" state
  } finally {
    loading.value = false
  }
}
)
</script>

<template>
  <div class="mx-auto max-w-5xl">
    <!-- Back -->
    <button
      type="button"
      class="fm inline-flex items-center gap-2 uppercase"
      style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);"
      @click="goBack"
    >
      <Icon name="lucide:arrow-left" size="14" />
      Back
    </button>

    <p v-if="loading" class="fb mt-20 text-center italic" style="color: var(--color-ink-soft);">Drawing from the cabinet…</p>
    <p v-else-if="!perfume" class="fb mt-20 text-center italic" style="color: var(--color-ink-soft);">That bottle isn't on file.</p>

    <template v-else>
      <!-- ── Hero ──────────────────────────────────────────────────────── -->
      <header class="mt-6 grid grid-cols-1 gap-8 md:grid-cols-[minmax(0,340px)_1fr] md:gap-12">
        <!-- colour panel -->
        <div
          class="flex min-h-80 items-center justify-center rounded-hero p-8"
          :style="{ background: world.bloom }"
        >
          <img
            v-if="perfume.image"
            :src="perfume.image"
            :alt="`${perfume.brand} ${perfume.name}`"
            class="max-h-70 w-full object-contain"
          >
          <ScentFlacon v-else :world="world" :size="150" />
        </div>

        <!-- details -->
        <div class="min-w-0">
          <div class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-mute);">{{ perfume.brand }}</div>
          <h1 class="fd mt-1.5" style="font-size: clamp(34px, 5vw, 48px); line-height: 1.04; color: var(--color-ink);">{{ perfume.name }}</h1>

          <div class="mt-3">
            <ScentFamilyLabel :world="world" :size="13" />
          </div>

          <!-- meta -->
          <p class="fm mt-3 uppercase" style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink-mute);">
            <template v-if="perfume.year">{{ perfume.year }}</template>
            <template v-if="perfume.country"><span :style="{ color: world.accent }" class="mx-1.5">·</span>{{ perfume.country }}</template>
            <template v-if="perfume.suit"><span :style="{ color: world.accent }" class="mx-1.5">·</span>{{ perfume.suit }}</template>
            <template v-if="perfume.rating">
              <span :style="{ color: world.accent }" class="mx-1.5">·</span>{{ perfume.rating.toFixed(2) }}
              <span class="ml-1" style="color: var(--color-ink-mute);">({{ perfume.votes }} votes)</span>
            </template>
          </p>

          <!-- accord chips -->
          <div v-if="accordChips.length" class="mt-5 flex flex-wrap gap-2">
            <span
              v-for="chip in accordChips"
              :key="chip"
              class="fm uppercase"
              :style="{ background: world.soft, color: world.accent, fontSize: '9px', letterSpacing: '0.12em', padding: '6px 12px', borderRadius: '999px' }"
            >{{ chip }}</span>
          </div>

          <!-- history -->
          <p
            v-if="perfume.history"
            class="fb mt-6 max-w-prose border-t pt-5 italic"
            style="font-size: 15px; line-height: 1.6; color: var(--color-ink-soft); border-color: var(--color-rule);"
          >{{ perfume.history }}</p>

          <!-- perfumers -->
          <p v-if="perfume.perfumers" class="fm mt-5 uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-mute);">
            <span :style="{ color: world.accent }" class="mr-1.5">/</span>By {{ perfume.perfumers }}
          </p>

          <!-- actions -->
          <div class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3">
            <NuxtLink
              :to="ownedItem ? `/user/wardrobe/${ownedItem.id}` : `/user/wardrobe/add?catalog_id=${perfume.id}`"
              class="inline-flex items-center gap-2 rounded-full px-6 py-3"
              :style="{ background: world.gradient, color: world.onGrad }"
            >
              <Icon :name="ownedItem ? 'lucide:library' : 'lucide:plus'" size="16" />
              <span class="fb" style="font-weight: 700;">{{ ownedItem ? 'Show in wardrobe' : 'Add to wardrobe' }}</span>
            </NuxtLink>
            <p v-if="ownedItem" class="fb italic" :style="{ fontSize: '14px', color: world.accent }">You already own this.</p>
          </div>
        </div>
      </header>

      <!-- ── Notes pyramid ─────────────────────────────────────────────── -->
      <section v-if="hasAnyNotes" class="mt-14">
        <p class="fm uppercase" :style="{ fontSize: '10px', letterSpacing: '0.2em', color: world.accent }">The pyramid</p>
        <h2 class="fd mt-1" style="font-size: 28px; color: var(--color-ink);">Notes <em style="color: var(--color-ink-soft);">it leaves on you.</em></h2>

        <div class="mt-6 grid grid-cols-1 gap-3 md:grid-cols-3">
          <article
            v-for="tier in noteTiers"
            v-show="tier.notes"
            :key="tier.key"
            class="rounded-card border p-6"
            style="background: var(--color-surface); border-color: var(--color-rule);"
          >
            <p class="fm uppercase" :style="{ fontSize: '9px', letterSpacing: '0.16em', color: world.accent }">{{ tier.label }}</p>
            <h3 class="fd mt-1.5" style="font-size: 18px; line-height: 1.2; color: var(--color-ink);">{{ tier.heading }}</h3>
            <p class="fb mt-3" style="font-size: 13px; line-height: 1.6; color: var(--color-ink-soft); text-transform: capitalize;">{{ formatNotes(tier.notes) }}</p>
          </article>
        </div>
      </section>

      <!-- ── Climate ───────────────────────────────────────────────────── -->
      <section v-if="hasWearBars" class="mt-14">
        <p class="fm uppercase" :style="{ fontSize: '10px', letterSpacing: '0.2em', color: world.accent }">Climate</p>
        <h2 class="fd mt-1" style="font-size: 28px; color: var(--color-ink);">When it <em style="color: var(--color-ink-soft);">wears best.</em></h2>

        <div class="mt-6 grid grid-cols-1 gap-8 md:grid-cols-2 md:gap-14">
          <div v-for="group in [{ label: 'Season', bars: seasons }, { label: 'Time of day', bars: times }]" :key="group.label">
            <p class="fm mb-4 uppercase" style="font-size: 9px; letter-spacing: 0.18em; color: var(--color-ink-mute);">{{ group.label }}</p>
            <div class="space-y-3.5">
              <div v-for="bar in group.bars" :key="bar.label">
                <div class="mb-1.5 flex items-baseline justify-between">
                  <span class="fm uppercase" style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink);">{{ bar.label }}</span>
                  <span class="fm" style="font-size: 10px; color: var(--color-ink-mute);">{{ bar.value }}%</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full" style="background: var(--color-surface-2);">
                  <div class="h-full rounded-full transition-all duration-700" :style="{ width: `${bar.value}%`, background: world.gradient }" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <p v-if="perfume.suit_season || perfume.suit_time" class="fb mt-8 italic" style="font-size: 14px; color: var(--color-ink-soft);">
          <template v-if="perfume.suit_season">Best worn through {{ perfume.suit_season.toLowerCase() }}</template>
          <template v-if="perfume.suit_season && perfume.suit_time">, </template>
          <template v-if="perfume.suit_time">suited to the {{ perfume.suit_time.toLowerCase() }}</template>.
        </p>
      </section>

      <!-- Fragrantica reference -->
      <div v-if="perfume.source_url" class="mt-14 flex justify-center border-t pt-8 sm:justify-end" style="border-color: var(--color-rule);">
        <a
          :href="perfume.source_url"
          target="_blank"
          rel="noopener noreferrer"
          class="fb italic"
          :style="{ fontSize: '14px', color: world.accent, borderBottom: `1px solid ${world.accent}`, paddingBottom: '2px' }"
        >Learn more about this perfume →</a>
      </div>
    </template>
  </div>
</template>
