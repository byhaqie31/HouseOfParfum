<template>
  <section class="relative">
    <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
      Today's recommendation
    </p>

    <!-- One bordered box, content varies by mode. min-h keeps the silhouette
         stable across picking → processing → result so the page doesn't reflow. -->
    <article class="relative border border-ink bg-paper min-h-150 md:min-h-145 flex flex-col">
      <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
      <div class="absolute -bottom-px right-0 w-12 h-px bg-accent" />

      <!-- ───────────  Empty wardrobe (no candidates to match against)  ─────────── -->
      <div v-if="wardrobe.count === 0" class="p-8 md:p-10 flex-1 flex flex-col">
        <h2 class="font-display text-3xl text-ink tracking-tight leading-tight">
          <em class="text-ink-soft">How</em> are you today?
        </h2>
        <p class="mt-3 font-display italic text-[14px] text-ink-soft max-w-xl leading-[1.6]">
          Add a bottle to your wardrobe first — we match against what you own.
        </p>
        <NuxtLink
          to="/wardrobe/add"
          class="mt-6 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-6 py-3 hover:bg-ink-soft transition-colors"
        >
          Add a bottle
          <Icon name="lucide:arrow-right" size="14" />
        </NuxtLink>
      </div>

      <!-- ───────────  Pickers (initial + edit)  ─────────── -->
      <div v-else-if="mode === 'picking'" class="p-8 md:p-10 flex-1 flex flex-col">
        <header class="mb-8">
          <h2 class="font-display text-3xl text-ink tracking-tight leading-tight">
            <em class="text-ink-soft">How</em> are you today?
          </h2>
          <p class="mt-3 font-display italic text-[14px] text-ink-soft max-w-xl leading-[1.6]">
            Tell us the mood, the weather, and the occasion. We'll pick the bottle from your shelf that fits.
          </p>
        </header>

        <div class="space-y-7">
          <div>
            <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-3">
              <span class="text-accent-deep mr-1.5">/</span>Mood
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="(label, key) in MOOD_LABELS"
                :key="key"
                type="button"
                class="chip"
                :class="{ 'is-active': mood === key }"
                @click="mood = key"
              >
                {{ label }}
              </button>
            </div>
          </div>

          <div>
            <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-3">
              <span class="text-accent-deep mr-1.5">/</span>Weather
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="(label, key) in WEATHER_LABELS"
                :key="key"
                type="button"
                class="chip"
                :class="{ 'is-active': weather === key }"
                @click="weather = key"
              >
                {{ label }}
              </button>
            </div>
          </div>

          <div>
            <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-3">
              <span class="text-accent-deep mr-1.5">/</span>Occasion
            </p>
            <div class="flex flex-wrap gap-2">
              <button
                v-for="(label, key) in OCCASION_LABELS"
                :key="key"
                type="button"
                class="chip"
                :class="{ 'is-active': occasion === key }"
                @click="occasion = key"
              >
                {{ label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Apply / Cancel pinned to the bottom of the box, mirroring the result card's action row -->
        <div v-if="isEditing" class="mt-auto pt-8">
          <div class="border-t border-rule pt-6 flex flex-wrap items-center justify-end gap-5">
            <button
              type="button"
              class="font-display italic text-[14px] text-ink-soft hover:text-ink pb-1 border-b border-rule transition-colors"
              @click="cancelEdit"
            >
              Cancel
            </button>
            <button
              type="button"
              class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
              :disabled="!isComplete"
              @click="applyEdit"
            >
              Apply changes
            </button>
          </div>
        </div>
      </div>

      <!-- ───────────  Processing animation  ─────────── -->
      <div v-else-if="mode === 'processing'" class="p-8 md:p-10 flex-1 flex flex-col">
        <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-accent-deep mb-7">
          Reading your mix
        </p>
        <ol class="space-y-4">
          <li
            v-for="(step, i) in STEPS"
            :key="step.key"
            class="flex items-center gap-4"
          >
            <span class="status-marker" :class="markerStatus(i)">
              <span v-if="i < stepIndex" aria-hidden="true">/</span>
              <span v-else-if="i === stepIndex" class="status-dot" aria-hidden="true" />
            </span>
            <span
              class="font-display text-[15px] leading-tight"
              :class="labelStatus(i)"
            >
              {{ step.label }}<span v-if="i === stepIndex" aria-hidden="true">…</span>
            </span>
          </li>
        </ol>
      </div>

      <!-- ───────────  Error  ─────────── -->
      <div v-else-if="mode === 'error'" class="p-8 md:p-10 flex-1 flex flex-col">
        <p class="font-display italic text-[14px] text-ink-soft mb-3">
          Couldn't reach the catalog to read your wardrobe's profile.
        </p>
        <button
          type="button"
          class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          @click="commitAndProcess"
        >
          Try again
        </button>
      </div>

      <!-- ───────────  Result (from wardrobe)  ─────────── -->
      <template v-else-if="mode === 'result' && topMatch">
        <!-- Box header: the user's chosen mix + change trigger -->
        <header class="border-b border-rule px-6 py-5 sm:px-8 sm:py-6 flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
          <p class="font-display italic text-[15px] sm:text-[16px] text-ink leading-snug max-w-prose">
            {{ headerSentence }}
          </p>
          <button
            type="button"
            class="shrink-0 ml-auto font-mono text-[10px] uppercase tracking-[0.18em] text-ink-soft hover:text-accent-deep pb-1 border-b border-accent transition-colors"
            @click="startEdit"
          >
            Change mood
          </button>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-0 flex-1">
          <div class="p-8 md:p-10 md:border-r md:border-rule flex items-center justify-center">
            <div class="aspect-3/4 w-full max-w-70 bg-paper-deep border border-rule flex items-center justify-center">
              <BottleIcon :size="120" />
            </div>
          </div>

          <div class="p-8 md:p-10 flex flex-col min-w-0">
            <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-accent-deep">
              From your wardrobe
            </p>
            <p class="mt-3 font-display italic text-[15px] text-ink-soft leading-tight">
              {{ topMatch.item.brand }}
            </p>
            <h3 class="mt-1 font-display text-3xl sm:text-4xl text-ink tracking-[-0.005em] leading-[1.05]">
              <NuxtLink
                :to="`/wardrobe/${topMatch.item.id}`"
                class="hover:text-accent-deep transition-colors"
              >
                {{ topMatch.item.name }}
              </NuxtLink>
            </h3>
            <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ topMatch.item.size }} ML
              <template v-if="topMatch.item.acquired"> &middot; {{ topMatch.item.acquired }}</template>
            </p>

            <NuxtLink
              v-if="topMatch.entry?.id"
              :to="`/perfume/${topMatch.entry.id}`"
              class="mt-3 inline-block self-start font-display italic text-[13px] text-ink hover:text-accent-deep pb-px border-b border-accent transition-colors"
            >
              Show the dossier &rarr;
            </NuxtLink>

            <p
              class="mt-5 pt-4 border-t border-rule font-display italic text-[15px] leading-normal max-w-prose"
              :class="isPickWornToday ? 'text-accent-deep' : 'text-ink-soft'"
            >
              <template v-if="isPickWornToday">
                You're wearing this now.
              </template>
              <template v-else>
                {{ matchReason }}
              </template>
            </p>

            <!-- Accord chips (from catalog enrichment) -->
            <div v-if="accordChips.length" class="mt-6">
              <p class="font-mono text-[9px] uppercase tracking-[0.22em] text-ink-mute mb-3">
                <span class="text-accent-deep mr-1.5">/</span>Accord
              </p>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="chip in accordChips"
                  :key="chip"
                  class="px-2.5 py-1 font-mono text-[9px] uppercase tracking-[0.14em] text-ink bg-paper-deep border border-rule"
                >
                  {{ chip }}
                </span>
              </div>
            </div>

            <!-- Action row pinned to the bottom of the right column -->
            <div class="mt-auto pt-8">
              <div class="border-t border-rule pt-6 flex flex-wrap items-center justify-between gap-4">
                <button
                  v-if="matches.length > 1 && !isPickWornToday"
                  type="button"
                  class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
                  @click="cycleMatch"
                >
                  Show me another
                </button>
                <span v-else aria-hidden="true" />

                <NuxtLink
                  v-if="isPickWornToday"
                  :to="`/wardrobe/${topMatch.item.id}`"
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
      </template>

      <!-- ───────────  Result but no matchable item in wardrobe  ─────────── -->
      <div v-else-if="mode === 'result' && !topMatch" class="p-8 md:p-10">
        <p class="font-display italic text-[15px] text-ink-soft leading-normal max-w-prose mb-4">
          We couldn't match any bottle on your shelf to this mood —
          none of them are linked to the catalog yet.
        </p>
        <NuxtLink
          to="/perfume"
          class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
        >
          Browse the catalog &rarr;
        </NuxtLink>
      </div>
    </article>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import {
  MOOD_LABELS,
  WEATHER_LABELS,
  OCCASION_LABELS,
  targetFingerprint,
  scoreCatalogEntry,
  whyMatches,
  type Mood,
  type Weather,
  type Occasion,
  type CatalogPerfume,
  type Fingerprint,
} from '~/data/scent-matching'
import type { WardrobeItem } from '~/stores/wardrobe'
import type { JournalEntry } from '~/stores/journal'

const api = useApi()
const wardrobe = useWardrobeStore()
const journal = useJournalStore()

// ───────── State ─────────
type Mode = 'picking' | 'processing' | 'result' | 'error'

const mood = ref<Mood | null>(null)
const weather = ref<Weather | null>(null)
const occasion = ref<Occasion | null>(null)

const mode = ref<Mode>('picking')
const stepIndex = ref(-1)
const matchIndex = ref(0)
const catalog = ref<CatalogPerfume[]>([])

interface Committed {
  mood: Mood
  weather: Weather
  occasion: Occasion
}
const committed = ref<Committed | null>(null)

const isComplete = computed(() => Boolean(mood.value && weather.value && occasion.value))
const isEditing = computed(() => mode.value === 'picking' && committed.value !== null)

// ───────── Processing animation ─────────
const STEPS = [
  { key: 'prompt',    label: 'Reading your mix' },
  { key: 'inventory', label: 'Checking your wardrobe' },
  { key: 'finding',   label: 'Finding the best perfume for you' },
] as const

function wait(ms: number): Promise<void> {
  return new Promise((resolve) => setTimeout(resolve, ms))
}

function markerStatus(i: number) {
  if (i < stepIndex.value) return 'is-done'
  if (i === stepIndex.value) return 'is-active'
  return 'is-pending'
}
function labelStatus(i: number) {
  if (i < stepIndex.value) return 'text-ink-mute'
  if (i === stepIndex.value) return 'text-ink'
  return 'text-ink-mute opacity-50'
}

// ───────── Persistence (daily) ─────────
const STORAGE_KEY = 'hop:scent-match:v1'

interface Stored extends Committed {
  date: string
}

function todayStr(): string {
  const d = new Date()
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

function loadStored(): Stored | null {
  if (typeof window === 'undefined') return null
  try {
    const raw = window.localStorage.getItem(STORAGE_KEY)
    if (!raw) return null
    const parsed = JSON.parse(raw) as Stored
    if (parsed.date !== todayStr()) return null
    return parsed
  } catch {
    return null
  }
}

function saveStored(s: Committed) {
  if (typeof window === 'undefined') return
  try {
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify({ date: todayStr(), ...s }))
  } catch {
    /* quota / disabled — non-critical */
  }
}

// ───────── Catalog fetch (needed to derive fingerprints for wardrobe items) ─────────
async function ensureCatalog() {
  if (catalog.value.length) return
  const data = await api.get('/perfume')
  catalog.value = Array.isArray(data) ? data : []
}

// ───────── Processing run ─────────
async function runProcessing() {
  mode.value = 'processing'
  stepIndex.value = 0
  const fetchPromise = ensureCatalog()
  await wait(2200) // Reading your mix

  stepIndex.value = 1
  try {
    await fetchPromise
  } catch {
    mode.value = 'error'
    return
  }
  await wait(2000) // Checking your wardrobe (guaranteed min visible time)

  stepIndex.value = 2
  await wait(2900) // Finding the best perfume for you

  mode.value = 'result'
}

// ───────── Commit + processing ─────────
function commitAndProcess() {
  if (!isComplete.value) return
  const c: Committed = {
    mood: mood.value!,
    weather: weather.value!,
    occasion: occasion.value!,
  }
  committed.value = c
  saveStored(c)
  matchIndex.value = 0
  runProcessing()
}

// Auto-trigger on the first complete selection (no prior committed state).
// When editing, the user must press "Apply changes".
watch([mood, weather, occasion], () => {
  if (committed.value === null && isComplete.value && mode.value === 'picking') {
    commitAndProcess()
  }
})

// ───────── Edit flow ─────────
function startEdit() {
  mode.value = 'picking'
}
function applyEdit() {
  commitAndProcess()
}
function cancelEdit() {
  if (committed.value) {
    mood.value = committed.value.mood
    weather.value = committed.value.weather
    occasion.value = committed.value.occasion
    mode.value = 'result'
  } else {
    mood.value = null
    weather.value = null
    occasion.value = null
    mode.value = 'picking'
  }
}

// ───────── Hydration ─────────
onMounted(() => {
  const stored = loadStored()
  if (stored) {
    committed.value = { mood: stored.mood, weather: stored.weather, occasion: stored.occasion }
    mood.value = stored.mood
    weather.value = stored.weather
    occasion.value = stored.occasion
    mode.value = 'result'
    ensureCatalog().catch(() => {
      mode.value = 'error'
    })
  }
})

// ───────── Wardrobe matching ─────────
// Join wardrobe items with their catalog entry (when linked), then rank by
// distance from the target fingerprint. Free-entered items (catalog_id === null)
// have no scent profile to compare against, so they're excluded.
interface WardrobeMatch {
  item: WardrobeItem
  entry: CatalogPerfume
  fingerprint: Fingerprint
  score: number
}

const matches = computed<WardrobeMatch[]>(() => {
  if (mode.value !== 'result') return []
  if (!isComplete.value) return []
  if (!catalog.value.length || !wardrobe.items.length) return []

  const target = targetFingerprint(mood.value!, weather.value!, occasion.value!)
  const ranked: WardrobeMatch[] = []
  for (const item of wardrobe.items) {
    if (typeof item.catalog_id !== 'number') continue
    const entry = catalog.value.find((p) => p.id === item.catalog_id)
    if (!entry) continue
    const { fingerprint, score } = scoreCatalogEntry(entry, target)
    ranked.push({ item, entry, fingerprint, score })
  }
  ranked.sort((a, b) => b.score - a.score)
  return ranked
})

const topMatch = computed<WardrobeMatch | null>(() => matches.value[matchIndex.value] ?? null)

const matchReason = computed(() => {
  if (!topMatch.value || !mood.value || !weather.value || !occasion.value) return ''
  const target = targetFingerprint(mood.value, weather.value, occasion.value)
  return whyMatches(target, topMatch.value.fingerprint)
})

const accordChips = computed(() => {
  const raw = topMatch.value?.entry.main_accord ?? ''
  return raw
    .split(',')
    .map((s) => s.trim())
    .filter(Boolean)
    .slice(0, 4)
})

function cycleMatch() {
  if (!matches.value.length) return
  matchIndex.value = (matchIndex.value + 1) % matches.value.length
}

// ───────── "I'm wearing this" tracking ─────────
function isSameDay(iso: string) {
  const a = new Date(iso)
  const b = new Date()
  return (
    a.getFullYear() === b.getFullYear()
    && a.getMonth() === b.getMonth()
    && a.getDate() === b.getDate()
  )
}

const isPickWornToday = computed(() => {
  const m = topMatch.value
  if (!m) return false
  return journal.entries.some(
    (e: JournalEntry) => e.wardrobe_item_id === m.item.id && isSameDay(e.worn_at),
  )
})

function wearThis() {
  const m = topMatch.value
  if (!m || isPickWornToday.value) return
  journal.log({
    wardrobe_item_id: m.item.id,
    brand: m.item.brand,
    name: m.item.name,
  })
}

// ───────── Header sentence ─────────
const OCCASION_PHRASE: Record<Occasion, string> = {
  work:    'the office',
  leisure: 'a leisurely day',
  evening: 'the evening',
  date:    'a date',
  active:  'something active',
}

const headerSentence = computed(() => {
  if (!mood.value || !weather.value || !occasion.value) return ''
  const m = MOOD_LABELS[mood.value].toLowerCase()
  const w = WEATHER_LABELS[weather.value].toLowerCase()
  const o = OCCASION_PHRASE[occasion.value]
  return `You're feeling ${m} on a ${w} day, going for ${o}.`
})
</script>

<style scoped>
.chip {
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 10px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--color-ink);
  background: var(--color-paper);
  border: 1px solid var(--color-rule);
  padding: 8px 14px;
  cursor: pointer;
  transition: color 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
}
.chip:hover {
  border-color: var(--color-ink-soft);
}
.chip.is-active {
  color: var(--color-paper);
  background: var(--color-ink);
  border-color: var(--color-ink);
}

.status-marker {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 14px;
  height: 14px;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 12px;
  line-height: 1;
}
.status-marker.is-done {
  color: var(--color-accent-deep, oklch(0.54 0.12 78));
}
.status-marker.is-pending {
  color: transparent;
}
.status-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--color-accent-deep, oklch(0.54 0.12 78));
  animation: scent-pulse 1.2s ease-in-out infinite;
}
@keyframes scent-pulse {
  0%, 100% { opacity: 0.35; transform: scale(0.85); }
  50%      { opacity: 1;    transform: scale(1.25); }
}
</style>
