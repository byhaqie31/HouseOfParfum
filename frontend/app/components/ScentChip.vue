<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = withDefaults(defineProps<{
  active?: boolean
  world?: ScentWorld | null
  as?: 'button' | 'span'
}>(), {
  active: false,
  world: null,
  as: 'button',
})

// Active chip tints with the fragrance's soft bg + accent text/border. With no
// world, the active state is a solid ink pill. No transition on the colour swap
// (it caused visible lag in handoff testing) — only transform/opacity may animate.
const style = computed(() => {
  if (!props.active) {
    return {
      background: 'transparent',
      color: 'var(--color-ink-soft)',
      border: '1px solid var(--color-rule)',
    }
  }
  if (props.world) {
    return {
      background: props.world.soft,
      color: props.world.accent,
      border: `1px solid ${props.world.accent}`,
    }
  }
  return {
    background: 'var(--color-ink)',
    color: 'var(--color-canvas)',
    border: '1px solid var(--color-ink)',
  }
})
</script>

<template>
  <component
    :is="as"
    class="fb inline-flex items-center whitespace-nowrap rounded-full"
    :type="as === 'button' ? 'button' : undefined"
    :style="{ padding: '7px 13px', fontSize: '12px', letterSpacing: '0.01em', ...style, cursor: as === 'button' ? 'pointer' : 'default' }"
  >
    <slot />
  </component>
</template>
