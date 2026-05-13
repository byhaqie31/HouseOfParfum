<template>
  <div class="min-h-screen grid grid-cols-1 md:grid-cols-12 bg-paper">
    <!-- Brand panel -->
    <aside class="hidden md:flex md:col-span-7 bg-paper-deep p-12 lg:p-16 flex-col justify-between relative overflow-hidden">
      <!-- Watermark: tall apothecary bottle, vertically centred against the right edge -->
      <svg
        class="absolute top-1/2 -translate-y-1/2 right-8 lg:right-12 w-72 lg:w-80 h-auto text-ink pointer-events-none select-none"
        viewBox="0 0 200 280"
        fill="none"
        stroke="currentColor"
        stroke-width="0.8"
        style="opacity: 0.09"
        aria-hidden="true"
      >
        <!-- Cap -->
        <rect x="76" y="6" width="48" height="34" />
        <!-- Collar -->
        <rect x="80" y="40" width="40" height="10" />
        <!-- Neck -->
        <rect x="86" y="50" width="28" height="22" />
        <!-- Body -->
        <rect x="22" y="72" width="156" height="200" rx="2" />
        <!-- Label panel -->
        <rect x="44" y="166" width="112" height="58" />
        <!-- Label rule -->
        <line x1="60" y1="194" x2="140" y2="194" />
        <!-- Inner shoulder line -->
        <line x1="22" y1="100" x2="178" y2="100" stroke-dasharray="2 4" />
      </svg>

      <NuxtLink to="/about" class="relative z-10 font-display text-xl text-ink tracking-tight">
        House of Parfum
      </NuxtLink>

      <div class="relative z-10 max-w-xl">
        <h1 class="font-display text-5xl lg:text-6xl leading-[1.05] text-ink tracking-tight">
          A companion for the bottles you wear,<br>
          <em class="text-ink-soft">and the ones you remember.</em>
        </h1>
        <p class="mt-8 text-base text-ink-soft leading-relaxed max-w-md">
          Log your collection. Note your daily wear. See what suits today &mdash;
          drawn from your shelf, the weather, and your mood.
        </p>
        <NuxtLink
          to="/about"
          class="mt-10 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-ink hover:text-accent transition-colors"
        >
          Learn more
          <Icon name="lucide:arrow-right" class="h-3.5 w-3.5" />
        </NuxtLink>
      </div>

      <p class="relative z-10 font-mono text-[11px] text-ink-mute">
        &copy; {{ new Date().getFullYear() }} Axel Nova Ventures
      </p>
    </aside>

    <!-- Form panel -->
    <section class="col-span-1 md:col-span-5 flex items-center justify-center px-6 py-12 md:py-16">
      <div class="w-full max-w-sm">
        <!-- Mobile brand mark (form panel only on mobile) -->
        <NuxtLink to="/about" class="md:hidden font-display text-2xl text-ink tracking-tight block mb-10 text-center">
          House of Parfum
        </NuxtLink>

        <h2 class="font-display text-3xl text-ink tracking-tight">Welcome back!</h2>
        <p class="mt-2 text-sm text-ink-soft">
          Sign in to your shelf.
        </p>

        <form class="mt-10 space-y-5" @submit.prevent="handleLogin">
          <div>
            <label for="email" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
              Email
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              autocomplete="email"
              class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors"
            >
          </div>

          <div>
            <div class="flex items-baseline justify-between mb-2">
              <label for="password" class="block text-[11px] uppercase tracking-widest text-ink-soft">
                Password
              </label>
              <!-- Placeholder for future /forgot-password link -->
            </div>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              autocomplete="current-password"
              class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors"
            >
          </div>

          <p v-if="error" class="text-sm text-accent">{{ error }}</p>

          <button
            type="submit"
            :disabled="submitting"
            class="w-full bg-ink text-paper text-xs uppercase tracking-widest py-3.5 hover:bg-ink-soft transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ submitting ? 'Signing in…' : 'Sign in' }}
          </button>
        </form>

        <p class="mt-8 text-xs uppercase tracking-widest text-ink-soft text-center">
          New here?
          <NuxtLink to="/register" class="text-ink hover:text-accent transition-colors ml-1">
            Create an account
          </NuxtLink>
        </p>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: 'guest' })

const api = useApi()
const auth = useAuthStore()
const router = useRouter()

const form = reactive({ email: '', password: '' })
const error = ref('')
const submitting = ref(false)

const handleLogin = async () => {
  error.value = ''
  submitting.value = true
  try {
    const data = await api.post('/login', form)
    auth.setToken(data.token)
    auth.setUser(data.user)
    await router.push('/today')
  } catch (_e) {
    error.value = 'Invalid email or password.'
  } finally {
    submitting.value = false
  }
}
</script>
