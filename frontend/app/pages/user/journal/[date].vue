<script setup lang="ts">
import { scentWorld, hashSeed } from '~/utils/scent'
import { longevityLabel } from '~/utils/wear'
import type { JournalEntry } from '~/stores/journal'

definePageMeta({ layout: 'app', middleware: 'auth' })

const route = useRoute()
const journal = useJournalStore()
const wardrobe = useWardrobeStore()
const toast = useToast()
const { isDark } = useScentWorld()

const parsedDate = computed<Date | null>(() => {
  const raw = String(route.params.date ?? '')
  const m = raw.match(/^(\d{4})-(\d{2})-(\d{2})$/)
  if (!m) return null
  const d = new Date(Number(m[1]), Number(m[2]) - 1, Number(m[3]))
  if (d.getMonth() !== Number(m[2]) - 1) return null
  return d
})

const isToday = computed(() => parsedDate.value !== null && dateKey(parsedDate.value) === dateKey(new Date()))
const headline = computed(() =>
  parsedDate.value
    ? parsedDate.value.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
    : '',
)
useHead({ title: () => headline.value || 'Journal' })

const wears = computed<JournalEntry[]>(() => {
  if (!parsedDate.value) return []
  const key = dateKey(parsedDate.value)
  return journal.entries
    .filter((e) => dateKey(e.worn_at) === key)
    .sort((a, b) => (a.worn_at < b.worn_at ? -1 : 1))
})

function entryWorld(e: JournalEntry) {
  const item = e.wardrobe_item_id ? wardrobe.byId(e.wardrobe_item_id) : undefined
  const fam = item?.family ?? 'woody'
  return scentWorld(fam, hashSeed((item?.id ?? '') + e.name), isDark.value)
}

async function removeEntry(e: JournalEntry) {
  if (!window.confirm(`Remove this ${e.name} wear from your journal?`)) return
  try {
    await journal.remove(e.id)
    toast.success('Wear removed.')
  } catch {
    toast.error('Could not remove that wear. Try again.')
  }
}
</script>

<template>
  <div class="mx-auto max-w-3xl">
    <NuxtLink
      to="/user/journal"
      class="mb-5 inline-flex items-center gap-2 fm uppercase"
      style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);"
    >
      <Icon name="lucide:arrow-left" size="14" /> Back to the calendar
    </NuxtLink>

    <p v-if="!parsedDate" class="mt-20 text-center fb italic" style="color: var(--color-ink-soft);">That date doesn't read right.</p>

    <template v-else>
      <header class="mb-8">
        <div class="kicker">{{ isToday ? 'Today · the wear log' : 'The wear log' }}</div>
        <h1 class="fd mt-1" style="font-size: clamp(28px, 5vw, 40px); line-height: 1.05;">{{ headline }}</h1>
        <p class="fm mt-2" style="font-size: 12px; color: var(--color-ink-mute);">
          {{ wears.length }} {{ wears.length === 1 ? 'wear' : 'wears' }}
        </p>
      </header>

      <p v-if="!wears.length" class="py-12 text-center fb italic" style="color: var(--color-ink-soft);">Nothing logged on this day.</p>

      <ul v-else class="space-y-3">
        <li
          v-for="e in wears"
          :key="e.id"
          class="flex gap-3 overflow-hidden rounded-panel border"
          style="border-color: var(--color-rule); background: var(--color-surface);"
        >
          <span class="w-1.5 shrink-0" :style="{ background: entryWorld(e).gradient }" />
          <div class="min-w-0 flex-1 py-4 pr-4">
            <div class="flex items-center justify-between gap-2">
              <span class="fm" style="font-size: 11px; color: var(--color-ink-soft);">{{ formatTime(e.worn_at) }}</span>
              <span
                v-if="e.longevity"
                class="rounded-full px-2 py-0.5 fm uppercase"
                style="font-size: 9px; letter-spacing: 0.1em;"
                :style="{ background: entryWorld(e).soft, color: entryWorld(e).accent }"
              >{{ longevityLabel(e.longevity) }}</span>
            </div>
            <div class="kicker mt-1.5">{{ e.brand }}</div>
            <component
              :is="e.wardrobe_item_id ? 'NuxtLink' : 'h3'"
              :to="e.wardrobe_item_id ? `/user/wardrobe/${e.wardrobe_item_id}` : undefined"
              class="fd block leading-tight"
              style="font-size: 22px;"
            >{{ e.name }}</component>
            <p class="fb mt-1.5" style="font-size: 12px; color: var(--color-ink-mute);">
              <span v-if="e.mood">{{ e.mood }}</span>
              <span v-if="e.occasion"> · {{ e.occasion }}</span>
              <span v-if="e.weather"> · {{ e.weather }}</span>
              <span v-if="e.sprays"> · {{ e.sprays }} {{ e.sprays === 1 ? 'spray' : 'sprays' }}</span>
            </p>
            <p v-if="e.experience || e.notes" class="fb mt-2 italic" style="font-size: 14px; color: var(--color-ink-soft);">"{{ e.experience ?? e.notes }}"</p>
            <p v-if="e.compliments" class="fb mt-2 italic" style="font-size: 13px; color: var(--color-ink-soft);">
              <span class="fm not-italic uppercase" style="font-size: 8px; letter-spacing: 0.16em;" :style="{ color: entryWorld(e).accent }">Heard</span>
              "{{ e.compliments }}"
            </p>
            <div class="mt-3 flex items-center gap-4">
              <NuxtLink
                v-if="e.wardrobe_item_id"
                :to="`/user/wardrobe/${e.wardrobe_item_id}`"
                class="fb italic" style="font-size: 12px; color: var(--color-ink);"
              >Open bottle →</NuxtLink>
              <button type="button" class="fb italic" style="font-size: 12px; color: var(--color-ink-mute);" @click="removeEntry(e)">Remove</button>
            </div>
          </div>
        </li>
      </ul>
    </template>
  </div>
</template>
