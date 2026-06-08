<script setup lang="ts">
// Every admin screen opens with a Fredoka H1 + italic bilingual sub-line, with
// room for a right-aligned action/metric via the #actions slot.
defineProps<{ title: string; sub?: string }>()
</script>

<template>
  <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div class="min-w-0">
      <h1 class="fd" style="font-size: clamp(28px, 4vw, 36px); line-height: 1.1; color: var(--color-ink);">{{ title }}</h1>
      <p v-if="sub" class="fb mt-1.5 italic" style="font-size: 14px; color: var(--color-ink-soft);">{{ sub }}</p>
    </div>
    <slot name="actions" />
  </div>
</template>
