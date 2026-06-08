<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = withDefaults(defineProps<{
  world: ScentWorld
  size?: number
}>(), {
  size: 14,
})
</script>

<template>
  <span
    class="inline-block shrink-0 rounded-full"
    :style="{
      width: `${size}px`,
      height: `${size}px`,
      background: world.gradient,
      boxShadow: `0 0 0 1px rgba(0,0,0,.05), 0 2px 6px -2px ${world.c2}`,
    }"
  />
</template>
