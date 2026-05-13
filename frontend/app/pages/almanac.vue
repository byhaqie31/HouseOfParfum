<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
      <header class="text-center">
        <p class="font-mono text-[14px] uppercase tracking-[0.32em] text-ink-mute font-bold">
          A reference volume
        </p>
        <p class="mt-4 font-display italic text-[14px] text-ink-soft">
          Click or drag a corner to turn the page.
        </p>
      </header>

      <ClientOnly>
        <AlmanacBook />
        <template #fallback>
          <div class="text-center font-mono text-[11px] uppercase tracking-[0.24em] text-ink-mute py-32">
            Binding the book…
          </div>
        </template>
      </ClientOnly>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'The Almanac — House of Parfum',
  meta: [
    {
      name: 'description',
      content: 'A field guide to perfume: how it is made, how it wears, how to choose. A reference volume from House of Parfum.',
    },
  ],
})
</script>
