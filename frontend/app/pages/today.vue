<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-3xl mx-auto">
      <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
        {{ formattedDate }}
      </p>

      <h1 class="mt-4 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
        <template v-if="auth.user?.name">
          Good {{ partOfDay }}, <em class="text-ink-soft">{{ firstName }}.</em>
        </template>
        <template v-else>
          Today.
        </template>
      </h1>

      <p class="mt-6 text-lg text-ink-soft leading-relaxed max-w-xl">
        Your daily pick will appear here &mdash; drawn from your shelf,
        the weather, and how you wore yesterday.
      </p>

      <!-- Placeholder card -->
      <div class="mt-16 border border-rule p-10 bg-paper">
        <p class="text-[11px] uppercase tracking-widest text-ink-mute">Suggested for today</p>
        <h2 class="mt-4 font-display text-3xl text-ink tracking-tight">
          <em class="text-ink-soft">— coming soon</em>
        </h2>
        <p class="mt-4 text-sm text-ink-soft">
          We'll wire this up once Vanity has a few bottles to choose from.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()

const now = new Date()

const formattedDate = computed(() =>
  now.toLocaleDateString('en-GB', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }),
)

const partOfDay = computed(() => {
  const h = now.getHours()
  if (h < 12) return 'morning'
  if (h < 18) return 'afternoon'
  return 'evening'
})

const firstName = computed(() =>
  typeof auth.user?.name === 'string' ? auth.user.name.split(' ')[0] : '',
)
</script>
