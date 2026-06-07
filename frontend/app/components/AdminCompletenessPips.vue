<script setup lang="ts">
// Four squares — F / N / I / H (family, notes, image, history) — filled when the
// field is present, dashed + muted when missing. An at-a-glance row health read.
const props = defineProps<{
  missing: { family: boolean; notes: boolean; image: boolean; history: boolean }
  accent?: string
}>()

const pips = computed(() => [
  { on: !props.missing.family, label: 'F', title: 'Scent family' },
  { on: !props.missing.notes, label: 'N', title: 'Notes pyramid' },
  { on: !props.missing.image, label: 'I', title: 'Image' },
  { on: !props.missing.history, label: 'H', title: 'History' },
])
</script>

<template>
  <div class="flex gap-[5px]">
    <span
      v-for="p in pips"
      :key="p.label"
      :title="p.title"
      class="fm flex items-center justify-center"
      :style="{
        width: '20px', height: '20px', borderRadius: '6px', fontSize: '9px',
        background: p.on ? 'var(--color-surface-2)' : 'transparent',
        border: p.on ? '1px solid var(--color-rule)' : '1px dashed var(--color-ink-mute)',
        color: p.on ? (accent || 'var(--color-ink-soft)') : 'var(--color-ink-mute)',
        opacity: p.on ? 1 : 0.7,
      }"
    >{{ p.label }}</span>
  </div>
</template>
