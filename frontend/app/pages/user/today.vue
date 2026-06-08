<script setup lang="ts">
import { scentWorld, hashSeed, FAMILIES, type ScentFamilyKey } from '~/utils/scent'
import { greetingWord, familyOfTheHour, pickOfTheDay } from '~/utils/wear'
import type { JournalEntry } from '~/stores/journal'

definePageMeta({ layout: 'app', middleware: 'auth' })
useHead({ title: 'Today' })

const auth = useAuthStore()
const wardrobe = useWardrobeStore()
const journal = useJournalStore()
const logStore = useLogStore()
const { worldFor, isDark } = useScentWorld()

const now = new Date()
const houseWorld = worldFor(() => familyOfTheHour(now))

const firstName = computed(() => {
  const raw = auth.user?.name
  if (typeof raw !== 'string' || !raw.trim()) return 'friend'
  const f = raw.trim().split(/\s+/)[0] ?? 'friend'
  return f.charAt(0).toUpperCase() + f.slice(1)
})

const dateLabel = computed(() =>
  now.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'long' }).toUpperCase(),
)

const pick = computed(() => pickOfTheDay(wardrobe.items, (id) => journal.wearCount(id), now))
const shelfPreview = computed(() => wardrobe.items.slice(0, 4))
const recent = computed(() => journal.sorted.slice(0, 5))

// This-month stats for the web right rail.
const wearsThisMonth = computed(() => {
  let n = 0
  for (const e of journal.entries) {
    const d = parseTimestamp(e.worn_at)
    if (d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth()) n++
  }
  return n
})

const mostWornFamily = computed<ScentFamilyKey | null>(() => {
  const tally = {} as Record<string, number>
  for (const e of journal.entries) {
    const item = e.wardrobe_item_id ? wardrobe.byId(e.wardrobe_item_id) : undefined
    const fam = item?.family
    if (fam) tally[fam] = (tally[fam] ?? 0) + 1
  }
  let best: ScentFamilyKey | null = null
  let bestN = 0
  for (const [fam, n] of Object.entries(tally)) {
    if (n > bestN) { bestN = n; best = fam as ScentFamilyKey }
  }
  return best
})
const mostWornWorld = computed(() => (mostWornFamily.value ? scentWorld(mostWornFamily.value, 0.5, isDark.value) : null))

function entryWorld(e: JournalEntry) {
  const item = e.wardrobe_item_id ? wardrobe.byId(e.wardrobe_item_id) : undefined
  const fam = item?.family ?? 'woody'
  return scentWorld(fam, hashSeed((item?.id ?? '') + e.name), isDark.value)
}

const knowledge = useDailyKnowledge(now)

function openBottle(id: string) { navigateTo(`/user/wardrobe/${id}`) }
function logBottle(item: typeof wardrobe.items[number]) { logStore.start(item) }
</script>

<template>
  <div>
    <!-- greeting -->
    <header class="mb-7 flex items-start justify-between gap-4">
      <div>
        <h1 class="fd" style="font-size: clamp(28px, 6vw, 40px); line-height: 1.05;">
          Good {{ greetingWord(now) }}, {{ firstName }}<span :style="{ color: houseWorld.accent }">.</span>
        </h1>
        <p class="fm mt-2 uppercase" style="font-size: 11px; letter-spacing: 0.16em; color: var(--color-ink-mute);">{{ dateLabel }}</p>
      </div>
    </header>

    <div class="lg:grid lg:grid-cols-[minmax(0,1fr)_340px] lg:gap-8">
      <!-- ── left column ───────────────────────────────────────────────── -->
      <div class="min-w-0 space-y-10">
        <!-- today's pick / empty -->
        <section>
          <TodayPickHero
            v-if="pick"
            :item="pick"
            size="large"
            @open="openBottle(pick.id)"
            @log="logBottle(pick)"
          />
          <div
            v-else
            class="rounded-hero border p-8 text-center"
            style="border-color: var(--color-rule); background: var(--color-surface);"
          >
            <h2 class="fd" style="font-size: 28px;">Your shelf is bare.</h2>
            <p class="fb mt-2 italic" style="font-size: 14px; color: var(--color-ink-soft);">Add a bottle to start a daily pick and a wear journal.</p>
            <NuxtLink
              to="/user/wardrobe/add"
              class="mt-5 inline-flex items-center gap-2 rounded-full px-5 py-2.5"
              :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
            >
              <Icon name="lucide:plus" size="16" /><span class="fb" style="font-weight: 700;">Add a bottle</span>
            </NuxtLink>
          </div>
        </section>

        <!-- your shelf -->
        <section v-if="wardrobe.count">
          <header class="mb-4 flex items-baseline justify-between">
            <h2 class="fd" style="font-size: 22px;">Your shelf</h2>
            <NuxtLink to="/user/wardrobe" class="fb italic" style="font-size: 13px; color: var(--color-ink-soft);">All {{ wardrobe.count }} →</NuxtLink>
          </header>
          <p class="fb mb-4 hidden italic md:block lg:hidden" style="font-size: 12px; color: var(--color-ink-mute);">Swipe a bottle left to log a wear.</p>
          <div class="grid grid-cols-2 gap-3">
            <ShelfCard
              v-for="item in shelfPreview"
              :key="item.id"
              :item="item"
              @open="openBottle($event.id)"
              @log="logBottle"
            />
          </div>
        </section>

        <!-- lately (mobile strip) -->
        <section v-if="recent.length" class="lg:hidden">
          <h2 class="fd mb-4" style="font-size: 22px;">Lately</h2>
          <div class="-mx-5 flex gap-3 overflow-x-auto px-5 pb-2">
            <button
              v-for="e in recent"
              :key="e.id"
              type="button"
              class="w-44 shrink-0 rounded-panel border p-4 text-left"
              style="border-color: var(--color-rule); background: var(--color-surface);"
              @click="navigateTo(`/user/journal/${dateKey(e.worn_at)}`)"
            >
              <span class="block h-1.5 w-12 rounded-full" :style="{ background: entryWorld(e).gradient }" />
              <span class="fm mt-3 block uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">{{ relativeDay(e.worn_at) }}</span>
              <span class="fd mt-1 block leading-tight" style="font-size: 16px;">{{ e.name }}</span>
              <span class="fb mt-1 block" style="font-size: 12px; color: var(--color-ink-soft);">{{ e.mood || '—' }} · {{ e.sprays ?? '—' }} {{ e.sprays === 1 ? 'spray' : 'sprays' }}</span>
            </button>
          </div>
        </section>

        <!-- field notes — one perfumery fact per day, bridges into the almanac -->
        <section v-if="knowledge">
          <header class="mb-4">
            <div class="kicker" :style="{ color: houseWorld.accent }">Field notes</div>
            <h2 class="fd mt-1" style="font-size: 22px;">One thing about perfume.</h2>
          </header>
          <article class="rounded-panel border p-6 md:p-7" style="border-color: var(--color-rule); background: var(--color-surface);">
            <div class="kicker" :style="{ color: houseWorld.accent }">{{ knowledge.kicker }}</div>
            <h3 class="fd mt-2" style="font-size: 22px;">{{ knowledge.title }}</h3>
            <p class="fb mt-3 italic" style="font-size: 15px; line-height: 1.65; color: var(--color-ink-soft);">{{ knowledge.body }}</p>
            <NuxtLink to="/user/almanac" class="mt-4 inline-flex items-center gap-2 fb italic" style="font-size: 14px; color: var(--color-ink);">
              Learn more in the almanac <Icon name="lucide:arrow-right" size="14" />
            </NuxtLink>
          </article>
        </section>
      </div>

      <!-- ── right rail (web) ──────────────────────────────────────────── -->
      <aside class="hidden lg:block">
        <div class="sticky top-7 space-y-6">
          <section class="rounded-panel border p-5" style="border-color: var(--color-rule); background: var(--color-surface);">
            <div class="kicker">This month</div>
            <div class="mt-3 flex items-end justify-between">
              <div>
                <div class="fd leading-none" style="font-size: 40px;">{{ wearsThisMonth }}</div>
                <div class="fb mt-1" style="font-size: 12px; color: var(--color-ink-soft);">{{ wearsThisMonth === 1 ? 'wear logged' : 'wears logged' }}</div>
              </div>
              <div v-if="mostWornWorld" class="text-right">
                <div class="kicker mb-1.5">Most worn</div>
                <div class="inline-flex items-center gap-2">
                  <ScentOrb :world="mostWornWorld" :size="14" />
                  <span class="fb" style="font-size: 13px;">{{ mostWornWorld.family.label }}</span>
                </div>
              </div>
            </div>
          </section>

          <section v-if="recent.length" class="rounded-panel border p-5" style="border-color: var(--color-rule); background: var(--color-surface);">
            <div class="kicker mb-3">Lately</div>
            <ul class="space-y-3">
              <li v-for="e in recent" :key="e.id">
                <button
                  type="button"
                  class="flex w-full items-center gap-3 text-left"
                  @click="navigateTo(`/user/journal/${dateKey(e.worn_at)}`)"
                >
                  <span class="h-9 w-1.5 shrink-0 rounded-full" :style="{ background: entryWorld(e).gradient }" />
                  <span class="min-w-0 flex-1">
                    <span class="fd block truncate" style="font-size: 15px;">{{ e.name }}</span>
                    <span class="fb block" style="font-size: 11px; color: var(--color-ink-mute);">{{ relativeDay(e.worn_at) }} · {{ e.mood || 'logged' }}</span>
                  </span>
                </button>
              </li>
            </ul>
          </section>
        </div>
      </aside>
    </div>
  </div>
</template>
