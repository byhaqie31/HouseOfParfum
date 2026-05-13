<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
      <!-- Greeting -->
      <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-ink-mute leading-[1.7]">
        Good {{ partOfDay }}, {{ firstName || 'friend' }}
        <span class="text-accent-deep mx-1">·</span>
        {{ formattedDate }}
      </p>

      <!-- Section divider with gold accent -->
      <div class="relative border-b border-ink mt-4 mb-10">
        <div class="absolute -bottom-px left-0 w-20 h-px bg-accent" />
      </div>

      <!-- Empty vanity → invite to add -->
      <template v-if="vanity.count === 0">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
          Today
        </p>
        <div class="border-y border-ink relative py-12 px-2">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <h2 class="font-display text-3xl sm:text-4xl text-ink tracking-tight leading-tight max-w-xl">
            Your shelf is bare<em class="text-ink-soft">.</em>
          </h2>
          <p class="mt-4 font-display italic text-[15px] text-ink-soft max-w-md">
            Add a bottle to your vanity and the daily pick begins.
          </p>
          <NuxtLink
            to="/vanity/add"
            class="mt-8 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-6 py-3 hover:bg-ink-soft transition-colors"
          >
            Add a bottle
            <Icon name="lucide:arrow-right" size="14" />
          </NuxtLink>
        </div>
      </template>

      <!-- Today's pick + glance -->
      <template v-else-if="todayPick">
        <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
          Today
        </p>

        <section class="grid grid-cols-1 sm:grid-cols-[140px_1fr] gap-8 sm:gap-10 border-b border-ink pb-10">
          <!-- Bottle frame -->
          <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center">
            <BottleIcon :size="64" />
          </div>

          <div>
            <p class="font-display italic text-[15px] text-ink-soft leading-tight">
              {{ todayPick.brand }}
            </p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl tracking-[-0.005em] leading-none">
              <NuxtLink
                :to="`/vanity/${todayPick.id}`"
                class="text-ink hover:text-accent-deep transition-colors"
              >
                {{ todayPick.name }}
              </NuxtLink>
            </h1>
            <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ todayPick.size }} ML
              <template v-if="todayPick.acquired"> &middot; {{ todayPick.acquired }}</template>
            </p>

            <p class="mt-5 pt-4 border-t border-rule font-display italic text-[15px] text-ink-soft leading-normal">
              {{ pickReason }}
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
              <button
                type="button"
                :disabled="wearLogged"
                class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors disabled:opacity-60 disabled:cursor-default"
                @click="wearThis"
              >
                {{ wearLogged ? 'Worn ✓' : "I'm wearing this" }}
              </button>
              <button
                v-if="candidates.length > 1"
                type="button"
                class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
                @click="showAnother"
              >
                Show me another
              </button>
            </div>
          </div>
        </section>

        <!-- Your Vanity glance -->
        <section class="mt-12">
          <header class="flex items-baseline justify-between mb-6">
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.24em] text-ink-mute">
              <span class="font-mono text-accent-deep mr-2">/</span>Your vanity
            </p>
            <NuxtLink
              to="/vanity"
              class="font-display italic text-[12px] text-ink-soft hover:text-ink transition-colors"
            >
              View all {{ vanity.count }} &rarr;
            </NuxtLink>
          </header>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <NuxtLink
              v-for="item in vanityGlance"
              :key="item.id"
              :to="`/vanity/${item.id}`"
              class="group aspect-3/4 bg-paper-deep border border-rule p-3 flex flex-col hover:bg-paper transition-colors duration-200"
            >
              <div class="flex-1 flex items-center justify-center">
                <BottleIcon :size="44" />
              </div>
              <p class="font-mono text-[7px] uppercase tracking-[0.14em] text-ink-mute leading-snug">
                {{ item.brand }}
              </p>
              <p class="font-display text-[11px] text-ink group-hover:text-accent-deep leading-[1.15] mt-0.5 transition-colors">
                {{ item.name }}
              </p>
            </NuxtLink>
          </div>
        </section>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const vanity = useVanityStore()
const journal = useJournalStore()

const now = new Date()

const formattedDate = computed(() =>
  now
    .toLocaleDateString('en-GB', {
      weekday: 'long',
      day: 'numeric',
      month: 'long',
    })
    .toUpperCase(),
)

const partOfDay = computed(() => {
  const h = now.getHours()
  if (h < 12) return 'morning'
  if (h < 18) return 'afternoon'
  return 'evening'
})

const firstName = computed(() => {
  const raw = auth.user?.name
  if (typeof raw !== 'string') return ''
  return raw.split(' ')[0]?.toLowerCase() ?? ''
})

// Sort candidates: never-worn first, then least-recently-worn.
const candidates = computed(() => {
  return [...vanity.items].sort((a, b) => {
    const la = journal.lastWornAt(a.id)
    const lb = journal.lastWornAt(b.id)
    if (!la && !lb) return 0
    if (!la) return -1
    if (!lb) return 1
    return la < lb ? -1 : la > lb ? 1 : 0
  })
})

const pickIndex = ref(0)
const wearLogged = ref(false)

const todayPick = computed(() => candidates.value[pickIndex.value] ?? null)

// Newest-added first for the glance grid (independent of pick ordering).
const vanityGlance = computed(() => vanity.items.slice(0, 4))

const pickReason = computed(() => {
  if (!todayPick.value) return ''
  const last = journal.lastWornAt(todayPick.value.id)
  if (!last) return 'Newly on the shelf — try it today.'
  const days = Math.floor(
    (Date.now() - new Date(last).getTime()) / (1000 * 60 * 60 * 24),
  )
  if (days === 0) return 'You wore this earlier today.'
  if (days === 1) return 'Worn yesterday — give it another day.'
  if (days < 7) return `You haven't worn this in ${days} days.`
  if (days < 30)
    return `You haven't reached for this in ${days} days — give it some air.`
  if (days < 365) {
    const months = Math.floor(days / 30)
    return `It's been ${months} ${months === 1 ? 'month' : 'months'} since you last wore this.`
  }
  return "It's been over a year since you last wore this."
})

const wearThis = () => {
  if (!todayPick.value || wearLogged.value) return
  journal.log({
    vanity_item_id: todayPick.value.id,
    brand: todayPick.value.brand,
    name: todayPick.value.name,
  })
  wearLogged.value = true
}

const showAnother = () => {
  if (candidates.value.length === 0) return
  pickIndex.value = (pickIndex.value + 1) % candidates.value.length
  wearLogged.value = false
}
</script>
