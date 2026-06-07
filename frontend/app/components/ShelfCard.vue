<script setup lang="ts">
import type { WardrobeItem } from '~/stores/wardrobe'

const props = defineProps<{ item: WardrobeItem }>()
const emit = defineEmits<{ open: [item: WardrobeItem]; log: [item: WardrobeItem] }>()

const journal = useJournalStore()
const { worldFor } = useScentWorld()

const world = worldFor(() => props.item.family ?? 'woody', () => props.item.id + props.item.name)
const wears = computed(() => journal.wearCount(props.item.id))
const lastWorn = computed(() => journal.lastWornAt(props.item.id))

// ── Swipe-to-log (mobile / pointer) ────────────────────────────────────────
const THRESHOLD = 78
const dragX = ref(0)
const dragging = ref(false)
let startX = 0
let pointerId: number | null = null
let moved = false

function onDown(e: PointerEvent) {
  // Only engage for touch / pen; mouse uses the hover Log button on web.
  if (e.pointerType === 'mouse') return
  startX = e.clientX
  pointerId = e.pointerId
  dragging.value = true
  moved = false
}
function onMove(e: PointerEvent) {
  if (!dragging.value || e.pointerId !== pointerId) return
  const dx = e.clientX - startX
  if (Math.abs(dx) > 4) moved = true
  dragX.value = Math.max(-110, Math.min(0, dx)) // left-only
}
function onUp(e: PointerEvent) {
  if (!dragging.value || e.pointerId !== pointerId) return
  dragging.value = false
  pointerId = null
  if (dragX.value <= -THRESHOLD) {
    emit('log', props.item)
  }
  dragX.value = 0 // spring back
}

function onClick() {
  if (moved) return // was a swipe, not a tap
  emit('open', props.item)
}
</script>

<template>
  <div class="relative overflow-hidden rounded-panel">
    <!-- action layer revealed by a left swipe -->
    <div
      class="absolute inset-0 flex items-center justify-end pr-6"
      :style="{ background: world.gradient, color: world.onGrad }"
      aria-hidden="true"
    >
      <span class="inline-flex items-center gap-1.5 fm uppercase" style="font-size: 11px; letter-spacing: 0.14em;">
        <Icon name="lucide:spray-can" size="18" /> Log
      </span>
    </div>

    <!-- the card -->
    <div
      class="group relative cursor-pointer rounded-panel border p-4 hover:-translate-y-1"
      :class="dragging ? '' : 'transition-transform duration-300 ease-[cubic-bezier(.2,.8,.2,1)]'"
      :style="{
        transform: `translateX(${dragX}px)`,
        background: 'var(--color-surface)',
        borderColor: 'var(--color-rule)',
        boxShadow: dragX === 0 ? 'none' : `0 14px 30px -20px ${world.c2}`,
      }"
      role="button"
      tabindex="0"
      @pointerdown="onDown"
      @pointermove="onMove"
      @pointerup="onUp"
      @pointercancel="onUp"
      @click="onClick"
      @keydown.enter="emit('open', item)"
    >
      <div class="flex items-start justify-between">
        <ScentOrb :world="world" :size="14" />
        <ScentFlacon :world="world" :size="40" :glow="false" />
      </div>

      <div class="mt-3">
        <div class="kicker">{{ item.brand }}</div>
        <h3 class="fd mt-0.5 leading-tight" style="font-size: 20px;">{{ item.name }}</h3>
        <p
          v-if="item.tagline"
          class="fb mt-1 line-clamp-2 italic"
          style="font-size: 12.5px; color: var(--color-ink-soft);"
        >{{ item.tagline }}</p>
      </div>

      <div class="mt-4 flex items-center justify-between border-t pt-3" style="border-color: var(--color-rule);">
        <span class="fm" style="font-size: 12px; color: var(--color-ink-mute);">{{ wears }} {{ wears === 1 ? 'wear' : 'wears' }}</span>

        <!-- web: hover-to-log button; mobile: last-worn text -->
        <button
          type="button"
          class="hidden items-center gap-1.5 rounded-full px-3 py-1 md:inline-flex"
          :style="{ background: world.soft, color: world.accent }"
          @click.stop="emit('log', item)"
        >
          <Icon name="lucide:spray-can" size="14" />
          <span class="fb" style="font-size: 12px;">Log</span>
        </button>
        <span
          class="fb md:hidden"
          style="font-size: 12px;"
          :style="{ color: lastWorn ? world.accent : 'var(--color-ink-mute)' }"
        >{{ lastWorn ? relativeDay(lastWorn) : 'not worn yet' }}</span>
      </div>
    </div>
  </div>
</template>
