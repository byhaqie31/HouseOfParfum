<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-3xl mx-auto">
      <!-- Back to calendar -->
      <NuxtLink
        to="/journal"
        class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft hover:text-ink transition-colors"
      >
        <Icon name="lucide:arrow-left" size="14" />
        Back to the calendar
      </NuxtLink>

      <template v-if="!validDate">
        <p class="mt-20 text-center font-display italic text-ink-soft">
          That date doesn't read right.
        </p>
      </template>

      <template v-else>
        <!-- Header -->
        <header class="mt-8 border-y border-ink relative py-8">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-accent-deep">
            <template v-if="isToday">Today &middot; the wear log</template>
            <template v-else>The wear log</template>
          </p>
          <h1 class="mt-2 font-display text-4xl sm:text-5xl text-ink tracking-[-0.005em] leading-none">
            {{ headlineLong }}
          </h1>
          <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
            {{ String(wears.length).padStart(2, '0') }}
            {{ wears.length === 1 ? 'wear' : 'wears' }}
            <template v-if="wears.length > 0">
              <span class="text-accent-deep mx-1">·</span>
              From {{ wears[wears.length - 1].timeLabel }}
              <template v-if="wears.length > 1"> to {{ wears[0].timeLabel }}</template>
            </template>
          </p>
        </header>

        <!-- Empty state -->
        <p
          v-if="wears.length === 0"
          class="mt-16 font-display italic text-[15px] text-ink-soft text-center py-12"
        >
          Nothing logged on this day.
        </p>

        <!-- Timeline -->
        <div v-else class="mt-12 relative grid grid-cols-[16px_1fr] gap-x-5">
          <!-- continuous vertical hairline spanning the marker column -->
          <div class="absolute top-2 bottom-2 left-[7px] w-px bg-rule" />

          <template v-for="entry in wears" :key="entry.id">
            <!-- Marker column -->
            <div class="relative">
              <div
                class="w-3 h-3 rounded-full mt-1.5 mx-auto relative z-10"
                :class="entry.isToday
                  ? 'bg-accent ring-1 ring-accent-deep'
                  : 'bg-paper border border-ink-soft'"
              />
            </div>

            <!-- Content -->
            <div class="pb-12">
              <div class="flex items-baseline justify-between gap-3 mb-2 flex-wrap">
                <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink">
                  {{ entry.timeLabel }}
                </p>
                <span
                  v-if="entry.longevity"
                  class="shrink-0 px-2.5 py-1 font-mono text-[8px] uppercase tracking-[0.16em] bg-accent-soft border border-accent text-accent-deep"
                >
                  {{ longevityLabel(entry.longevity) }}
                </span>
              </div>

              <p class="font-display italic text-[14px] text-ink-soft leading-tight">
                {{ entry.brand }}
              </p>
              <NuxtLink
                v-if="entry.vanity_item_id"
                :to="`/vanity/${entry.vanity_item_id}`"
                class="block mt-0.5 font-display text-2xl text-ink hover:text-accent-deep leading-tight transition-colors"
              >
                {{ entry.name }}
              </NuxtLink>
              <h3 v-else class="mt-0.5 font-display text-2xl text-ink leading-tight">
                {{ entry.name }}
              </h3>

              <p
                v-if="entry.experience || entry.notes"
                class="mt-3 font-display italic text-[16px] text-ink-soft leading-normal"
              >
                &ldquo;{{ entry.experience ?? entry.notes }}&rdquo;
              </p>
              <p
                v-else
                class="mt-3 font-display italic text-[13px] text-ink-mute"
              >
                <template v-if="entry.isToday">
                  Diary blank — write something from the bottle as the day unfolds.
                </template>
                <template v-else>
                  Worn, no words.
                </template>
              </p>

              <p
                v-if="entry.compliments"
                class="mt-3 flex items-baseline gap-3 font-display italic text-[15px] text-ink-soft"
              >
                <span class="font-mono not-italic text-[8px] uppercase tracking-[0.18em] text-accent-deep shrink-0">
                  Heard
                </span>
                &ldquo;{{ entry.compliments }}&rdquo;
              </p>

              <NuxtLink
                v-if="entry.vanity_item_id"
                :to="`/vanity/${entry.vanity_item_id}`"
                class="mt-4 inline-block font-display italic text-[12px] text-ink hover:text-accent-deep border-b border-accent pb-px transition-colors"
              >
                Open diary &rarr;
              </NuxtLink>
            </div>
          </template>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { JournalEntry, Longevity } from '~/stores/journal'

definePageMeta({ middleware: 'auth' })

const route = useRoute()
const journal = useJournalStore()

const longevityLabels: Record<Longevity, string> = {
  brief: 'Brief',
  settled: 'Settled',
  lasting: 'Lasting',
  'all-day': 'All day',
  'into-night': 'Into the night',
}
const longevityLabel = (v: Longevity) => longevityLabels[v] ?? ''

// Parse the YYYY-MM-DD route param into a Date (validates strictly).
const parsedDate = computed<Date | null>(() => {
  const raw = String(route.params.date ?? '')
  const m = raw.match(/^(\d{4})-(\d{2})-(\d{2})$/)
  if (!m) return null
  const year = Number(m[1])
  const month = Number(m[2])
  const day = Number(m[3])
  const d = new Date(year, month - 1, day)
  if (
    d.getFullYear() !== year
    || d.getMonth() !== month - 1
    || d.getDate() !== day
  ) return null
  return d
})

const validDate = computed(() => parsedDate.value !== null)

const isSameDay = (a: Date, b: Date) =>
  a.getFullYear() === b.getFullYear()
  && a.getMonth() === b.getMonth()
  && a.getDate() === b.getDate()

const isToday = computed(() =>
  parsedDate.value !== null && isSameDay(parsedDate.value, new Date()),
)

const headlineLong = computed(() => {
  if (!parsedDate.value) return ''
  // "Wednesday, 13 May 2026"
  return parsedDate.value.toLocaleDateString('en-GB', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
})

type DecoratedEntry = JournalEntry & {
  isToday: boolean
  timeLabel: string
}

// Newest first within the day so the most recent wear is at the top of the timeline.
const wears = computed<DecoratedEntry[]>(() => {
  if (!parsedDate.value) return []
  const dateStr = parsedDate.value.toDateString()
  const todayStr = new Date().toDateString()
  return journal.entries
    .filter((e: JournalEntry) => new Date(e.worn_at).toDateString() === dateStr)
    .sort((a: JournalEntry, b: JournalEntry) => (a.worn_at < b.worn_at ? 1 : -1))
    .map((entry: JournalEntry) => {
      const d = new Date(entry.worn_at)
      return {
        ...entry,
        isToday: d.toDateString() === todayStr,
        timeLabel: d.toLocaleTimeString('en-GB', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false,
        }),
      }
    })
})
</script>
