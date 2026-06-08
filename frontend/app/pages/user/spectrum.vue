<script setup lang="ts">
import { FAMILY_ORDER, FAMILIES, scentWorld, type ScentFamilyKey } from '~/utils/scent'

definePageMeta({ layout: 'app', middleware: 'auth' })
useHead({ title: 'Scent spectrum' })

const canvas = useCanvasStore()
const wardrobe = useWardrobeStore()

// Local preview switch — defaults to the live canvas mode but can be flipped to
// inspect either mode as living documentation.
const previewDark = ref(canvas.isDark)

const cards = computed(() =>
  FAMILY_ORDER.map((key) => {
    const fam = FAMILIES[key]
    const world = scentWorld(key, 0.5, previewDark.value)
    const example = wardrobe.items.find((i) => i.family === key) ?? null
    return { key, fam, world, example }
  }),
)

function modeSwatch(key: ScentFamilyKey, dark: boolean) {
  return scentWorld(key, 0.5, dark)
}
</script>

<template>
  <div>
    <header class="mb-6 flex flex-wrap items-end justify-between gap-4">
      <div>
        <div class="kicker">Living reference</div>
        <h1 class="fd mt-1" style="font-size: clamp(28px, 6vw, 42px); line-height: 1.05;">The scent spectrum</h1>
        <p class="fb mt-2 italic" style="font-size: 14px; color: var(--color-ink-soft);">Seven families, each its own colour world. Every bottle is born from one.</p>
      </div>

      <!-- local daytime / after-dark switch -->
      <div class="inline-flex rounded-full border p-1" style="border-color: var(--color-rule); background: var(--color-surface);">
        <button
          type="button"
          class="rounded-full px-4 py-1.5 fb"
          style="font-size: 12px;"
          :style="!previewDark ? { background: 'var(--color-ink)', color: 'var(--color-canvas)' } : { color: 'var(--color-ink-soft)' }"
          @click="previewDark = false"
        >Daytime</button>
        <button
          type="button"
          class="rounded-full px-4 py-1.5 fb"
          style="font-size: 12px;"
          :style="previewDark ? { background: 'var(--color-ink)', color: 'var(--color-canvas)' } : { color: 'var(--color-ink-soft)' }"
          @click="previewDark = true"
        >After dark</button>
      </div>
    </header>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <article
        v-for="card in cards"
        :key="card.key"
        class="overflow-hidden rounded-panel border"
        style="border-color: var(--color-rule); background: var(--color-surface);"
      >
        <!-- gradient header -->
        <div class="flex items-center justify-between p-5" :style="{ background: card.world.bloom, color: card.world.onGrad }">
          <div>
            <div class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; opacity: 0.8;">Family</div>
            <div class="fd" style="font-size: 24px;">{{ card.fam.label }}</div>
          </div>
          <ScentFlacon :world="card.world" :size="48" />
        </div>

        <div class="p-5">
          <!-- hue band + chroma readout -->
          <div class="kicker mb-2">OKLCH</div>
          <p class="fm" style="font-size: 11px; color: var(--color-ink-soft);">
            hue {{ card.fam.hue[0] }}–{{ card.fam.hue[1] }}° · chroma {{ previewDark ? card.fam.dc : card.fam.lc }}
          </p>

          <!-- three core swatches -->
          <div class="mt-4 grid grid-cols-3 gap-2">
            <div v-for="s in [
              { label: 'c1', bg: card.world.c1 },
              { label: 'c2', bg: card.world.c2 },
              { label: 'accent', bg: card.world.accent },
            ]" :key="s.label" class="text-center">
              <span class="block h-10 w-full rounded-lg" :style="{ background: s.bg }" />
              <span class="fm mt-1 block uppercase" style="font-size: 8px; letter-spacing: 0.1em; color: var(--color-ink-mute);">{{ s.label }}</span>
            </div>
          </div>

          <!-- both-mode mini swatches -->
          <div class="mt-4 flex items-center gap-3">
            <span class="fm" style="font-size: 9px; color: var(--color-ink-mute);">Both modes</span>
            <span class="h-4 w-8 rounded-full" :style="{ background: modeSwatch(card.key, false).gradient }" title="Daytime" />
            <span class="h-4 w-8 rounded-full" :style="{ background: modeSwatch(card.key, true).gradient }" title="After dark" />
          </div>

          <NuxtLink
            v-if="card.example"
            :to="`/user/wardrobe/${card.example.id}`"
            class="mt-4 inline-flex items-center gap-1.5 fb italic"
            style="font-size: 13px;"
            :style="{ color: card.world.accent }"
          >e.g. {{ card.example.name }} <Icon name="lucide:arrow-right" size="13" /></NuxtLink>
          <p v-else class="mt-4 fb italic" style="font-size: 12px; color: var(--color-ink-mute);">No bottle yet in this family.</p>
        </div>
      </article>
    </div>
  </div>
</template>
