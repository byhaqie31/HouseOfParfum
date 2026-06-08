<script setup lang="ts">
import type { ScentWorld } from '~/utils/scent'

const props = withDefaults(defineProps<{
  world: ScentWorld
  modelValue: number
  max?: number
  size?: number
}>(), {
  max: 8,
  size: 140,
})

const emit = defineEmits<{ 'update:modelValue': [value: number] }>()

const fire = ref(0)

function spray() {
  if (props.modelValue >= props.max) {
    // still fire a puff for feedback even at the cap
    fire.value++
    return
  }
  emit('update:modelValue', props.modelValue + 1)
  fire.value++
}

function dec() {
  emit('update:modelValue', Math.max(0, props.modelValue - 1))
}
function inc() {
  emit('update:modelValue', Math.min(props.max, props.modelValue + 1))
}
</script>

<template>
  <div class="flex flex-col items-center gap-4">
    <div class="relative" :style="{ width: `${size}px`, height: `${size}px` }">
      <SprayBurst :world="world" :fire="fire" />
      <button
        type="button"
        class="flex h-full w-full flex-col items-center justify-center rounded-full select-none active:scale-[0.97]"
        style="transition: transform 0.12s ease;"
        :style="{
          background: world.bloom,
          color: world.onGrad,
          boxShadow: `0 18px 44px -22px ${world.c2}`,
        }"
        :aria-label="`Spray. Currently ${modelValue} sprays`"
        @click="spray"
      >
        <span class="fd leading-none" style="font-size: 46px;">{{ modelValue }}</span>
        <span class="fb mt-1" style="font-size: 12px; opacity: 0.85;">{{ modelValue === 1 ? 'spray' : 'sprays' }}</span>
      </button>
    </div>

    <div class="flex items-center gap-3">
      <button
        type="button"
        class="flex h-9 w-9 items-center justify-center rounded-full border"
        style="border-color: var(--color-rule); color: var(--color-ink-soft);"
        aria-label="One fewer spray"
        @click="dec"
      >
        <Icon name="lucide:minus" size="18" />
      </button>
      <span class="fm w-12 text-center" style="font-size: 13px; color: var(--color-ink-mute);">{{ modelValue }} / {{ max }}</span>
      <button
        type="button"
        class="flex h-9 w-9 items-center justify-center rounded-full border"
        style="border-color: var(--color-rule); color: var(--color-ink-soft);"
        aria-label="One more spray"
        @click="inc"
      >
        <Icon name="lucide:plus" size="18" />
      </button>
    </div>
  </div>
</template>
