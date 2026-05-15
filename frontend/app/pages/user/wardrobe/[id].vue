<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Back -->
      <NuxtLink
        to="/user/wardrobe"
        class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-soft hover:text-ink transition-colors"
      >
        <Icon name="lucide:arrow-left" size="14" />
        Back to your shelf
      </NuxtLink>

      <template v-if="!item">
        <p class="mt-20 text-center font-display italic text-ink-soft">
          That bottle isn't on your shelf.
        </p>
      </template>

      <template v-else>
        <!-- Title block (top-left) -->
        <header class="mt-8 border-y border-ink relative py-8">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />
          <p class="font-display italic text-[16px] text-ink-soft leading-tight">
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
            <template v-if="item.catalog_id">
              <span class="text-accent-deep mx-1">·</span>
              <NuxtLink :to="`/user/perfume/${item.catalog_id}`" class="text-ink-soft hover:text-ink transition-colors">
                Catalog details &rarr;
              </NuxtLink>
            </template>
          </p>
        </header>

        <!-- Diary spread -->
        <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
          <!-- ─── LEFT: bottle + start-wear / today's diary ─── -->
          <section class="lg:border-r lg:border-rule lg:pr-12">
            <!-- Bottle, centered + bigger -->
            <div class="flex justify-center">
              <div class="aspect-3/4 w-64 bg-paper-deep border border-rule flex items-center justify-center">
                <BottleIcon :size="180" />
              </div>
            </div>

            <!-- Acquired-time personal notes (the line user wrote when adding) -->
            <p
              v-if="item.notes"
              class="mt-8 font-display italic text-[15px] text-ink-soft leading-normal text-center max-w-md mx-auto"
            >
              &ldquo;{{ item.notes }}&rdquo;
            </p>

            <!-- ① Not yet worn today → primary CTA right under the bottle -->
            <div v-if="!todayEntry" class="mt-10 flex justify-center">
              <button
                type="button"
                class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-8 py-3.5 hover:bg-ink-soft transition-colors"
                @click="startWear"
              >
                I'm wearing this
              </button>
            </div>

            <!-- ② Already wearing today → status indicator -->
            <div v-else class="mt-10 text-center">
              <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-accent-deep">
                On your skin
              </p>
              <p class="mt-2 font-display italic text-[15px] text-ink-soft">
                Since {{ wearStartedAt }}
              </p>
            </div>

            <!-- ③ Today's diary — appears only when today's entry exists -->
            <Transition
              enter-active-class="transition duration-400 ease-out"
              enter-from-class="opacity-0 translate-y-2"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition duration-200 ease-in"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <div v-if="todayEntry" class="mt-12 pt-10 border-t border-rule">
                <!-- Heading + Edit toggle -->
                <div class="flex items-baseline justify-between gap-4">
                  <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep">
                    Today's diary
                  </p>
                  <button
                    v-if="!isEditingDiary"
                    type="button"
                    class="font-display italic text-[13px] text-ink hover:text-accent-deep pb-px border-b border-accent transition-colors"
                    @click="editDiary"
                  >
                    Edit
                  </button>
                </div>

                <!-- Edit mode -->
                <form
                  v-if="isEditingDiary"
                  class="mt-4"
                  @submit.prevent="saveDiary"
                >
                  <h2 class="font-display text-2xl text-ink tracking-tight">
                    <em class="text-ink-soft">Write</em> as it unfolds.
                  </h2>
                  <p class="mt-2 font-display italic text-[13px] text-ink-mute">
                    Come back through the day — log what others said, how long it lasted, how it sat.
                  </p>

                  <div class="mt-8 space-y-6">
                    <div>
                      <label
                        for="experience"
                        class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2"
                      >
                        Experience
                      </label>
                      <textarea
                        id="experience"
                        v-model="form.experience"
                        rows="3"
                        placeholder="How does it sit with you?"
                        class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors resize-none"
                      />
                    </div>

                    <div>
                      <label
                        for="compliments"
                        class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-2"
                      >
                        Compliments
                      </label>
                      <input
                        id="compliments"
                        v-model="form.compliments"
                        type="text"
                        placeholder="What did others say?"
                        class="w-full bg-paper-deep border border-rule px-4 py-2.5 text-[14px] text-ink placeholder:font-display placeholder:italic placeholder:text-ink-mute focus:outline-none focus:border-ink-soft transition-colors"
                      >
                    </div>

                    <div>
                      <p class="block font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
                        Longevity
                      </p>
                      <div class="flex flex-wrap gap-2">
                        <button
                          v-for="opt in longevityOptions"
                          :key="opt.value"
                          type="button"
                          class="px-4 py-2 font-mono text-[10px] uppercase tracking-[0.14em] border transition-colors"
                          :class="form.longevity === opt.value
                            ? 'bg-accent-soft border-accent text-accent-deep font-medium'
                            : 'bg-paper-deep border-rule text-ink hover:border-ink-soft'"
                          @click="toggleLongevity(opt.value)"
                        >
                          {{ opt.label }}
                        </button>
                      </div>
                      <p class="mt-2 font-display italic text-[12px] text-ink-mute">
                        {{ longevityHint }}
                      </p>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-2">
                      <button
                        v-if="hasDiaryContent"
                        type="button"
                        class="font-display italic text-[13px] text-ink-soft hover:text-ink transition-colors"
                        @click="cancelEdit"
                      >
                        Cancel
                      </button>
                      <span v-else />
                      <button
                        type="submit"
                        class="bg-ink text-paper text-[11px] uppercase tracking-[0.2em] font-medium px-6 py-3 hover:bg-ink-soft transition-colors"
                      >
                        Save diary
                      </button>
                    </div>
                  </div>
                </form>

                <!-- View mode (saved diary, read-only) -->
                <div v-else class="mt-6">
                  <p
                    v-if="todayEntry.experience"
                    class="font-display italic text-[16px] text-ink-soft leading-normal"
                  >
                    &ldquo;{{ todayEntry.experience }}&rdquo;
                  </p>

                  <p
                    v-if="todayEntry.compliments"
                    class="mt-4 flex items-baseline gap-3 font-display italic text-[14px] text-ink-soft"
                  >
                    <span class="font-mono not-italic text-[8px] uppercase tracking-[0.18em] text-accent-deep shrink-0">
                      Heard
                    </span>
                    &ldquo;{{ todayEntry.compliments }}&rdquo;
                  </p>

                  <div v-if="todayEntry.longevity" class="mt-5">
                    <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-ink-mute mb-2">
                      Longevity
                    </p>
                    <span class="inline-block px-3 py-1.5 font-mono text-[10px] uppercase tracking-[0.14em] bg-accent-soft border border-accent text-accent-deep font-medium">
                      {{ longevityLabel(todayEntry.longevity) }}
                    </span>
                  </div>

                </div>
              </div>
            </Transition>
          </section>

          <!-- ─── RIGHT: wear log timeline ─── -->
          <section>
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

            <ul v-if="wears.length > 0" class="border-t border-rule">
              <li
                v-for="entry in wears"
                :key="entry.id"
                class="border-b border-rule py-6"
                :class="{ 'bg-paper-deep -mx-4 px-4': entry.isToday }"
              >
                <div class="flex items-baseline justify-between mb-3 gap-3">
                  <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink">
                    <template v-if="entry.isToday">
                      <span class="text-accent-deep">Today</span>
                      <span class="text-ink-mute ml-1">{{ entry.timeLabel }}</span>
                    </template>
                    <template v-else>
                      {{ entry.dayName }}
                      <span class="text-accent-deep mx-1">·</span>
                      {{ entry.dayNum }} {{ entry.monthLabel }}
                      <span class="text-ink-mute ml-1">{{ entry.timeLabel }}</span>
                    </template>
                  </p>
                  <span
                    v-if="entry.longevity"
                    class="shrink-0 px-2.5 py-1 font-mono text-[8px] uppercase tracking-[0.16em] bg-accent-soft border border-accent text-accent-deep"
                  >
                    {{ longevityLabel(entry.longevity) }}
                  </span>
                </div>

                <!-- Experience (or legacy notes) -->
                <p
                  v-if="entry.experience || entry.notes"
                  class="font-display italic text-[15px] text-ink-soft leading-normal"
                >
                  &ldquo;{{ entry.experience ?? entry.notes }}&rdquo;
                </p>
                <p
                  v-else
                  class="font-display italic text-[13px] text-ink-mute"
                >
                  <template v-if="entry.isToday">
                    Diary blank — write something on the left as the day unfolds.
                  </template>
                  <template v-else>
                    Worn, no words.
                  </template>
                </p>

                <!-- Compliments -->
                <p
                  v-if="entry.compliments"
                  class="mt-3 flex items-baseline gap-3 font-display italic text-[14px] text-ink-soft"
                >
                  <span class="font-mono not-italic text-[8px] uppercase tracking-[0.18em] text-accent-deep shrink-0">
                    Heard
                  </span>
                  &ldquo;{{ entry.compliments }}&rdquo;
                </p>
              </li>
            </ul>

            <p
              v-else
              class="font-display italic text-[15px] text-ink-soft py-8 border-t border-rule"
            >
              Never worn yet — press <em>I'm wearing this</em> on the left to begin.
            </p>
          </section>
        </div>

        <!-- Remove from shelf -->
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
import type { Longevity } from '~/stores/journal'

definePageMeta({ middleware: 'auth' })

const route = useRoute()
const router = useRouter()
const wardrobe = useWardrobeStore()
const journal = useJournalStore()
const toast = useToast()

const id = computed(() => String(route.params.id))
const item = computed(() => wardrobe.byId(id.value) ?? null)

const longevityOptions: Array<{ value: Longevity; label: string; hint: string }> = [
  { value: 'brief', label: 'Brief', hint: 'Faded in an hour or two.' },
  { value: 'settled', label: 'Settled', hint: 'Held through the afternoon.' },
  { value: 'lasting', label: 'Lasting', hint: 'Carried most of the day.' },
  { value: 'all-day', label: 'All day', hint: 'Still there at dinner.' },
  { value: 'into-night', label: 'Into the night', hint: 'Found it again on the pillow.' },
]

const longevityLabel = (v: Longevity) =>
  longevityOptions.find(o => o.value === v)?.label ?? ''

const isSameDay = (iso: string) => {
  const a = parseTimestamp(iso)
  const b = new Date()
  return (
    a.getFullYear() === b.getFullYear()
    && a.getMonth() === b.getMonth()
    && a.getDate() === b.getDate()
  )
}

// The wear started today for this item, if any. The diary section binds to it.
const todayEntry = computed(() =>
  journal.entries.find(e => e.wardrobe_item_id === id.value && isSameDay(e.worn_at)) ?? null,
)

const wearStartedAt = computed(() => {
  if (!todayEntry.value) return ''
  return formatTime(todayEntry.value.worn_at)
})

const form = reactive({
  experience: '',
  compliments: '',
  longevity: null as Longevity | null,
})

// Sync the form from today's entry whenever it appears or changes (e.g. after save,
// or if user reloads later in the day and the entry already has prior values).
watch(
  todayEntry,
  (entry) => {
    form.experience = entry?.experience ?? ''
    form.compliments = entry?.compliments ?? ''
    form.longevity = entry?.longevity ?? null
  },
  { immediate: true },
)

// Does today's entry have anything written in it yet?
const hasDiaryContent = computed(() => {
  const e = todayEntry.value
  if (!e) return false
  return Boolean(e.experience || e.compliments || e.longevity)
})

// Edit mode is on when the user explicitly hit "Edit" OR the entry is fresh
// (no fields saved yet — first time landing in the form right after starting a wear).
const editingOverride = ref(false)
const isEditingDiary = computed(() =>
  Boolean(todayEntry.value) && (!hasDiaryContent.value || editingOverride.value),
)

const longevityHint = computed(() => {
  if (!form.longevity) return 'How long did it sit on you?'
  return longevityOptions.find(o => o.value === form.longevity)?.hint ?? ''
})

const toggleLongevity = (v: Longevity) => {
  form.longevity = form.longevity === v ? null : v
}

const startWear = async () => {
  if (!item.value || todayEntry.value) return
  try {
    await journal.log({
      wardrobe_item_id: item.value.id,
      brand: item.value.brand,
      name: item.value.name,
    })
    toast.success('Logged to your diary.')
  } catch {
    toast.error('Could not log the wear — please try again.')
  }
}

const saveDiary = async () => {
  if (!todayEntry.value) return
  try {
    await journal.update(todayEntry.value.id, {
      experience: form.experience.trim() || undefined,
      compliments: form.compliments.trim() || undefined,
      longevity: form.longevity ?? undefined,
    })
    editingOverride.value = false
    toast.success('Diary saved.')
  } catch {
    toast.error('Could not save the diary — please try again.')
  }
}

const editDiary = () => {
  editingOverride.value = true
}

const cancelEdit = () => {
  // Re-sync form from the saved entry and exit edit mode.
  const entry = todayEntry.value
  if (entry) {
    form.experience = entry.experience ?? ''
    form.compliments = entry.compliments ?? ''
    form.longevity = entry.longevity ?? null
  }
  editingOverride.value = false
}

const wearCount = computed(() => journal.wearCount(id.value))

const lastWornLabel = computed(() => {
  const iso = journal.lastWornAt(id.value)
  if (!iso) return ''
  const days = Math.floor(
    (Date.now() - new Date(iso).getTime()) / (1000 * 60 * 60 * 24),
  )
  if (days === 0) return 'today'
  if (days === 1) return 'yesterday'
  if (days < 30) return `${days} days ago`
  const months = Math.floor(days / 30)
  return `${months} ${months === 1 ? 'month' : 'months'} ago`
})

const wears = computed(() => {
  return journal.entries
    .filter(e => e.wardrobe_item_id === id.value)
    .sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1))
    .map((entry) => {
      const d = parseTimestamp(entry.worn_at)
      return {
        ...entry,
        isToday: isSameDay(entry.worn_at),
        dayName: d.toLocaleDateString('en-GB', { weekday: 'short' }).toUpperCase(),
        dayNum: String(d.getDate()).padStart(2, '0'),
        monthLabel: d.toLocaleDateString('en-GB', { month: 'short', year: 'numeric' }).toUpperCase(),
        timeLabel: formatTime(entry.worn_at),
      }
    })
})

const confirmRemove = async () => {
  if (!item.value) return
  // eslint-disable-next-line no-alert
  if (!window.confirm(`Remove ${item.value.brand} ${item.value.name} from your shelf?`)) return
  try {
    await wardrobe.remove(item.value.id)
    toast.success('Removed from your shelf.')
    router.push('/user/wardrobe')
  } catch {
    toast.error('Could not remove the bottle — please try again.')
  }
}
</script>
