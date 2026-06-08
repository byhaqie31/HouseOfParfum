<script setup lang="ts">
import { longevityLabel } from '~/utils/wear'

definePageMeta({ layout: 'app', middleware: 'auth' })

const route = useRoute()
const router = useRouter()
const wardrobe = useWardrobeStore()
const journal = useJournalStore()
const logStore = useLogStore()
const toast = useToast()
const { worldFor } = useScentWorld()

const id = computed(() => String(route.params.id))
const item = computed(() => wardrobe.byId(id.value) ?? null)
useHead({ title: () => item.value?.name ?? 'Bottle' })

const world = worldFor(() => item.value?.family ?? 'woody', () => (item.value ? item.value.id + item.value.name : 'x'))

const wearCount = computed(() => journal.wearCount(id.value))
const lastWorn = computed(() => journal.lastWornAt(id.value))

const usualSprays = computed(() => {
  const vals = journal.entries
    .filter((e) => e.wardrobe_item_id === id.value && typeof e.sprays === 'number')
    .map((e) => e.sprays as number)
  if (!vals.length) return null
  return Math.round(vals.reduce((a, b) => a + b, 0) / vals.length)
})

const wears = computed(() =>
  journal.entries
    .filter((e) => e.wardrobe_item_id === id.value)
    .sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1)),
)

const swatches = computed(() => [
  { label: 'Gradient', value: world.value.c1, bg: world.value.gradient },
  { label: 'Accent', value: world.value.accent, bg: world.value.accent },
  { label: 'Soft', value: world.value.soft, bg: world.value.soft },
])

function logThis() { if (item.value) logStore.start(item.value) }

async function confirmRemove() {
  if (!item.value) return
  if (!window.confirm(`Remove ${item.value.brand} ${item.value.name} from your shelf?`)) return
  try {
    await wardrobe.remove(item.value.id)
    toast.success('Removed from your shelf.')
    router.push('/user/wardrobe')
  } catch {
    toast.error('Could not remove the bottle. Try again.')
  }
}
</script>

<template>
  <div>
    <button
      type="button"
      class="mb-5 inline-flex items-center gap-2 fm uppercase"
      style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);"
      @click="router.push('/user/wardrobe')"
    >
      <Icon name="lucide:arrow-left" size="14" /> Back to your shelf
    </button>

    <p v-if="!item" class="fb mt-20 text-center italic" style="color: var(--color-ink-soft);">That bottle isn't on your shelf.</p>

    <div v-else class="lg:grid lg:grid-cols-[minmax(0,440px)_minmax(0,1fr)] lg:gap-8">
      <!-- ── left: gradient panel ──────────────────────────────────────── -->
      <section class="lg:sticky lg:top-7 lg:self-start">
        <div
          class="flex flex-col items-center rounded-hero p-8 text-center"
          :style="{ background: world.bloom, color: world.onGrad, boxShadow: `0 22px 60px -30px ${world.c2}` }"
        >
          <ScentFlacon :world="world" :size="120" />
          <div class="mt-6">
            <div class="fm uppercase" style="font-size: 11px; letter-spacing: 0.18em; opacity: 0.8;">{{ item.brand }}</div>
            <h1 class="fd mt-1" style="font-size: clamp(30px, 5vw, 42px); line-height: 1.05;">{{ item.name }}</h1>
            <p v-if="item.tagline" class="fb mt-2 italic" style="font-size: 14px; opacity: 0.9;">{{ item.tagline }}</p>
          </div>
          <div class="mt-4 flex flex-wrap items-center justify-center gap-2" style="opacity: 0.92;">
            <span class="fm uppercase" style="font-size: 10px; letter-spacing: 0.16em;">{{ world.family.label }}</span>
            <template v-if="item.concentration"><span style="opacity: 0.6;">·</span><span class="fb" style="font-size: 12px;">{{ item.concentration }}</span></template>
          </div>

          <button
            type="button"
            class="mt-6 flex w-full items-center justify-center gap-2 rounded-full py-3.5"
            :style="{ background: world.onGrad, color: world.c2 }"
            @click="logThis"
          >
            <Icon name="lucide:spray-can" size="18" /><span class="fb" style="font-size: 15px; font-weight: 700;">Spray on and log</span>
          </button>
        </div>

        <p v-if="item.notes" class="fb mt-5 text-center italic" style="font-size: 14px; color: var(--color-ink-soft);">"{{ item.notes }}"</p>
      </section>

      <!-- ── right: stats, pyramid, colour, wears ──────────────────────── -->
      <section class="mt-8 space-y-8 lg:mt-0">
        <!-- wear stats strip -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
          <div v-for="stat in [
            { k: 'Total wears', v: String(wearCount) },
            { k: 'Last worn', v: lastWorn ? relativeDay(lastWorn) : 'never' },
            { k: 'Usual dose', v: usualSprays ? `${usualSprays} ${usualSprays === 1 ? 'spray' : 'sprays'}` : '—' },
            { k: 'Bottle size', v: `${item.size} ml` },
          ]" :key="stat.k" class="rounded-card border p-4" style="border-color: var(--color-rule); background: var(--color-surface);">
            <div class="kicker">{{ stat.k }}</div>
            <div class="fd mt-1.5" style="font-size: 18px;">{{ stat.v }}</div>
          </div>
        </div>

        <!-- notes pyramid -->
        <div v-if="item.notes_top?.length || item.notes_heart?.length || item.notes_base?.length">
          <h2 class="fd mb-3" style="font-size: 20px;">The notes</h2>
          <NotesPyramid :world="world" :top="item.notes_top" :heart="item.notes_heart" :base="item.notes_base" />
        </div>

        <div class="grid gap-8 md:grid-cols-2">
          <!-- this bottle's colour -->
          <div>
            <h2 class="fd mb-3" style="font-size: 20px;">This bottle's colour</h2>
            <div class="space-y-2">
              <div v-for="s in swatches" :key="s.label" class="flex items-center gap-3 rounded-card border p-3" style="border-color: var(--color-rule); background: var(--color-surface);">
                <span class="h-9 w-9 shrink-0 rounded-lg" :style="{ background: s.bg }" />
                <span class="min-w-0">
                  <span class="fb block" style="font-size: 13px;">{{ s.label }}</span>
                  <span class="fm block truncate" style="font-size: 10px; color: var(--color-ink-mute);">{{ s.value }}</span>
                </span>
              </div>
            </div>
          </div>

          <!-- recent wears -->
          <div>
            <h2 class="fd mb-3" style="font-size: 20px;">Recent wears</h2>
            <ul v-if="wears.length" class="space-y-2">
              <li
                v-for="e in wears.slice(0, 6)"
                :key="e.id"
                class="flex gap-3 rounded-card border p-3"
                style="border-color: var(--color-rule); background: var(--color-surface);"
              >
                <span class="w-1.5 shrink-0 rounded-full" :style="{ background: world.gradient }" />
                <div class="min-w-0 flex-1">
                  <div class="flex items-center justify-between gap-2">
                    <span class="fm" style="font-size: 11px; color: var(--color-ink-soft);">{{ relativeDay(e.worn_at) }} · {{ formatTime(e.worn_at) }}</span>
                    <span v-if="e.longevity" class="rounded-full px-2 py-0.5 fm uppercase" style="font-size: 9px; letter-spacing: 0.1em;" :style="{ background: world.soft, color: world.accent }">{{ longevityLabel(e.longevity) }}</span>
                  </div>
                  <p v-if="e.experience || e.notes" class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-soft);">"{{ e.experience ?? e.notes }}"</p>
                  <p class="fb mt-1" style="font-size: 11px; color: var(--color-ink-mute);">
                    <span v-if="e.mood">{{ e.mood }}</span>
                    <span v-if="e.occasion"> · {{ e.occasion }}</span>
                    <span v-if="e.sprays"> · {{ e.sprays }} {{ e.sprays === 1 ? 'spray' : 'sprays' }}</span>
                  </p>
                </div>
              </li>
            </ul>
            <p v-else class="fb italic" style="font-size: 14px; color: var(--color-ink-soft);">Never worn yet. Press "spray on and log" to begin.</p>
          </div>
        </div>

        <!-- remove -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-t pt-5" style="border-color: var(--color-rule);">
          <p class="fb italic" style="font-size: 13px; color: var(--color-ink-mute); max-width: 34ch;">Removing takes the bottle off your shelf. Journal entries stay where they are.</p>
          <button type="button" class="fb italic" style="font-size: 14px; color: var(--color-ink-soft);" @click="confirmRemove">Remove from shelf</button>
        </div>
      </section>
    </div>
  </div>
</template>
