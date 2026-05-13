<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-3xl mx-auto">
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
      <div class="relative border-b border-ink mt-10 mb-12">
        <div class="absolute -bottom-px left-0 w-20 h-px bg-accent" />
      </div>

      <!-- Account section -->
      <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
        Account
      </p>

      <dl class="border-t border-rule">
        <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-5 border-b border-rule">
          <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
            Name
          </dt>
          <dd class="mt-1 sm:mt-0 font-display italic text-[16px] text-ink">
            {{ auth.user?.name || '—' }}
          </dd>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-5 border-b border-rule">
          <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
            Email
          </dt>
          <dd class="mt-1 sm:mt-0 text-[14px] text-ink">
            {{ auth.user?.email || '—' }}
          </dd>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-5 border-b border-rule">
          <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
            Member since
          </dt>
          <dd class="mt-1 sm:mt-0 font-mono text-[12px] uppercase tracking-[0.16em] text-ink-mute">
            {{ memberSinceFull }}
          </dd>
        </div>
      </dl>

      <!-- Edit + Sign out -->
      <div class="mt-12 pt-6 border-t border-rule flex flex-wrap items-center justify-between gap-6">
        <p class="font-display italic text-[13px] text-ink-mute">
          Editing your details lands in a later release.
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
