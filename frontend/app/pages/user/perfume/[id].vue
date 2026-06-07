<template>
  <div>
    <div class="max-w-5xl mx-auto">
      <!-- Back link -->
      <button
        type="button"
        class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft hover:text-ink transition-colors"
        @click="goBack"
      >
        <Icon name="lucide:arrow-left" size="14" />
        Back
      </button>

      <template v-if="loading">
        <p class="mt-20 text-center font-display italic text-ink-soft">Drawing from the cabinet…</p>
      </template>

      <template v-else-if="!perfume">
        <p class="mt-20 text-center font-display italic text-ink-soft">
          That bottle isn't on file.
        </p>
      </template>

      <template v-else>
        <!-- Hero -->
        <header class="mt-8 grid grid-cols-1 md:grid-cols-[220px_1fr] gap-10 md:gap-14 border-y border-ink relative py-10">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />

          <!-- Bottle image or placeholder -->
          <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center overflow-hidden">
            <img
              v-if="perfume.image"
              :src="perfume.image"
              :alt="`${perfume.brand} ${perfume.name}`"
              class="w-full h-full object-contain"
            >
            <BottleIcon v-else :size="120" />
          </div>

          <div>
            <p class="font-display italic text-[15px] text-ink-soft leading-tight">
              {{ perfume.brand }}
            </p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl text-ink tracking-[-0.005em] leading-none">
              {{ perfume.name }}
            </h1>

            <!-- Meta line -->
            <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              <template v-if="perfume.year">{{ perfume.year }}</template>
              <template v-if="perfume.country">
                <span class="text-accent-deep mx-1">·</span>{{ perfume.country }}
              </template>
              <template v-if="perfume.suit">
                <span class="text-accent-deep mx-1">·</span>{{ perfume.suit }}
              </template>
              <template v-if="perfume.rating">
                <span class="text-accent-deep mx-1">·</span>{{ perfume.rating.toFixed(2) }}
                <span class="text-ink-mute ml-1">({{ perfume.votes }} votes)</span>
              </template>
            </p>

            <!-- Main accord chips -->
            <div v-if="accordChips.length" class="mt-6 flex flex-wrap gap-2">
              <span
                v-for="chip in accordChips"
                :key="chip"
                class="px-3 py-1.5 font-mono text-[9px] uppercase tracking-[0.14em] text-ink bg-paper-deep border border-rule"
              >
                {{ chip }}
              </span>
            </div>

            <!-- History / editorial description -->
            <p
              v-if="perfume.history"
              class="mt-6 pt-5 border-t border-rule font-display italic text-[15px] text-ink-soft leading-normal max-w-prose"
            >
              {{ perfume.history }}
            </p>

            <!-- Perfumers -->
            <p v-if="perfume.perfumers" class="mt-5 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              <span class="text-accent-deep mr-1.5">/</span>
              By {{ perfume.perfumers }}
            </p>

            <!-- Actions -->
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
              <NuxtLink
                v-if="ownedItem"
                :to="`/user/wardrobe/${ownedItem.id}`"
                class="inline-block bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors"
              >
                Show in wardrobe
              </NuxtLink>
              <NuxtLink
                v-else
                :to="`/user/wardrobe/add?catalog_id=${perfume.id}`"
                class="inline-block bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors"
              >
                Add to wardrobe
              </NuxtLink>
              <p v-if="ownedItem" class="font-display italic text-[14px] text-accent-deep">
                You already own this.
              </p>
            </div>
          </div>
        </header>

        <!-- Notes pyramid -->
        <section v-if="hasAnyNotes" class="mt-16">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-2">
            The pyramid
          </p>
          <h2 class="font-display text-3xl text-ink tracking-tight">
            Notes <em class="text-ink-soft">it leaves on you.</em>
          </h2>

          <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-px bg-rule border border-rule">
            <article v-if="perfume.top_notes" class="bg-paper p-8">
              <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep">
                Top
              </p>
              <h3 class="mt-2 font-display text-[18px] text-ink leading-tight">
                What you smell first
              </h3>
              <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.14em] text-ink-soft leading-relaxed">
                {{ formatNotes(perfume.top_notes) }}
              </p>
            </article>

            <article v-if="perfume.middle_notes" class="bg-paper p-8">
              <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep">
                Heart
              </p>
              <h3 class="mt-2 font-display text-[18px] text-ink leading-tight">
                What it settles into
              </h3>
              <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.14em] text-ink-soft leading-relaxed">
                {{ formatNotes(perfume.middle_notes) }}
              </p>
            </article>

            <article v-if="perfume.base_notes" class="bg-paper p-8">
              <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep">
                Base
              </p>
              <h3 class="mt-2 font-display text-[18px] text-ink leading-tight">
                What lingers on skin
              </h3>
              <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.14em] text-ink-soft leading-relaxed">
                {{ formatNotes(perfume.base_notes) }}
              </p>
            </article>
          </div>
        </section>

        <!-- When to wear -->
        <section v-if="hasWearBars" class="mt-16">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-2">
            Climate
          </p>
          <h2 class="font-display text-3xl text-ink tracking-tight">
            When it <em class="text-ink-soft">wears best.</em>
          </h2>

          <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
            <div>
              <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-ink-mute mb-5">
                Season
              </p>
              <div class="space-y-4">
                <div v-for="season in seasons" :key="season.label">
                  <div class="flex items-baseline justify-between mb-1.5">
                    <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink">{{ season.label }}</span>
                    <span class="font-mono text-[10px] text-ink-mute">{{ season.value }}%</span>
                  </div>
                  <div class="h-px bg-rule relative overflow-hidden">
                    <div
                      class="absolute inset-y-0 left-0 bg-ink transition-all duration-700"
                      :style="{ width: `${season.value}%` }"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div>
              <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-ink-mute mb-5">
                Time of day
              </p>
              <div class="space-y-4">
                <div v-for="time in times" :key="time.label">
                  <div class="flex items-baseline justify-between mb-1.5">
                    <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink">{{ time.label }}</span>
                    <span class="font-mono text-[10px] text-ink-mute">{{ time.value }}%</span>
                  </div>
                  <div class="h-px bg-rule relative overflow-hidden">
                    <div
                      class="absolute inset-y-0 left-0 bg-ink transition-all duration-700"
                      :style="{ width: `${time.value}%` }"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <p v-if="perfume.suit_season || perfume.suit_time" class="mt-10 font-display italic text-[14px] text-ink-soft">
            <template v-if="perfume.suit_season">
              Best worn through {{ perfume.suit_season.toLowerCase() }}
            </template>
            <template v-if="perfume.suit_season && perfume.suit_time">, </template>
            <template v-if="perfume.suit_time">
              suited to the {{ perfume.suit_time.toLowerCase() }}
            </template>.
          </p>
        </section>

        <!-- Fragrantica reference -->
        <div v-if="perfume.source_url" class="mt-16 pt-8 border-t border-rule flex justify-center sm:justify-end">
          <a
            :href="perfume.source_url"
            target="_blank"
            rel="noopener noreferrer"
            class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          >
            Learn more about this perfume &rarr;
          </a>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'app', middleware: 'auth' })

type Bar = { label: string; value: number }

const route = useRoute()
const router = useRouter()
const api = useApi()
const wardrobe = useWardrobeStore()

const perfume = ref<any>(null)
const loading = ref(true)

const ownedItem = computed(() =>
  perfume.value
    ? wardrobe.items.find(i => i.catalog_id === perfume.value.id) ?? null
    : null,
)

const accordChips = computed<string[]>(() => {
  const raw = perfume.value?.main_accord
  if (!raw || typeof raw !== 'string') return []
  return raw.split(',').map((s: string) => s.trim()).filter(Boolean)
})

const formatNotes = (raw: string) =>
  raw.split(',').map((s: string) => s.trim()).filter(Boolean).join(' · ')

const hasAnyNotes = computed(
  () => perfume.value?.top_notes || perfume.value?.middle_notes || perfume.value?.base_notes,
)

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
  () =>
    seasons.value.some((s: Bar) => s.value > 0)
    || times.value.some((t: Bar) => t.value > 0),
)

const goBack = () => {
  if (window.history.length > 1) router.back()
  else router.push('/user/perfume')
}

onMounted(async () => {
  try {
    perfume.value = await api.get(`/perfume/${route.params.id}`)
  } catch {
    // perfume stays null → "not on file" state shown
  } finally {
    loading.value = false
  }
})
</script>
