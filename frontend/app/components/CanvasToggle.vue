<script setup lang="ts">
withDefaults(defineProps<{ variant?: 'icon' | 'row' }>(), { variant: 'icon' })

const canvas = useCanvasStore()
</script>

<template>
  <button
    v-if="variant === 'icon'"
    type="button"
    class="flex h-10 w-10 items-center justify-center rounded-full border"
    style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink-soft);"
    :aria-label="canvas.isDark ? 'Switch to daytime' : 'Switch to after dark'"
    @click="canvas.toggle()"
  >
    <Icon :name="canvas.isDark ? 'lucide:sun' : 'lucide:moon'" size="18" />
  </button>

  <button
    v-else
    type="button"
    class="flex w-full items-center justify-between rounded-field border px-3.5 py-2.5"
    style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink);"
    @click="canvas.toggle()"
  >
    <span class="fb inline-flex items-center gap-2" style="font-size: 13px;">
      <Icon :name="canvas.isDark ? 'lucide:moon' : 'lucide:sun'" size="16" />
      {{ canvas.isDark ? 'After dark' : 'Daytime' }}
    </span>
    <span
      class="relative inline-flex h-5 w-9 items-center rounded-full"
      :style="{ background: canvas.isDark ? 'var(--color-ink)' : 'var(--color-rule)' }"
    >
      <span
        class="absolute h-4 w-4 rounded-full bg-white transition-transform"
        :style="{ transform: canvas.isDark ? 'translateX(18px)' : 'translateX(2px)' }"
      />
    </span>
  </button>
</template>
