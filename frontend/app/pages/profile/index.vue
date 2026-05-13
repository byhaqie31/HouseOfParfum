<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
      <!-- Greeting eyebrow -->
      <p class="font-mono text-[10px] uppercase tracking-[0.22em] text-ink-mute leading-[1.7]">
        Your account
      </p>

      <!-- Hero -->
      <h1 class="mt-4 font-display text-5xl sm:text-6xl text-ink tracking-[-0.005em] leading-none">
        <template v-if="auth.user?.name">
          {{ auth.user.name }}<em class="text-ink-soft">.</em>
        </template>
        <template v-else>
          Friend<em class="text-ink-soft">.</em>
        </template>
      </h1>

      <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute">
        {{ auth.user?.email || '—' }}
        <template v-if="memberSinceShort">
          <span class="text-accent-deep mx-1">·</span>
          Member since {{ memberSinceShort }}
        </template>
      </p>

      <!-- Section divider with gold accent -->
      <div class="relative border-b border-ink mt-10 mb-10">
        <div class="absolute -bottom-px left-0 w-20 h-px bg-accent" />
      </div>

      <div class="space-y-6">
        <!-- Account -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Account
          </p>

          <dl class="divide-y divide-rule">
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 first:pt-0">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Name
              </dt>
              <dd class="mt-1 sm:mt-0 font-display italic text-[16px] text-ink">
                {{ auth.user?.name || '—' }}
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Email
              </dt>
              <dd class="mt-1 sm:mt-0 text-[14px] text-ink">
                {{ auth.user?.email || '—' }}
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 last:pb-0">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Member since
              </dt>
              <dd class="mt-1 sm:mt-0 font-mono text-[12px] uppercase tracking-[0.16em] text-ink-mute">
                {{ memberSinceFull }}
              </dd>
            </div>
          </dl>

          <p class="mt-5 font-display italic text-[13px] text-ink-mute">
            Editing your details lands in a later release.
          </p>
        </section>

        <!-- Preferences -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Preferences
          </p>

          <dl class="divide-y divide-rule">
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 first:pt-0">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Fragrance families
              </dt>
              <dd class="mt-1 sm:mt-0 font-display italic text-[15px] text-ink-soft">
                Not set yet
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Seasonal leaning
              </dt>
              <dd class="mt-1 sm:mt-0 font-display italic text-[15px] text-ink-soft">
                Not set yet
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 last:pb-0">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Signature scent
              </dt>
              <dd class="mt-1 sm:mt-0 font-display italic text-[15px] text-ink-soft">
                Not set yet
              </dd>
            </div>
          </dl>

          <p class="mt-5 font-display italic text-[13px] text-ink-mute">
            Tuning preferences lands in a later release — Today's recommendation will lean on them.
          </p>
        </section>

        <!-- Notifications -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Notifications
          </p>

          <dl class="divide-y divide-rule">
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] items-center gap-x-8 py-4 first:pt-0">
              <div>
                <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                  Daily Today nudge
                </dt>
                <dd class="mt-1 font-display italic text-[13px] text-ink-mute">
                  A gentle morning prompt to pick the day's wear.
                </dd>
              </div>
              <button
                type="button"
                role="switch"
                :aria-checked="prefs.dailyNudge"
                class="relative inline-flex h-6 w-11 items-center border transition-colors"
                :class="prefs.dailyNudge ? 'bg-accent-soft border-accent' : 'bg-paper border-rule'"
                @click="prefs.dailyNudge = !prefs.dailyNudge"
              >
                <span
                  class="inline-block h-4 w-4 bg-ink transition-transform"
                  :class="prefs.dailyNudge ? 'translate-x-6' : 'translate-x-1'"
                />
              </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] items-center gap-x-8 py-4 last:pb-0">
              <div>
                <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                  Weekly journal recap
                </dt>
                <dd class="mt-1 font-display italic text-[13px] text-ink-mute">
                  A Sunday note of what you wore this week.
                </dd>
              </div>
              <button
                type="button"
                role="switch"
                :aria-checked="prefs.weeklyRecap"
                class="relative inline-flex h-6 w-11 items-center border transition-colors"
                :class="prefs.weeklyRecap ? 'bg-accent-soft border-accent' : 'bg-paper border-rule'"
                @click="prefs.weeklyRecap = !prefs.weeklyRecap"
              >
                <span
                  class="inline-block h-4 w-4 bg-ink transition-transform"
                  :class="prefs.weeklyRecap ? 'translate-x-6' : 'translate-x-1'"
                />
              </button>
            </div>
          </dl>
        </section>

        <!-- Appearance -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Appearance
          </p>

          <dl class="divide-y divide-rule">
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 first:pt-0 last:pb-0">
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                Theme
              </dt>
              <dd class="mt-1 sm:mt-0 text-[14px] text-ink">
                Paper
                <span class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute ml-2">
                  Default
                </span>
              </dd>
            </div>
          </dl>

          <p class="mt-5 font-display italic text-[13px] text-ink-mute">
            Dark mode lands in a later release.
          </p>
        </section>

        <!-- Data & privacy -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Data &amp; privacy
          </p>

          <dl class="divide-y divide-rule">
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] items-center gap-x-8 py-4 first:pt-0">
              <div>
                <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                  Export your collection
                </dt>
                <dd class="mt-1 font-display italic text-[13px] text-ink-mute">
                  A JSON of your shelf, journal, and notes.
                </dd>
              </div>
              <button
                type="button"
                disabled
                class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute border border-rule px-4 py-2 cursor-not-allowed"
              >
                Soon
              </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto] items-center gap-x-8 py-4 last:pb-0">
              <div>
                <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                  Delete account
                </dt>
                <dd class="mt-1 font-display italic text-[13px] text-ink-mute">
                  Removes your shelf, journal, and preferences. Permanent.
                </dd>
              </div>
              <button
                type="button"
                disabled
                class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute border border-rule px-4 py-2 cursor-not-allowed"
              >
                Soon
              </button>
            </div>
          </dl>
        </section>
      </div>

      <!-- Session footer -->
      <div class="mt-10 pt-6 border-t border-rule flex flex-wrap items-center justify-between gap-6">
        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
          Signed in as {{ auth.user?.email || '—' }}
        </p>
        <button
          type="button"
          class="font-display italic text-[15px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
          @click="handleSignOut"
        >
          Sign out
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const router = useRouter()

type Prefs = { dailyNudge: boolean, weeklyRecap: boolean }
const PREFS_KEY = 'hop:profile:prefs'

const prefs = reactive<Prefs>({ dailyNudge: false, weeklyRecap: false })

onMounted(() => {
  try {
    const raw = localStorage.getItem(PREFS_KEY)
    if (raw) Object.assign(prefs, JSON.parse(raw))
  } catch {
    // ignore — fall back to defaults
  }
})

watch(prefs, (value) => {
  localStorage.setItem(PREFS_KEY, JSON.stringify(value))
}, { deep: true })

const memberSinceShort = computed(() => {
  const created = auth.user?.created_at
  if (!created) return ''
  return new Date(created).toLocaleDateString('en-GB', {
    month: 'long',
    year: 'numeric',
  })
})

const memberSinceFull = computed(() => {
  const created = auth.user?.created_at
  if (!created) return '—'
  return new Date(created)
    .toLocaleDateString('en-GB', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    })
    .toUpperCase()
})

const handleSignOut = () => {
  auth.logout()
  router.push('/')
}
</script>
