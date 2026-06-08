<script setup lang="ts">
import { MOODS, OCCASIONS, WEATHERS, LONGEVITY } from '~/utils/wear'
import type { Longevity } from '~/stores/journal'

const logStore = useLogStore()
const journal = useJournalStore()
const toast = useToast()
const { worldFor } = useScentWorld()

const open = computed({
  get: () => logStore.open,
  set: (v) => { if (!v) logStore.close() },
})

const item = computed(() => logStore.item)
const world = worldFor(() => item.value?.family ?? 'woody', () => (item.value ? item.value.id + item.value.name : 'x'))

// Local form state, reset each time a new bottle opens the modal.
const sprays = ref(2)
const mood = ref<string | null>(null)
const occasion = ref<string | null>(null)
const weather = ref<string | null>(null)
const longevity = ref<Longevity | null>(null)
const note = ref('')
const saving = ref(false)
const done = ref(false)

watch(() => logStore.open, (isOpen) => {
  if (isOpen) {
    sprays.value = 2
    mood.value = null
    occasion.value = null
    weather.value = null
    longevity.value = null
    note.value = ''
    saving.value = false
    done.value = false
  }
})

const longevityHint = computed(() => LONGEVITY.find((l) => l.value === longevity.value)?.hint ?? '')

function pick<T>(current: T | null, value: T): T | null {
  return current === value ? null : value
}

async function submit() {
  if (!item.value || saving.value) return
  saving.value = true
  try {
    await journal.log({
      wardrobe_item_id: item.value.id,
      brand: item.value.brand,
      name: item.value.name,
      sprays: sprays.value,
      mood: mood.value,
      occasion: occasion.value,
      weather: weather.value,
      longevity: longevity.value,
      experience: note.value.trim() || undefined,
    })
    done.value = true
    toast.success(`Logged ${item.value.name} · ${sprays.value} ${sprays.value === 1 ? 'spray' : 'sprays'}`)
    setTimeout(() => logStore.close(), 550)
  } catch {
    toast.error('Could not log that wear. Try again.')
    saving.value = false
  }
}
</script>

<template>
  <AppModal v-model="open" :max-width="860" label="Log a wear">
    <div v-if="item" class="grid md:grid-cols-[320px_1fr]">
      <!-- gradient spray panel -->
      <div
        class="flex flex-col items-center justify-center gap-6 p-8"
        :style="{ background: world.bloom, color: world.onGrad }"
      >
        <div class="text-center">
          <div class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; opacity: 0.8;">{{ item.brand }}</div>
          <div class="fd mt-1" style="font-size: 26px;">{{ item.name }}</div>
        </div>
        <SprayControl v-model="sprays" :world="world" />
        <p class="fb text-center italic" style="font-size: 12px; opacity: 0.85;">Tap to spray. Each tap counts a press.</p>
      </div>

      <!-- form -->
      <div class="p-6 md:p-8">
        <div class="kicker">Log a wear</div>
        <h3 class="fd mt-1" style="font-size: 24px;">How did it sit today?</h3>

        <div class="mt-5 space-y-5">
          <div>
            <div class="fb mb-2" style="font-size: 13px; color: var(--color-ink-soft);">Mood</div>
            <div class="flex flex-wrap gap-2">
              <ScentChip
                v-for="m in MOODS"
                :key="m"
                :active="mood === m"
                :world="world"
                @click="mood = pick(mood, m)"
              >{{ m }}</ScentChip>
            </div>
          </div>

          <div>
            <div class="fb mb-2" style="font-size: 13px; color: var(--color-ink-soft);">Occasion</div>
            <div class="flex flex-wrap gap-2">
              <ScentChip
                v-for="o in OCCASIONS"
                :key="o"
                :active="occasion === o"
                :world="world"
                @click="occasion = pick(occasion, o)"
              >{{ o }}</ScentChip>
            </div>
          </div>

          <div>
            <div class="fb mb-2" style="font-size: 13px; color: var(--color-ink-soft);">Weather</div>
            <div class="flex flex-wrap gap-2">
              <ScentChip
                v-for="w in WEATHERS"
                :key="w"
                :active="weather === w"
                :world="world"
                @click="weather = pick(weather, w)"
              >{{ w }}</ScentChip>
            </div>
          </div>

          <div>
            <div class="fb mb-2" style="font-size: 13px; color: var(--color-ink-soft);">Longevity</div>
            <div class="flex flex-wrap gap-2">
              <ScentChip
                v-for="l in LONGEVITY"
                :key="l.value"
                :active="longevity === l.value"
                :world="world"
                @click="longevity = (longevity === l.value ? null : l.value)"
              >{{ l.label }}</ScentChip>
            </div>
            <p v-if="longevityHint" class="fb mt-2 italic" style="font-size: 12px; color: var(--color-ink-mute);">{{ longevityHint }}</p>
          </div>

          <div>
            <div class="fb mb-2" style="font-size: 13px; color: var(--color-ink-soft);">Note</div>
            <textarea
              v-model="note"
              rows="3"
              placeholder="How does it sit with you today?"
              class="w-full resize-none rounded-field border px-3.5 py-2.5 fb"
              style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink); font-size: 14px;"
            />
          </div>
        </div>

        <button
          type="button"
          class="mt-6 flex w-full items-center justify-center gap-2 rounded-full py-3.5 disabled:opacity-60"
          :style="{ background: world.gradient, color: world.onGrad }"
          :disabled="saving"
          @click="submit"
        >
          <Icon v-if="done" name="lucide:check" size="18" />
          <Icon v-else name="lucide:spray-can" size="18" />
          <span class="fb" style="font-size: 15px; font-weight: 700;">{{ done ? 'Logged' : 'Log this wear' }}</span>
        </button>
      </div>
    </div>
  </AppModal>
</template>
