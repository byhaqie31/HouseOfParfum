<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = withDefaults(defineProps<{
  world: ScentWorld
  size?: number
  glow?: boolean
}>(), {
  size: 64,
  glow: true,
})

// Bottle proportions — width `size`, height `size * 1.36` (handoff Flacon spec).
const h = computed(() => props.size * 1.36)
const cap = computed(() => ({
  width: `${props.size * 0.36}px`,
  height: `${h.value * 0.15}px`,
}))
const neck = computed(() => ({
  top: `${h.value * 0.135}px`,
  width: `${props.size * 0.22}px`,
  height: `${h.value * 0.06}px`,
}))
const body = computed(() => ({
  height: `${h.value * 0.82}px`,
  borderRadius: `${props.size * 0.3}px ${props.size * 0.3}px ${props.size * 0.18}px ${props.size * 0.18}px`,
  background: props.world.bloom,
  boxShadow: props.glow
    ? `0 10px 26px -10px ${props.world.c2}, inset 0 1px 1px rgba(255,255,255,.35)`
    : 'inset 0 1px 1px rgba(255,255,255,.3)',
}))
</script>

<template>
  <div
    class="relative shrink-0"
    :style="{ width: `${size}px`, height: `${h}px` }"
    aria-hidden="true"
  >
    <!-- cap -->
    <div
      class="absolute top-0 left-1/2 -translate-x-1/2 opacity-90"
      :style="{ ...cap, borderRadius: '5px 5px 2px 2px', background: 'var(--color-ink)' }"
    />
    <!-- neck -->
    <div
      class="absolute left-1/2 -translate-x-1/2 opacity-80"
      :style="{ ...neck, background: 'var(--color-ink)' }"
    />
    <!-- frosted gradient body -->
    <div
      class="absolute bottom-0 left-0 overflow-hidden"
      :style="{ width: `${size}px`, ...body }"
    >
      <!-- soft highlight blob -->
      <div
        class="absolute"
        style="top: 10%; left: 14%; width: 22%; height: 55%; border-radius: 999px; background: linear-gradient(180deg, rgba(255,255,255,.55), transparent); filter: blur(2px);"
      />
    </div>
  </div>
</template>
