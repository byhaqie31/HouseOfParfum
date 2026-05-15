<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <header class="border-b border-rule pb-8">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          Your shelf
        </p>
        <h1 class="mt-3 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
          Wardrobe
        </h1>
      </header>

      <!-- Count + add button, on the same line below the bar -->
      <div v-if="wardrobe.count > 0" class="mt-8 flex items-center justify-between gap-4">
        <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-mute">
          {{ String(wardrobe.count).padStart(2, '0') }}
          <span class="text-ink-soft ml-1">{{ wardrobe.count === 1 ? 'bottle' : 'bottles' }}</span>
        </p>
        <NuxtLink
          to="/user/wardrobe/add"
          class="inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-6 py-3 hover:bg-ink-soft transition-colors"
        >
          <Icon name="lucide:plus" size="14" />
          Add a bottle
        </NuxtLink>
      </div>

      <!-- Populated shelf -->
      <section v-if="wardrobe.count > 0" class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <NuxtLink
          v-for="item in wardrobe.items"
          :key="item.id"
          :to="`/user/wardrobe/${item.id}`"
          class="group flex flex-col"
        >
          <div class="aspect-3/4 bg-paper-deep border border-rule flex items-center justify-center group-hover:bg-paper transition-colors duration-200">
            <BottleIcon :size="72" />
          </div>
          <p class="mt-4 font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute">
            {{ item.brand }}
          </p>
          <h3 class="mt-1 font-display text-[17px] text-ink leading-tight group-hover:text-accent-deep transition-colors">
            {{ item.name }}
          </h3>
          <p class="mt-2 font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute">
            {{ item.size }} ml
            <template v-if="item.acquired">
              <span class="text-accent-deep mx-1">·</span>{{ item.acquired }}
            </template>
          </p>
          <p
            v-if="lastWornLabel(item.id)"
            class="mt-2 font-display italic text-[12px] text-ink-soft"
          >
            {{ lastWornLabel(item.id) }}
          </p>
        </NuxtLink>
      </section>

      <!-- Empty state -->
      <section v-else class="mt-24 sm:mt-32 max-w-xl mx-auto text-center">
        <div class="w-60 h-60 rounded-full border border-rule bg-paper-deep flex items-center justify-center mx-auto">
          <BottleIcon :size="100" />
        </div>
        <h2 class="mt-12 font-display text-3xl sm:text-4xl text-ink tracking-tight leading-[1.1]">
          Your shelf is bare.
        </h2>
        <p class="mt-4 text-base text-ink-soft leading-relaxed">
          Begin with the bottle you reached for today.
        </p>
        <NuxtLink
          to="/user/wardrobe/add"
          class="mt-12 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-8 py-3.5 hover:bg-ink-soft transition-colors"
        >
          Add your first bottle
          <Icon name="lucide:arrow-right" size="14" />
        </NuxtLink>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const wardrobe = useWardrobeStore()
const journal = useJournalStore()

const lastWornLabel = (wardrobeItemId: string) => {
  const iso = journal.lastWornAt(wardrobeItemId)
  if (!iso) return ''
  const days = Math.floor(
    (Date.now() - new Date(iso).getTime()) / (1000 * 60 * 60 * 24),
  )
  if (days === 0) return 'Worn today'
  if (days === 1) return 'Worn yesterday'
  if (days < 7) return `Worn ${days} days ago`
  if (days < 30) return `Last worn ${days} days ago`
  if (days < 365) {
    const months = Math.floor(days / 30)
    return `Last worn ${months} ${months === 1 ? 'month' : 'months'} ago`
  }
  return 'Last worn over a year ago'
}
</script>
