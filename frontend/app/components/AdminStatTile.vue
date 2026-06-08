<script setup lang="ts">
const props = defineProps<{
  label: string
  value: number | null
  growth?: number
}>()

const up = computed(() => (props.growth ?? 0) >= 0)
</script>

<template>
  <div
    class="relative overflow-hidden rounded-panel border p-[22px_24px]"
    style="background: var(--color-surface); border-color: var(--color-rule);"
  >
    <!-- accent tab -->
    <div class="absolute left-0 top-0 h-0.75 w-9" style="background: var(--color-accent);" />

    <div class="fm uppercase" style="font-size: 9.5px; letter-spacing: 0.16em; color: var(--color-ink-mute);">{{ label }}</div>

    <div class="fd" style="font-size: 42px; line-height: 1; margin-top: 16px; color: var(--color-ink);">
      {{ value !== null ? value.toLocaleString() : '—' }}
    </div>

    <div v-if="growth !== undefined" class="mt-2.5 flex items-center gap-1.5">
      <span
        class="fm"
        :style="{ fontSize: '11px', color: up ? 'oklch(0.62 0.13 150)' : 'oklch(0.6 0.15 25)' }"
      >{{ up ? '▲' : '▼' }} {{ Math.abs(growth) }}%</span>
      <span class="fb whitespace-nowrap" style="font-size: 11px; color: var(--color-ink-mute);">vs last month</span>
    </div>
  </div>
</template>
