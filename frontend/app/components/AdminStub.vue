<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

// Placeholder for a portal area that's labelled in the nav but designed next.
defineProps<{ title: string; my: string; blurb: string }>()

const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())
</script>

<template>
  <div>
    <AdminPageHeader :title="title" :sub="my" />
    <div
      class="rounded-panel border border-dashed px-10 py-14 text-center"
      style="border-color: var(--color-rule); background: var(--color-surface);"
    >
      <span class="mx-auto mb-4 inline-block h-3.5 w-3.5 rounded-full" :style="{ background: world.gradient }" />
      <div class="fd" style="font-size: 22px; color: var(--color-ink);">Designed next</div>
      <p class="fb mx-auto mt-2 max-w-md" style="font-size: 14px; line-height: 1.55; color: var(--color-ink-soft);">{{ blurb }}</p>
    </div>
  </div>
</template>
