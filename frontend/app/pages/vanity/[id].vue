<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
      <!-- Back -->
      <NuxtLink
        to="/vanity"
        class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft hover:text-ink transition-colors"
      >
        <Icon name="lucide:arrow-left" size="14" />
        Back to your shelf
      </NuxtLink>

      <template v-if="!item">
        <p class="mt-20 text-center font-display italic text-ink-soft">
          That bottle isn't on your shelf.
        </p>
        <div class="mt-8 text-center">
          <NuxtLink
            to="/vanity"
            class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          >
            Return to the shelf
          </NuxtLink>
        </div>
      </template>

      <template v-else>
        <!-- Hero -->
        <header class="mt-8 grid grid-cols-1 md:grid-cols-[220px_1fr] gap-10 md:gap-14 border-y border-ink relative py-10">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />

          <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center">
            <BottleIcon :size="120" />
          </div>

          <div>
            <p class="font-display italic text-[15px] text-ink-soft leading-tight">
              {{ item.brand }}
            </p>
            <h1 class="mt-1 font-display text-4xl sm:text-5xl text-ink tracking-[-0.005em] leading-none">
              {{ item.name }}
            </h1>

            <p class="mt-4 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ item.size }} ml
              <template v-if="item.acquired">
                <span class="text-accent-deep mx-1">·</span>Acquired {{ item.acquired }}
              </template>
            </p>

            <!-- Personal notes -->
            <p
              v-if="item.notes"
              class="mt-6 pt-5 border-t border-rule font-display italic text-[15px] text-ink-soft leading-normal max-w-prose"
            >
              &ldquo;{{ item.notes }}&rdquo;
            </p>

            <!-- Actions -->
            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
              <button
                type="button"
                :disabled="wearLogged"
                class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors disabled:opacity-60 disabled:cursor-default"
                @click="wearThis"
              >
                {{ wearLogged ? 'Worn ✓' : "I'm wearing this" }}
              </button>
              <button
                v-if="item.catalog_id"
                type="button"
                class="font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
                @click="viewCatalog"
              >
                Catalog details &rarr;
              </button>
            </div>
          </div>
        </header>

        <!-- Wear log section -->
        <section class="mt-16">
          <div class="flex items-baseline justify-between mb-6">
            <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep">
              Wear log
            </p>
            <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
              {{ String(wearCount).padStart(2, '0') }} {{ wearCount === 1 ? 'wear' : 'wears' }}
              <template v-if="lastWornLabel">
                <span class="text-accent-deep mx-1">·</span>{{ lastWornLabel }}
              </template>
            </p>
          </div>

          <template v-if="wears.length > 0">
            <ul class="border-t border-rule">
              <li
                v-for="entry in wears"
                :key="entry.id"
                class="border-b border-rule py-5 grid grid-cols-[80px_1fr] sm:grid-cols-[120px_1fr] gap-6 items-center"
              >
                <div>
                  <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
                    {{ entry.dayName }}
                  </p>
                  <p class="font-mono text-[22px] text-ink leading-none mt-1">
                    {{ entry.dayNum }}
                  </p>
                  <p class="font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute mt-1">
                    {{ entry.monthLabel }} &middot; {{ entry.timeLabel }}
                  </p>
                </div>
                <p class="font-display italic text-[14px] text-ink-soft leading-normal">
                  <template v-if="entry.notes">{{ entry.notes }}</template>
                  <template v-else>Worn.</template>
                </p>
              </li>
            </ul>
          </template>

          <p
            v-else
            class="font-display italic text-[15px] text-ink-soft py-8 border-t border-rule"
          >
            Never worn yet — log your first wear with the button above.
          </p>
        </section>

        <!-- Remove -->
        <section class="mt-16 pt-6 border-t border-rule flex flex-wrap items-center justify-between gap-4">
          <p class="font-display italic text-[13px] text-ink-mute max-w-md">
            Removing takes the bottle off your shelf. Journal entries stay where they are.
          </p>
          <button
            type="button"
            class="font-display italic text-[14px] text-ink-soft hover:text-accent-deep pb-1 border-b border-accent transition-colors"
            @click="confirmRemove"
          >
            Remove from shelf
          </button>
        </section>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const route = useRoute()
const router = useRouter()
const vanity = useVanityStore()
const journal = useJournalStore()

const id = computed(() => String(route.params.id))
const item = computed(() => vanity.byId(id.value) ?? null)

const wearLogged = ref(false)

const wearCount = computed(() => journal.wearCount(id.value))

const lastWornLabel = computed(() => {
  const iso = journal.lastWornAt(id.value)
  if (!iso) return ''
  const days = Math.floor(
    (Date.now() - new Date(iso).getTime()) / (1000 * 60 * 60 * 24),
  )
  if (days === 0) return 'Worn today'
  if (days === 1) return 'Worn yesterday'
  if (days < 30) return `Worn ${days} days ago`
  const months = Math.floor(days / 30)
  if (months < 12) return `Worn ${months} ${months === 1 ? 'month' : 'months'} ago`
  return 'Worn over a year ago'
})

const wears = computed(() => {
  return journal.entries
    .filter(e => e.vanity_item_id === id.value)
    .sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1))
    .map((entry) => {
      const d = new Date(entry.worn_at)
      return {
        ...entry,
        dayName: d.toLocaleDateString('en-GB', { weekday: 'short' }).toUpperCase(),
        dayNum: String(d.getDate()).padStart(2, '0'),
        monthLabel: d.toLocaleDateString('en-GB', { month: 'short', year: 'numeric' }).toUpperCase(),
        timeLabel: d.toLocaleTimeString('en-GB', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false,
        }),
      }
    })
})

const wearThis = () => {
  if (!item.value || wearLogged.value) return
  journal.log({
    vanity_item_id: item.value.id,
    brand: item.value.brand,
    name: item.value.name,
  })
  wearLogged.value = true
}

const viewCatalog = () => {
  if (item.value?.catalog_id) router.push(`/perfume/${item.value.catalog_id}`)
}

const confirmRemove = () => {
  if (!item.value) return
  // eslint-disable-next-line no-alert
  if (!window.confirm(`Remove ${item.value.brand} ${item.value.name} from your shelf?`)) return
  vanity.remove(item.value.id)
  router.push('/vanity')
}
</script>
