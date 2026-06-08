<template>
  <div style="background: var(--color-canvas);">
    <!-- Hero -->
    <section class="px-6 pt-32 pb-24">
      <div class="mx-auto max-w-3xl text-center">
        <p class="kicker" :style="{ color: world.accent }">
          A personal perfume companion
        </p>
        <h1 class="fd mt-6" style="font-size: clamp(40px, 7vw, 76px); line-height: 1.05; letter-spacing: -0.01em; color: var(--color-ink);">
          Wear what moves you.<br>
          <em style="color: var(--color-ink-soft);">Remember what did.</em>
        </h1>
        <p class="fb mx-auto mt-8 max-w-xl" style="font-size: 18px; line-height: 1.65; color: var(--color-ink-soft);">
          House of Parfum is a quiet space to keep track of the bottles on your shelf,
          the scents you wear each day, and the ones worth reaching for tomorrow.
        </p>
        <div v-if="!auth.isLoggedIn" class="mt-12 flex flex-wrap items-center justify-center gap-5">
          <NuxtLink
            to="/auth/login"
            class="fm inline-flex items-center rounded-full px-8 py-3.5 uppercase"
            style="font-size: 11px; letter-spacing: 0.16em;"
            :style="{ background: world.gradient, color: world.onGrad }"
          >
            Sign in
          </NuxtLink>
          <NuxtLink
            to="/auth/register"
            class="fm inline-flex items-center gap-2 uppercase transition-colors"
            style="font-size: 11px; letter-spacing: 0.16em; color: var(--color-ink);"
          >
            Create an account
            <Icon name="lucide:arrow-right" class="h-3.5 w-3.5" />
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Three pillars -->
    <section class="border-y px-6 py-24" style="border-color: var(--color-rule); background: var(--color-surface-2);">
      <div class="mx-auto max-w-5xl">
        <p class="kicker text-center" :style="{ color: world.accent }">
          Three quiet rituals
        </p>
        <h2 class="fd mt-4 text-center" style="font-size: clamp(32px, 5vw, 52px); line-height: 1.1; letter-spacing: -0.01em; color: var(--color-ink);">
          A shelf, a diary, a daily nudge.
        </h2>

        <div class="mt-16 grid grid-cols-1 gap-6 md:mt-20 md:grid-cols-3">
          <article
            v-for="pillar in pillars"
            :key="pillar.title"
            class="rounded-panel border p-7"
            style="border-color: var(--color-rule); background: var(--color-surface);"
          >
            <span
              class="inline-flex h-12 w-12 items-center justify-center rounded-field"
              :style="{ background: world.soft, color: world.accent }"
            >
              <Icon :name="pillar.icon" class="h-6 w-6" />
            </span>
            <h3 class="fd mt-6" style="font-size: 24px; letter-spacing: -0.005em; color: var(--color-ink);">
              {{ pillar.title }}
            </h3>
            <p class="fb mt-4" style="font-size: 14px; line-height: 1.65; color: var(--color-ink-soft);">
              {{ pillar.body }}
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Closing CTA -->
    <section v-if="!auth.isLoggedIn" class="px-6 py-24">
      <div
        class="mx-auto max-w-3xl rounded-hero px-8 py-20 text-center"
        :style="{ background: world.bloom, color: world.onGrad }"
      >
        <h2 class="fd" style="font-size: clamp(30px, 5vw, 50px); line-height: 1.1; letter-spacing: -0.01em;">
          Begin with the bottle <em style="opacity: 0.82;">you reached for today.</em>
        </h2>
        <NuxtLink
          to="/auth/register"
          class="fm mt-10 inline-flex items-center rounded-full px-8 py-3.5 uppercase"
          style="font-size: 11px; letter-spacing: 0.16em; background: var(--color-surface); color: var(--color-ink);"
        >
          Create your shelf
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

const auth = useAuthStore()
const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())

const pillars = [
  {
    icon: 'lucide:flower-2',
    title: 'Wardrobe',
    body: 'A record of every bottle on your shelf — brand, notes, size, the day it arrived. Decants and samples welcome.',
  },
  {
    icon: 'lucide:book-open',
    title: 'Journal',
    body: 'A short note for each wear: what, when, how it sat with you. A diary written in scent.',
  },
  {
    icon: 'lucide:sparkles',
    title: 'Today',
    body: 'A daily suggestion drawn from what you own, the weather outside, and what you wore yesterday.',
  },
]

onMounted(() => auth.init())
</script>
