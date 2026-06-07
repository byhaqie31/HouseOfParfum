<script setup lang="ts">
import { scentWorld, bottleSeed, type ScentFamilyKey } from '~/utils/scent'
import { deriveFamily } from '~/utils/scentFamily'

interface DiscoverPerfume {
  id: number
  brand: string
  name: string
  main_accord?: string | null
  rating?: number | null
  family?: ScentFamilyKey | null
}

const props = defineProps<{ perfume: DiscoverPerfume }>()

const { isDark } = useScentWorld()

function splitAccords(raw?: string | null): string[] {
  return (raw ?? '').split(',').map((s) => s.trim()).filter(Boolean)
}

const accords = computed(() => splitAccords(props.perfume.main_accord))

// The whole win: colour is derivable from data we already ship. Prefer a curated
// family if one exists, else derive it from the catalogue's main_accord vocabulary.
const family = computed<ScentFamilyKey>(
  () => props.perfume.family ?? deriveFamily(accords.value),
)
const world = computed(() =>
  scentWorld(family.value, bottleSeed(String(props.perfume.id), props.perfume.name), isDark.value),
)

// Per-card hover chrome, tinted by the scent world (set as CSS vars on the root).
const cardVars = computed(() => ({
  '--card-accent': world.value.accent,
  '--card-shadow': `0 22px 48px -26px ${world.value.c2}`,
}))
</script>

<template>
  <NuxtLink
    :to="`/user/perfume/${perfume.id}`"
    class="discover-card group block rounded-card border p-4"
    :style="[cardVars, { borderColor: 'var(--color-rule)', background: 'var(--color-surface)' }]"
  >
    <!-- Colour panel — the dead placeholder becomes the colour moment. -->
    <div
      class="relative flex aspect-3/4 items-center justify-center overflow-hidden rounded-card"
      :style="{ background: world.bloom }"
    >
      <!-- frosted family pill -->
      <span
        class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1"
        style="background: rgba(255,255,255,0.18); backdrop-filter: blur(6px);"
      >
        <span class="inline-block rounded-full" :style="{ width: '7px', height: '7px', background: world.c1 }" />
        <span class="fm uppercase" :style="{ fontSize: '8.5px', letterSpacing: '0.12em', color: world.onGrad }">{{ world.family.label }}</span>
      </span>

      <ScentFlacon :world="world" :size="72" />
    </div>

    <!-- Meta -->
    <p class="mt-4 fm uppercase" style="font-size: 9px; letter-spacing: 0.16em; color: var(--color-ink-mute);">{{ perfume.brand }}</p>
    <h3 class="mt-1 fd line-clamp-2" style="font-size: 18px; line-height: 1.2; min-height: 2.4em; color: var(--color-ink);">{{ perfume.name }}</h3>

    <p v-if="accords.length" class="mt-2.5 flex flex-wrap items-center gap-x-1.5 gap-y-1">
      <template v-for="(a, i) in accords.slice(0, 3)" :key="a">
        <span v-if="i" class="inline-block rounded-full" :style="{ width: '3px', height: '3px', background: world.accent }" />
        <span class="fm uppercase" style="font-size: 9px; letter-spacing: 0.1em; color: var(--color-ink-soft);">{{ a }}</span>
      </template>
    </p>

    <div class="mt-4 flex items-center justify-between gap-3">
      <span
        class="view-link fd italic"
        :style="{ fontSize: '12px', color: 'var(--color-ink)', borderBottom: `1px solid ${world.accent}`, paddingBottom: '1px' }"
      >View details →</span>
      <span v-if="perfume.rating != null" class="fm whitespace-nowrap" style="font-size: 11px; color: var(--color-ink-mute);">★ {{ perfume.rating }}</span>
    </div>
  </NuxtLink>
</template>

<style scoped>
.discover-card {
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.discover-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--card-shadow);
}
.discover-card:hover .view-link {
  color: var(--card-accent) !important;
}

@media (prefers-reduced-motion: reduce) {
  .discover-card { transition: none; }
  .discover-card:hover { transform: none; }
}
</style>
