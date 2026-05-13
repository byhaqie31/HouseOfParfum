<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-5xl mx-auto">
      <header class="flex items-end justify-between border-b border-rule pb-8">
        <div>
          <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
            Your shelf
          </p>
          <h1 class="mt-3 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
            Vanity
          </h1>
        </div>
        <NuxtLink
          to="/vanity/add"
          class="hidden sm:inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-widest px-6 py-3 hover:bg-ink-soft transition-colors"
        >
          <Icon name="lucide:plus" class="h-3.5 w-3.5" />
          Add a bottle
        </NuxtLink>
      </header>

      <!-- Empty state -->
      <div class="mt-24 sm:mt-32 max-w-xl mx-auto text-center">
        <div class="w-60 h-60 rounded-full border border-rule bg-paper-deep flex items-center justify-center mx-auto">
          <Icon name="lucide:flower-2" size="100" class="text-ink-mute" />
        </div>
        <h2 class="mt-12 font-display text-3xl sm:text-4xl text-ink tracking-tight leading-[1.1]">
          Your shelf is bare.
        </h2>
        <p class="mt-4 text-base text-ink-soft leading-relaxed">
          Begin with the bottle you reached for today.
        </p>
        <NuxtLink
          to="/vanity/add"
          class="mt-12 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-widest px-8 py-3.5 hover:bg-ink-soft transition-colors"
        >
          Add your first bottle
          <Icon name="lucide:arrow-right" class="h-3.5 w-3.5" />
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
</script>
