<script setup lang="ts">
import { scentWorld, bottleSeed, type ScentFamilyKey, type ScentWorld } from '~/utils/scent'
import { deriveFamily } from '~/utils/scentFamily'
import { showcaseImageFor } from '~/data/showcase-images'

interface ShowcasePerfume {
  id: number
  brand: string
  name: string
  main_accord?: string | null
  rating?: number | null
  family?: ScentFamilyKey | null
}

const props = defineProps<{ perfumes: ShowcasePerfume[] }>()

const { isDark } = useScentWorld()

// ~5s of drift per card keeps apparent speed constant however many load.
const duration = computed(() => `${props.perfumes.length * 5}s`)

// Same colour derivation as DiscoverCard: curated family wins, else derive
// from the accord vocabulary. Pure function of the row + canvas mode.
function worldOf(p: ShowcasePerfume): ScentWorld {
  const accords = (p.main_accord ?? '').split(',').map((s) => s.trim()).filter(Boolean)
  return scentWorld(p.family ?? deriveFamily(accords), bottleSeed(String(p.id), p.name), isDark.value)
}
</script>

<template>
  <div class="marquee" :style="{ '--marquee-duration': duration }">
    <div class="marquee-rail">
      <!-- Track is rendered twice for the seamless -50% loop; the duplicate is
           inert for screen readers and keyboards. -->
      <div
        v-for="copy in 2"
        :key="copy"
        class="marquee-track"
        :aria-hidden="copy === 2 ? 'true' : undefined"
      >
        <NuxtLink
          v-for="p in perfumes"
          :key="`${copy}-${p.id}`"
          :to="`/user/perfume/${p.id}`"
          :tabindex="copy === 2 ? -1 : undefined"
          class="showcase-card block w-56 shrink-0"
        >
          <div
            class="relative aspect-3/4 overflow-hidden rounded-card border"
            style="border-color: var(--color-rule); background: var(--color-surface-2);"
          >
            <img
              :src="showcaseImageFor(p.id)"
              alt=""
              loading="lazy"
              class="absolute inset-0 h-full w-full object-cover"
            >
            <span
              class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1"
              style="background: rgba(20, 20, 20, 0.35); backdrop-filter: blur(6px);"
            >
              <span class="inline-block rounded-full" :style="{ width: '7px', height: '7px', background: worldOf(p).c1 }" />
              <span class="fm uppercase" style="font-size: 8.5px; letter-spacing: 0.12em; color: #fff;">{{ worldOf(p).family.label }}</span>
            </span>
          </div>
          <p class="mt-3 fm uppercase" style="font-size: 9px; letter-spacing: 0.16em; color: var(--color-ink-mute);">{{ p.brand }}</p>
          <h3 class="mt-1 fd line-clamp-1" style="font-size: 17px; color: var(--color-ink);">{{ p.name }}</h3>
          <p v-if="p.rating != null" class="mt-1 fm" style="font-size: 11px; color: var(--color-ink-mute);">★ {{ p.rating }}</p>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.marquee {
  overflow: hidden;
  -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
  mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
}

.marquee-rail {
  display: flex;
  width: max-content;
  animation: marquee-drift var(--marquee-duration, 60s) linear infinite;
}

.marquee:hover .marquee-rail {
  animation-play-state: paused;
}

/* gap + matching padding-right make each track exactly N*(card+gap) wide,
   so translateX(-50%) lands precisely one track over: a seamless loop. */
.marquee-track {
  display: flex;
  gap: 24px;
  padding-right: 24px;
}

@keyframes marquee-drift {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

@media (prefers-reduced-motion: reduce) {
  .marquee { overflow-x: auto; }
  .marquee-rail { animation: none; }
  .marquee-track[aria-hidden='true'] { display: none; }
}
</style>
