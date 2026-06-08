<script setup lang="ts">
import { scentWorld, hashSeed } from '~/utils/scent'
import { longevityLabel } from '~/utils/wear'
import type { JournalEntry } from '~/stores/journal'

definePageMeta({ layout: 'app', middleware: 'auth' })
useHead({ title: 'Journal' })

const journal = useJournalStore()
const wardrobe = useWardrobeStore()
const { isDark } = useScentWorld()

onMounted(() => { journal.load() })

const selected = ref(dateKey(new Date()))

const selectedLabel = computed(() => {
  const d = parseTimestamp(selected.value)
  const isToday = dateKey(d) === dateKey(new Date())
  return isToday
    ? 'Today'
    : d.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' })
})

const selectedWears = computed(() =>
  journal.entries
    .filter((e) => dateKey(e.worn_at) === selected.value)
    .sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1)),
)

function entryWorld(e: JournalEntry) {
  const item = e.wardrobe_item_id ? wardrobe.byId(e.wardrobe_item_id) : undefined
  const fam = item?.family ?? 'woody'
  return scentWorld(fam, hashSeed((item?.id ?? '') + e.name), isDark.value)
}

function openEntry(e: JournalEntry) {
  if (e.wardrobe_item_id) navigateTo(`/user/wardrobe/${e.wardrobe_item_id}`)
}
</script>

<template>
  <div>
    <header class="mb-6">
      <div class="kicker">Your wear log</div>
      <h1 class="fd mt-1" style="font-size: clamp(30px, 6vw, 44px); line-height: 1.05;">Journal</h1>
    </header>

    <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_380px] lg:gap-8">
      <!-- calendar -->
      <section class="rounded-panel border p-4 md:p-5" style="border-color: var(--color-rule); background: var(--color-surface);">
        <ScentCalendar v-model="selected" :entries="journal.entries" />
      </section>

      <!-- selected day -->
      <aside class="mt-6 lg:mt-0">
        <div class="lg:sticky lg:top-7">
          <div class="mb-4">
            <h2 class="fd" style="font-size: 22px;">{{ selectedLabel }}</h2>
            <p class="fm mt-1" style="font-size: 11px; color: var(--color-ink-mute);">
              {{ selectedWears.length }} {{ selectedWears.length === 1 ? 'wear' : 'wears' }} logged
            </p>
          </div>

          <ul v-if="selectedWears.length" class="space-y-3">
            <li
              v-for="e in selectedWears"
              :key="e.id"
              class="flex cursor-pointer gap-3 overflow-hidden rounded-panel border"
              style="border-color: var(--color-rule); background: var(--color-surface);"
              @click="openEntry(e)"
            >
              <span class="w-1.5 shrink-0" :style="{ background: entryWorld(e).gradient }" />
              <div class="min-w-0 flex-1 py-3.5 pr-4">
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
                <h3 class="fd leading-tight" style="font-size: 18px;">{{ e.name }}</h3>
                <p class="fb mt-1" style="font-size: 11px; color: var(--color-ink-mute);">
                  <span v-if="e.mood">{{ e.mood }}</span>
                  <span v-if="e.occasion"> · {{ e.occasion }}</span>
                  <span v-if="e.sprays"> · {{ e.sprays }} {{ e.sprays === 1 ? 'spray' : 'sprays' }}</span>
                  <span v-if="!e.mood && !e.occasion && !e.sprays">logged</span>
                </p>
                <p v-if="e.experience || e.notes" class="fb mt-2 italic" style="font-size: 13px; color: var(--color-ink-soft);">"{{ e.experience ?? e.notes }}"</p>
              </div>
            </li>
          </ul>

          <div
            v-else
            class="rounded-panel border border-dashed p-8 text-center"
            style="border-color: var(--color-rule);"
          >
            <p class="fb italic" style="font-size: 14px; color: var(--color-ink-soft);">
              {{ journal.count === 0 ? 'Nothing in the journal yet. Log a wear and it lands here.' : 'Nothing logged on this day.' }}
            </p>
          </div>

          <NuxtLink
            v-if="selectedWears.length"
            :to="`/user/journal/${selected}`"
            class="mt-4 inline-flex items-center gap-2 fb italic"
            style="font-size: 14px; color: var(--color-ink);"
          >View the full day <Icon name="lucide:arrow-right" size="14" /></NuxtLink>
        </div>
      </aside>
    </div>
  </div>
</template>
