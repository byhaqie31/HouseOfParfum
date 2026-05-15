<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Header -->
      <header class="border-b border-rule pb-8">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          Your wear log
        </p>
        <h1 class="mt-3 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
          Journal
        </h1>
      </header>

      <!-- Calendar spread -->
      <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12">
        <!-- ─── LEFT 2/3: calendar grid ─── -->
        <section class="lg:col-span-2">
          <!-- Month nav -->
          <div class="flex items-baseline justify-between mb-6">
            <p class="font-display text-2xl text-ink tracking-tight">
              {{ monthLabelTitle }}
              <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute align-middle ml-3">
                {{ String(monthWearCount).padStart(2, '0') }} {{ monthWearCount === 1 ? 'Perfume' : 'Perfumes' }}
              </span>
            </p>
            <div class="flex items-center gap-2">
              <button
                type="button"
                class="h-9 px-4 flex items-center justify-center border border-rule bg-paper-deep font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                @click="jumpToToday"
              >
                Today
              </button>
              <button
                type="button"
                aria-label="Previous month"
                class="w-9 h-9 flex items-center justify-center border border-rule bg-paper-deep text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                @click="shiftMonth(-1)"
              >
                <Icon name="lucide:chevron-left" size="16" />
              </button>
              <button
                type="button"
                aria-label="Next month"
                class="w-9 h-9 flex items-center justify-center border border-rule bg-paper-deep text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                @click="shiftMonth(1)"
              >
                <Icon name="lucide:chevron-right" size="16" />
              </button>
            </div>
          </div>

          <!-- Weekday labels -->
          <div class="grid grid-cols-7 gap-px bg-rule border border-rule">
            <div
              v-for="day in weekdayLabels"
              :key="day"
              class="bg-paper-deep py-2 text-center font-mono text-[9px] uppercase tracking-[0.18em] text-ink-mute"
            >
              {{ day }}
            </div>
          </div>

          <!-- Day cells -->
          <div class="grid grid-cols-7 gap-px bg-rule border-x border-b border-rule">
            <button
              v-for="(cell, i) in cells"
              :key="i"
              type="button"
              class="bg-paper aspect-square p-2 text-left flex flex-col justify-between transition-colors cursor-pointer"
              :class="cellClasses(cell)"
              @click="selectCell(cell)"
            >
              <div class="flex items-start justify-between gap-1">
                <span
                  class="font-mono text-[11px] tracking-[0.04em]"
                  :class="cell.inMonth ? 'text-ink' : 'text-ink-mute opacity-50'"
                >
                  {{ cell.dayNum }}
                </span>
                <span
                  v-if="cell.isToday"
                  class="w-1 h-1 mt-1.5 rounded-full bg-accent shrink-0"
                  aria-label="Today"
                />
              </div>
              <div v-if="cell.wears.length" class="flex items-baseline justify-end">
                <span class="font-mono text-[9px] uppercase tracking-[0.14em] text-accent-deep font-medium">
                  <span class="sm:hidden">{{ cell.wears.length }}P</span>
                  <span class="hidden sm:inline">{{ cell.wears.length }} {{ cell.wears.length === 1 ? 'Perfume' : 'Perfumes' }}</span>
                </span>
              </div>
            </button>
          </div>
        </section>

        <!-- ─── RIGHT 1/3: selected day details ─── -->
        <aside>
          <div class="mb-6">
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep">
              <template v-if="selectedIsToday">Today</template>
              <template v-else>This day</template>
            </p>
            <h2 class="mt-1 font-display text-3xl text-ink tracking-tight leading-tight">
              {{ selectedDayHeadline }}
            </h2>
            <p class="mt-2 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ String(selectedDayWears.length).padStart(2, '0') }} {{ selectedDayWears.length === 1 ? 'Perfume' : 'Perfumes' }} logged
            </p>
          </div>

          <ul v-if="visibleSelectedDayWears.length > 0" class="border-t border-rule">
            <li
              v-for="entry in visibleSelectedDayWears"
              :key="entry.id"
              class="border-b border-rule"
            >
              <NuxtLink
                v-if="entry.wardrobe_item_id"
                :to="`/wardrobe/${entry.wardrobe_item_id}`"
                class="group block py-5 -mx-3 px-3 hover:bg-paper-deep transition-colors cursor-pointer"
              >
                <div class="flex items-baseline justify-between gap-2 mb-2">
                  <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft">
                    {{ entry.timeLabel }}
                  </p>
                  <span
                    v-if="entry.longevity"
                    class="shrink-0 px-2 py-0.5 font-mono text-[8px] uppercase tracking-[0.16em] bg-accent-soft border border-accent text-accent-deep"
                  >
                    {{ longevityLabel(entry.longevity) }}
                  </span>
                </div>

                <p class="font-display italic text-[13px] text-ink-soft leading-tight">
                  {{ entry.brand }}
                </p>
                <h3 class="mt-0.5 font-display text-xl text-ink leading-tight group-hover:text-accent-deep transition-colors">
                  {{ entry.name }}
                </h3>

                <p
                  v-if="entry.experience || entry.notes"
                  class="mt-3 font-display italic text-[14px] text-ink-soft leading-normal"
                >
                  &ldquo;{{ entry.experience ?? entry.notes }}&rdquo;
                </p>

                <p
                  v-if="entry.compliments"
                  class="mt-2 flex items-baseline gap-2 font-display italic text-[13px] text-ink-soft"
                >
                  <span class="font-mono not-italic text-[8px] uppercase tracking-[0.18em] text-accent-deep shrink-0">
                    Heard
                  </span>
                  &ldquo;{{ entry.compliments }}&rdquo;
                </p>

                <p class="mt-3 font-display italic text-[12px] text-ink group-hover:text-accent-deep border-b border-accent inline-block pb-px transition-colors">
                  Open diary &rarr;
                </p>
              </NuxtLink>

              <!-- Orphan entry without a wardrobe item — kept inert -->
              <div v-else class="block py-5">
                <div class="flex items-baseline justify-between gap-2 mb-2">
                  <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft">
                    {{ entry.timeLabel }}
                  </p>
                  <span
                    v-if="entry.longevity"
                    class="shrink-0 px-2 py-0.5 font-mono text-[8px] uppercase tracking-[0.16em] bg-accent-soft border border-accent text-accent-deep"
                  >
                    {{ longevityLabel(entry.longevity) }}
                  </span>
                </div>
                <p class="font-display italic text-[13px] text-ink-soft leading-tight">
                  {{ entry.brand }}
                </p>
                <h3 class="mt-0.5 font-display text-xl text-ink leading-tight">
                  {{ entry.name }}
                </h3>
                <p
                  v-if="entry.experience || entry.notes"
                  class="mt-3 font-display italic text-[14px] text-ink-soft leading-normal"
                >
                  &ldquo;{{ entry.experience ?? entry.notes }}&rdquo;
                </p>
              </div>
            </li>
          </ul>

          <p
            v-else-if="journal.count === 0"
            class="font-display italic text-[15px] text-ink-soft py-8 border-t border-rule"
          >
            Nothing in the journal yet. Log a wear from your shelf and it'll show up here.
          </p>

          <p
            v-else
            class="font-display italic text-[15px] text-ink-soft py-8 border-t border-rule"
          >
            <template v-if="selectedIsToday">
              No wears yet today.
            </template>
            <template v-else>
              Nothing logged on this day.
            </template>
          </p>

          <!-- View timeline → day detail. Always visible when day has wears -->
          <div v-if="selectedDayWears.length > 0" class="mt-6 flex items-center justify-between gap-3">
            <p v-if="hiddenCount > 0" class="font-display italic text-[13px] text-ink-mute">
              {{ hiddenCount }} more {{ hiddenCount === 1 ? 'wear' : 'wears' }} on this day.
            </p>
            <span v-else />
            <NuxtLink
              :to="`/journal/${dateParam}`"
              class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-px border-b border-accent transition-colors"
            >
              View timeline &rarr;
            </NuxtLink>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { JournalEntry, Longevity } from '~/stores/journal'

definePageMeta({ middleware: 'auth' })

const journal = useJournalStore()

onMounted(() => { journal.load() })

// Week starts Monday for editorial / international feel.
const weekdayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']

const longevityLabels: Record<Longevity, string> = {
  brief: 'Brief',
  settled: 'Settled',
  lasting: 'Lasting',
  'all-day': 'All day',
  'into-night': 'Into the night',
}
const longevityLabel = (v: Longevity) => longevityLabels[v] ?? ''

const isSameDay = (a: Date, b: Date) =>
  a.getFullYear() === b.getFullYear()
  && a.getMonth() === b.getMonth()
  && a.getDate() === b.getDate()

// Month being displayed (always the 1st of that month).
const cursor = ref(new Date(new Date().getFullYear(), new Date().getMonth(), 1))
const selectedDate = ref(new Date())

const monthLabelTitle = computed(() =>
  cursor.value.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }),
)

// Group all entries by toDateString() for O(1) cell lookup.
const wearsByDay = computed(() => {
  const map = new Map<string, JournalEntry[]>()
  for (const e of journal.entries) {
    const key = parseTimestamp(e.worn_at).toDateString()
    if (!map.has(key)) map.set(key, [])
    map.get(key)!.push(e)
  }
  // Newest first within a day
  for (const [, arr] of map) {
    arr.sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1))
  }
  return map
})

type Cell = {
  date: Date
  dayNum: string
  inMonth: boolean
  isToday: boolean
  isSelected: boolean
  wears: JournalEntry[]
}

// Build the 42-cell (6 weeks × 7 days) grid, Monday-first.
const cells = computed<Cell[]>(() => {
  const first = new Date(cursor.value)
  const dow = first.getDay() // 0 = Sun
  const offsetToMonday = dow === 0 ? 6 : dow - 1
  const start = new Date(first)
  start.setDate(first.getDate() - offsetToMonday)

  const today = new Date()
  const result: Cell[] = []
  for (let i = 0; i < 42; i++) {
    const d = new Date(start)
    d.setDate(start.getDate() + i)
    result.push({
      date: d,
      dayNum: String(d.getDate()).padStart(2, '0'),
      inMonth: d.getMonth() === cursor.value.getMonth(),
      isToday: isSameDay(d, today),
      isSelected: isSameDay(d, selectedDate.value),
      wears: wearsByDay.value.get(d.toDateString()) ?? [],
    })
  }
  return result
})

const monthWearCount = computed(() =>
  cells.value.reduce((n: number, c: Cell) => (c.inMonth ? n + c.wears.length : n), 0),
)

const cellClasses = (cell: Cell) => {
  const out: string[] = []
  if (cell.isSelected) {
    out.push('bg-accent-soft ring-1 ring-accent ring-inset')
  } else if (cell.wears.length) {
    out.push('hover:bg-paper-deep')
  } else {
    out.push('hover:bg-paper-deep')
  }
  if (!cell.inMonth) out.push('opacity-50')
  return out.join(' ')
}

const selectCell = (cell: Cell) => {
  selectedDate.value = new Date(cell.date)
  // Jump month if the user clicked an out-of-month cell
  if (!cell.inMonth) {
    cursor.value = new Date(cell.date.getFullYear(), cell.date.getMonth(), 1)
  }
}

const shiftMonth = (delta: number) => {
  const next = new Date(cursor.value)
  next.setMonth(next.getMonth() + delta, 1)
  cursor.value = next
}

const jumpToToday = () => {
  const t = new Date()
  cursor.value = new Date(t.getFullYear(), t.getMonth(), 1)
  selectedDate.value = t
}

const selectedIsToday = computed(() => isSameDay(selectedDate.value, new Date()))

const selectedDayHeadline = computed(() => {
  // e.g. "Wed, 13 May"
  return selectedDate.value.toLocaleDateString('en-GB', {
    weekday: 'short',
    day: 'numeric',
    month: 'long',
  })
})

const selectedDayWears = computed(() => {
  const raw = wearsByDay.value.get(selectedDate.value.toDateString()) ?? []
  return raw.map((entry: JournalEntry) => ({
    ...entry,
    timeLabel: formatTime(entry.worn_at),
  }))
})

// Cap the right column to the 3 most recent wears for the day; everything
// else lives on /journal/[date] reachable via "View timeline →".
const PREVIEW_LIMIT = 3
const visibleSelectedDayWears = computed(() =>
  selectedDayWears.value.slice(0, PREVIEW_LIMIT),
)
const hiddenCount = computed(() =>
  Math.max(0, selectedDayWears.value.length - PREVIEW_LIMIT),
)

// Date param used in the timeline URL — YYYY-MM-DD.
const dateParam = computed(() => {
  const d = selectedDate.value
  return [
    d.getFullYear(),
    String(d.getMonth() + 1).padStart(2, '0'),
    String(d.getDate()).padStart(2, '0'),
  ].join('-')
})
</script>
