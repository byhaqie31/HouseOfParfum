<script setup lang="ts">
import {
  scentWorld, bottleSeed, FAMILIES, FAMILY_ORDER, type ScentFamilyKey,
} from '~/utils/scent'
import type { RegistryPerfume, CuratePayload } from '~/types/admin'

const props = defineProps<{ perfume: RegistryPerfume | null; saving?: boolean }>()
const emit = defineEmits<{ close: []; save: [payload: CuratePayload] }>()

const { isDark } = useScentWorld()

// Local edit state, (re)hydrated whenever a different row opens.
const family = ref<ScentFamilyKey | null>(null)
const notesInput = reactive({ top: '', heart: '', base: '' })
const image = ref('')
const history = ref('')

watch(() => props.perfume, (p) => {
  if (!p) return
  family.value = p.family ?? null
  notesInput.top = (p.notes?.top ?? []).join(', ')
  notesInput.heart = (p.notes?.heart ?? []).join(', ')
  notesInput.base = (p.notes?.base ?? []).join(', ')
  image.value = p.image ?? ''
  history.value = p.history ?? ''
}, { immediate: true })

// The header previews the colour world live — picking a family instantly recolours it.
const world = computed(() =>
  family.value && props.perfume
    ? scentWorld(family.value, bottleSeed(String(props.perfume.id), props.perfume.name), isDark.value)
    : null,
)
const familyChips = computed(() =>
  FAMILY_ORDER.map((k) => ({ key: k, label: FAMILIES[k].label, world: scentWorld(k, 0.5, isDark.value) })),
)

const tiers = [
  { key: 'top' as const, en: 'Top notes', my: 'Nota atas' },
  { key: 'heart' as const, en: 'Heart notes', my: 'Nota tengah' },
  { key: 'base' as const, en: 'Base notes', my: 'Nota dasar' },
]

function parse(v: string): string[] {
  return v.split(',').map((s) => s.trim()).filter(Boolean)
}

function save() {
  if (!props.perfume || props.saving) return
  emit('save', {
    id: props.perfume.id,
    family: family.value,
    notes: { top: parse(notesInput.top), heart: parse(notesInput.heart), base: parse(notesInput.base) },
    image: image.value.trim() || null,
    history: history.value.trim() || null,
  })
}

function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape' && props.perfume) emit('close')
}
onMounted(() => window.addEventListener('keydown', onKey))
onBeforeUnmount(() => window.removeEventListener('keydown', onKey))
</script>

<template>
  <Teleport to="body">
    <Transition name="drawer">
      <div
        v-if="perfume"
        class="fixed inset-0 z-[120] flex justify-end"
        style="background: color-mix(in oklab, var(--color-canvas) 30%, rgba(0,0,0,0.5)); backdrop-filter: blur(5px);"
        role="dialog"
        aria-modal="true"
        @click.self="emit('close')"
      >
        <div
          class="drawer-panel flex h-full w-[480px] max-w-[92vw] flex-col overflow-y-auto border-l"
          style="background: var(--color-canvas); border-color: var(--color-rule); box-shadow: -30px 0 80px -30px rgba(0,0,0,0.4);"
        >
          <!-- Live colour-world preview header -->
          <div
            class="px-7 py-[30px]"
            :style="world
              ? { background: world.bloom, color: world.onGrad }
              : { background: 'var(--color-surface-2)', color: 'var(--color-ink-soft)' }"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="min-w-0">
                <div class="fm uppercase" style="font-size: 10px; letter-spacing: 0.16em; opacity: 0.85;">{{ perfume.brand }}</div>
                <div class="fd mt-1.5 truncate" style="font-size: 32px; line-height: 1.08; font-weight: 600;">{{ perfume.name }}</div>
                <div class="fm mt-2" style="font-size: 10px; letter-spacing: 0.1em; opacity: 0.85;">
                  {{ perfume.year ?? '—' }} · {{ world ? world.family.label : 'No family yet' }}
                </div>
              </div>
              <ScentFlacon v-if="world" :world="world!" :size="54" />
              <span
                v-else
                class="shrink-0 rounded-[18px] border"
                style="width: 54px; height: 74px; border: 1.5px dashed currentColor; opacity: 0.5;"
              />
            </div>
          </div>

          <div class="px-7 pb-10 pt-[26px]">
            <!-- Scent family — the keystone -->
            <div class="flex items-baseline justify-between">
              <span class="fm uppercase" style="font-size: 9px; letter-spacing: 0.14em; color: var(--color-ink-mute);">Scent family</span>
              <span
                class="fb italic"
                :style="{ fontSize: '10px', color: world ? 'var(--color-ink-mute)' : 'oklch(0.6 0.15 25)' }"
              >{{ world ? 'generates the colour' : 'required for colour' }}</span>
            </div>
            <div class="mt-2.5 flex flex-wrap gap-2">
              <ScentChip
                v-for="chip in familyChips"
                :key="chip.key"
                :active="family === chip.key"
                :world="chip.world"
                @click="family = chip.key"
              >{{ chip.label }}</ScentChip>
            </div>

            <!-- Notes pyramid -->
            <div class="mt-6">
              <span class="fm uppercase" style="font-size: 9px; letter-spacing: 0.14em; color: var(--color-ink-mute);">Notes pyramid</span>
              <div class="fb mt-0.5 italic" style="font-size: 11px; color: var(--color-ink-mute);">Comma separated. Dipisahkan dengan koma.</div>
              <div class="mt-2.5 flex flex-col gap-3">
                <div v-for="t in tiers" :key="t.key">
                  <div class="flex items-baseline justify-between">
                    <span class="fb" style="font-size: 12px; font-weight: 600; color: var(--color-ink-soft);">{{ t.en }}</span>
                    <span class="fb italic" style="font-size: 10px; color: var(--color-ink-mute);">{{ t.my }}</span>
                  </div>
                  <input
                    v-model="notesInput[t.key]"
                    class="admin-input fb mt-1.5"
                    placeholder="e.g. Bergamot, Lime, Neroli"
                  >
                </div>
              </div>
            </div>

            <!-- Editorial fields -->
            <div class="mt-6 flex flex-col gap-3.5">
              <div>
                <span class="fm uppercase" style="font-size: 9px; letter-spacing: 0.14em; color: var(--color-ink-mute);">Image URL</span>
                <input v-model="image" class="admin-input fb mt-1.5" placeholder="https://…">
              </div>
              <div>
                <span class="fm uppercase" style="font-size: 9px; letter-spacing: 0.14em; color: var(--color-ink-mute);">History</span>
                <textarea v-model="history" rows="4" class="admin-input fb mt-1.5 resize-none" placeholder="The story behind this scent…" />
              </div>
            </div>

            <div class="mt-7 flex gap-3">
              <button
                type="button"
                class="flex flex-1 items-center justify-center gap-2.5 rounded-[13px] py-3.5 disabled:opacity-60"
                :style="world
                  ? { background: world.gradient, color: world.onGrad }
                  : { background: 'var(--color-ink)', color: 'var(--color-canvas)' }"
                :disabled="saving"
                @click="save"
              >
                <Icon :name="saving ? 'lucide:loader-circle' : 'lucide:check'" size="17" :class="{ 'animate-spin': saving }" />
                <span class="fm uppercase" style="font-size: 11px; letter-spacing: 0.14em;">{{ saving ? 'Saving' : 'Save' }}</span>
              </button>
              <button
                type="button"
                class="fm rounded-[13px] border px-5 py-3.5 uppercase"
                style="border-color: var(--color-rule); background: transparent; color: var(--color-ink-soft); font-size: 11px; letter-spacing: 0.12em;"
                @click="emit('close')"
              >Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.admin-input {
  width: 100%;
  background: var(--color-surface);
  border: 1px solid var(--color-rule);
  border-radius: 10px;
  padding: 10px 12px;
  font-size: 13px;
  color: var(--color-ink);
  outline: none;
}
.admin-input::placeholder { color: var(--color-ink-mute); }
.admin-input:focus { border-color: var(--color-accent); }

.drawer-enter-active, .drawer-leave-active { transition: opacity 0.26s ease; }
.drawer-enter-from, .drawer-leave-to { opacity: 0; }
.drawer-enter-active .drawer-panel, .drawer-leave-active .drawer-panel {
  transition: transform 0.3s cubic-bezier(.2,.8,.2,1);
}
.drawer-enter-from .drawer-panel, .drawer-leave-to .drawer-panel { transform: translateX(100%); }

@media (prefers-reduced-motion: reduce) {
  .drawer-enter-active, .drawer-leave-active,
  .drawer-enter-active .drawer-panel, .drawer-leave-active .drawer-panel { transition: none; }
}
</style>
