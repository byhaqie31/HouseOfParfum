<template>
  <svg
    viewBox="0 0 40 56"
    :width="size"
    :height="(size * 56) / 40"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
  >
    <!-- Cap -->
    <rect x="13" y="0" width="14" height="9" fill="var(--color-ink)" />
    <!-- Body (paper with ink-soft stroke) -->
    <rect
      x="4"
      y="9"
      width="32"
      height="44"
      rx="1"
      fill="var(--color-paper)"
      stroke="var(--color-ink-soft)"
      stroke-width="0.6"
    />
    <!-- Champagne label -->
    <rect x="10" y="27" width="20" height="14" opacity="0.9" fill="var(--color-accent)" />
    <!-- Ink stripe over label -->
    <rect x="10" y="32" width="20" height="1" opacity="0.85" fill="var(--color-ink)" />
  </svg>
</template>

<script setup lang="ts">
withDefaults(defineProps<{ size?: number }>(), { size: 56 })
</script>
