<script setup lang="ts">
import type { WardrobeItem } from '~/stores/wardrobe'

const props = defineProps<{ item: WardrobeItem; size?: 'default' | 'large' }>()
const emit = defineEmits<{ open: [item: WardrobeItem]; log: [item: WardrobeItem] }>()

const { worldFor, isDark } = useScentWorld()
const world = worldFor(() => props.item.family ?? 'woody', () => props.item.id + props.item.name)

// Button: filled with onGrad; label is the gradient's dark stop by day, near-black after dark.
const buttonText = computed(() => (isDark.value ? 'oklch(0.2 0.02 285)' : world.value.c2))
const nameSize = computed(() => (props.size === 'large' ? 'clamp(34px, 5vw, 52px)' : 'clamp(30px, 8vw, 38px)'))
</script>

<template>
  <div
    class="relative cursor-pointer overflow-hidden rounded-hero p-6 md:p-9"
    :style="{
      background: world.bloom,
      color: world.onGrad,
      boxShadow: isDark ? `0 0 60px -20px ${world.c1}` : `0 22px 60px -28px ${world.c2}`,
    }"
    @click="emit('open', item)"
  >
    <div class="flex items-start justify-between gap-4">
      <div class="min-w-0">
        <div class="fm uppercase" style="font-size: 11px; letter-spacing: 0.18em; opacity: 0.8;">{{ item.brand }}</div>
        <h2 class="fd mt-1 leading-[1.05]" :style="{ fontSize: nameSize }">{{ item.name }}</h2>
        <p
          v-if="item.tagline"
          class="fb mt-2 italic"
          style="font-size: 15px; opacity: 0.92; max-width: 30ch;"
        >{{ item.tagline }}</p>
      </div>
      <ScentFlacon :world="world" :size="size === 'large' ? 70 : 56" />
    </div>

    <!-- family meta row -->
    <div class="mt-5 flex items-center gap-2" style="opacity: 0.92;">
      <span
        class="inline-block rounded-full"
        :style="{ width: '11px', height: '11px', background: world.c1, boxShadow: `0 0 0 2px ${world.onGrad}` }"
      />
      <span class="fm uppercase" style="font-size: 10px; letter-spacing: 0.16em;">{{ world.family.label }}</span>
      <span v-if="item.concentration" style="opacity: 0.6;">·</span>
      <span v-if="item.concentration" class="fb" style="font-size: 12px;">{{ item.concentration }}</span>
    </div>

    <button
      type="button"
      class="mt-6 flex w-full items-center justify-center gap-2 rounded-full py-3.5 active:scale-[0.99]"
      style="transition: transform 0.12s ease;"
      :style="{ background: world.onGrad, color: buttonText }"
      @click.stop="emit('log', item)"
    >
      <Icon name="lucide:spray-can" size="18" />
      <span class="fb" style="font-size: 15px; font-weight: 700;">Spray on and log</span>
    </button>
  </div>
</template>
