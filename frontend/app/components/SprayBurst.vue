<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = defineProps<{
  world: ScentWorld
  fire: number // increment to trigger a new burst
}>()

type Particle = { id: number; x: number; y: number; d: number; s: number; flower: boolean }

const particles = ref<Particle[]>([])

// Each burst remounts a fresh set of ~16 particles drifting up-and-out:
// some filled accent dots, ~40% hollow "petal" rings. (Math.random is fine in
// this client-only component.)
watch(() => props.fire, (n) => {
  if (n <= 0) return
  particles.value = Array.from({ length: 16 }).map((_, i) => {
    const a = (-90 + (Math.random() * 120 - 60)) * Math.PI / 180
    const dist = 50 + Math.random() * 90
    return {
      id: i,
      x: Math.cos(a) * dist,
      y: Math.sin(a) * dist - 20,
      d: 0.05 + Math.random() * 0.25,
      s: 0.5 + Math.random() * 0.9,
      flower: Math.random() > 0.6,
    }
  })
})
</script>

<template>
  <div class="pointer-events-none absolute inset-0 overflow-visible" aria-hidden="true">
    <span
      v-for="p in particles"
      :key="`${fire}-${p.id}`"
      class="absolute rounded-full"
      :style="{
        left: '50%',
        top: '40%',
        opacity: 0,
        width: p.flower ? '9px' : '6px',
        height: p.flower ? '9px' : '6px',
        background: p.flower ? 'transparent' : world.accent,
        border: p.flower ? `1.5px solid ${world.accent}` : 'none',
        animation: `puff 1.1s ${p.d}s cubic-bezier(.2,.7,.3,1) forwards`,
        '--px': `${p.x}px`,
        '--py': `${p.y}px`,
        '--ps': String(p.s),
      }"
    />
  </div>
</template>
