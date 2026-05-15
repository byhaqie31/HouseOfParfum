<template>
  <div>
    <!-- Hero -->
    <section class="pt-32 pb-24 px-6">
      <div class="max-w-3xl mx-auto text-center">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          A personal perfume companion
        </p>
        <h1 class="mt-6 font-display text-5xl sm:text-7xl text-ink tracking-tight leading-[1.05]">
          Wear what moves you.<br>
          <em class="text-ink-soft">Remember what did.</em>
        </h1>
        <p class="mt-8 text-lg text-ink-soft leading-relaxed max-w-xl mx-auto">
          House of Parfum is a quiet space to keep track of the bottles on your shelf,
          the scents you wear each day, and the ones worth reaching for tomorrow.
        </p>
        <div v-if="!auth.isLoggedIn" class="mt-12 flex items-center justify-center gap-6">
          <NuxtLink
            to="/auth/login"
            class="inline-block bg-ink text-paper text-xs uppercase tracking-widest px-8 py-3.5 hover:bg-ink-soft transition-colors"
          >
            Sign in
          </NuxtLink>
          <NuxtLink
            to="/auth/register"
            class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-ink hover:text-accent transition-colors"
          >
            Create an account
            <Icon name="lucide:arrow-right" class="h-3.5 w-3.5" />
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Three pillars -->
    <section class="py-24 px-6 bg-paper-deep border-y border-rule">
      <div class="max-w-5xl mx-auto">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute text-center">
          Three quiet rituals
        </p>
        <h2 class="mt-4 font-display text-4xl sm:text-5xl text-ink tracking-tight text-center">
          A shelf, a diary, a daily nudge.
        </h2>

        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-16">
          <article
            v-for="pillar in pillars"
            :key="pillar.title"
            class="flex flex-col"
          >
            <Icon :name="pillar.icon" class="h-6 w-6 text-ink" />
            <h3 class="mt-6 font-display text-2xl text-ink tracking-tight">
              {{ pillar.title }}
            </h3>
            <p class="mt-4 text-sm text-ink-soft leading-relaxed">
              {{ pillar.body }}
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Closing CTA -->
    <section v-if="!auth.isLoggedIn" class="py-32 px-6">
      <div class="max-w-2xl mx-auto text-center">
        <h2 class="font-display text-4xl sm:text-5xl text-ink tracking-tight leading-[1.1]">
          Begin with the bottle <em class="text-ink-soft">you reached for today.</em>
        </h2>
        <NuxtLink
          to="/auth/register"
          class="mt-10 inline-block bg-ink text-paper text-xs uppercase tracking-widest px-8 py-3.5 hover:bg-ink-soft transition-colors"
        >
          Create your shelf
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore()

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
