<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = defineProps<{
  world: ScentWorld
  top?: string[] | null
  heart?: string[] | null
  base?: string[] | null
}>()

// Top → heart → base: progressively wider tiers on mobile (the "pyramid"),
// three equal cards on web (md+).
const tiers = computed(() => [
  { label: 'Top notes', items: props.top ?? [], w: 'w-[62%]' },
  { label: 'Heart notes', items: props.heart ?? [], w: 'w-[80%]' },
  { label: 'Base notes', items: props.base ?? [], w: 'w-full' },
])

const hasAny = computed(() => tiers.value.some((t) => t.items.length))
</script>

<template>
  <div v-if="hasAny" class="flex flex-col gap-2.5 md:grid md:grid-cols-3 md:gap-3">
    <div
      v-for="tier in tiers"
      :key="tier.label"
      :class="[tier.w, 'md:w-full']"
      class="rounded-card border p-[13px_16px]"
      style="background: var(--color-surface); border-color: var(--color-rule);"
    >
      <div class="flex items-baseline justify-between gap-2">
        <span class="kicker" :style="{ color: world.accent }">{{ tier.label }}</span>
      </div>
      <div class="mt-2 flex flex-wrap gap-x-2.5 gap-y-1">
        <span
          v-for="(note, j) in tier.items"
          :key="note"
          class="fd inline-flex items-center"
          style="font-size: 16px; color: var(--color-ink);"
        >
          <span
            v-if="j > 0"
            class="mr-1.5 inline-block rounded-full"
            :style="{ width: '3px', height: '3px', background: world.accent }"
          />
          {{ note }}
        </span>
      </div>
    </div>
  </div>
</template>
