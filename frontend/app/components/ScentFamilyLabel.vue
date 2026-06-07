<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

withDefaults(defineProps<{
  world: ScentWorld
  size?: number
}>(), {
  size: 12,
})
</script>

<template>
  <span class="inline-flex items-center gap-[7px]">
    <ScentOrb :world="world" :size="size" />
    <span
      class="fm uppercase"
      style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);"
    >{{ world.family.label }}</span>
  </span>
</template>
