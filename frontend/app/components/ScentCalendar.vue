<script setup lang="ts">
import type { JournalEntry } from '~/stores/journal'
import { scentWorld, hashSeed } from '~/utils/scent'
import { familyOfTheHour } from '~/utils/wear'

const props = defineProps<{ entries: JournalEntry[] }>()
const selected = defineModel<string>({ default: '' })

const wardrobe = useWardrobeStore()
const { isDark, worldFor } = useScentWorld()

// A gentle daily tint for the header/accents (no single bottle in scope here).
const houseWorld = worldFor(() => familyOfTheHour())

const today = new Date()
const todayKey = dateKey(today)

// Cursor month — defaults to the selected day's month, else today.
const cursor = ref(new Date(today.getFullYear(), today.getMonth(), 1))
watch(selected, (val) => {
  if (val) {
    const d = parseTimestamp(val)
    cursor.value = new Date(d.getFullYear(), d.getMonth(), 1)
  }
})

const year = computed(() => cursor.value.getFullYear())
const month = computed(() => cursor.value.getMonth())

const entriesByDay = computed(() => {
  const map = new Map<string, JournalEntry[]>()
  for (const e of props.entries) {
    const k = dateKey(e.worn_at)
    const arr = map.get(k)
    if (arr) arr.push(e)
    else map.set(k, [e])
  }
  return map
})

const monthLabel = computed(() =>
  cursor.value.toLocaleDateString([], { month: 'long', year: 'numeric' }),
)

const wearsThisMonth = computed(() => {
  let n = 0
  for (const e of props.entries) {
    const d = parseTimestamp(e.worn_at)
    if (d.getFullYear() === year.value && d.getMonth() === month.value) n++
  }
  return n
})

const WEEKDAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']

const cells = computed(() => {
  const firstOfMonth = new Date(year.value, month.value, 1)
  const lead = (firstOfMonth.getDay() + 6) % 7 // Monday-first offset
  return Array.from({ length: 42 }).map((_, i) => {
    const dayNum = i - lead + 1
    const date = new Date(year.value, month.value, dayNum)
    const key = dateKey(date)
    return {
      key,
      day: date.getDate(),
      inMonth: date.getMonth() === month.value,
      isToday: key === todayKey,
      isFuture: date > today && key !== todayKey,
      wears: entriesByDay.value.get(key) ?? [],
    }
  })
})

// Colour a wear dot from its linked bottle's family (falls back gracefully).
function dotBg(entry: JournalEntry): string {
  const item = entry.wardrobe_item_id ? wardrobe.byId(entry.wardrobe_item_id) : undefined
  const family = item?.family ?? 'woody'
  const seed = hashSeed((item?.id ?? '') + (entry.name ?? ''))
  return scentWorld(family, seed, isDark.value).gradient
}

function prev() {
  cursor.value = new Date(year.value, month.value - 1, 1)
}
function next() {
  cursor.value = new Date(year.value, month.value + 1, 1)
}
function goToday() {
  cursor.value = new Date(today.getFullYear(), today.getMonth(), 1)
  selected.value = todayKey
}
</script>

<template>
  <div>
    <!-- header -->
    <div class="mb-4 flex items-end justify-between gap-3">
      <div>
        <h2 class="fd" style="font-size: clamp(22px, 4vw, 28px);">{{ monthLabel }}</h2>
        <span class="fm" style="font-size: 12px;" :style="{ color: houseWorld.accent }">
          {{ wearsThisMonth }} {{ wearsThisMonth === 1 ? 'wear' : 'wears' }} this month
        </span>
      </div>
      <div class="flex items-center gap-2">
        <button
          type="button"
          class="rounded-full border px-3 py-1.5 fb"
          style="border-color: var(--color-rule); color: var(--color-ink-soft); font-size: 12px;"
          @click="goToday"
        >Today</button>
        <button
          type="button"
          class="flex h-9 w-9 items-center justify-center rounded-full border"
          style="border-color: var(--color-rule); color: var(--color-ink-soft);"
          aria-label="Previous month"
          @click="prev"
        ><Icon name="lucide:chevron-left" size="18" /></button>
        <button
          type="button"
          class="flex h-9 w-9 items-center justify-center rounded-full border"
          style="border-color: var(--color-rule); color: var(--color-ink-soft);"
          aria-label="Next month"
          @click="next"
        ><Icon name="lucide:chevron-right" size="18" /></button>
      </div>
    </div>

    <!-- weekday header -->
    <div class="mb-2 grid grid-cols-7 gap-1.5">
      <div
        v-for="w in WEEKDAYS"
        :key="w"
        class="fm text-center uppercase"
        style="font-size: 9px; letter-spacing: 0.12em; color: var(--color-ink-mute);"
      >{{ w }}</div>
    </div>

    <!-- day grid -->
    <div class="grid grid-cols-7 gap-1.5">
      <button
        v-for="cell in cells"
        :key="cell.key"
        type="button"
        class="flex aspect-square flex-col items-center justify-start rounded-[13px] border p-1.5 text-center"
        :style="{
          borderColor: selected === cell.key ? houseWorld.accent : 'transparent',
          background: selected === cell.key
            ? 'var(--color-surface)'
            : cell.wears.length ? 'var(--color-surface-2)' : 'transparent',
          opacity: cell.inMonth ? (cell.isFuture ? 0.4 : 1) : 0.28,
          cursor: 'pointer',
        }"
        @click="selected = cell.key"
      >
        <span
          class="fm"
          style="font-size: 12px;"
          :style="{ color: cell.isToday ? houseWorld.accent : 'var(--color-ink-soft)' }"
        >{{ cell.day }}</span>
        <div class="mt-auto flex flex-wrap items-center justify-center gap-0.5 pb-0.5">
          <span
            v-for="(w, i) in cell.wears.slice(0, 3)"
            :key="w.id"
            class="inline-block rounded-full"
            :style="{ width: '7px', height: '7px', background: dotBg(w) }"
          />
          <span
            v-if="cell.wears.length > 3"
            class="fm"
            style="font-size: 8px; color: var(--color-ink-mute);"
          >+{{ cell.wears.length - 3 }}</span>
        </div>
      </button>
    </div>
  </div>
</template>
