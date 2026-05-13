<template>
  <div class="min-h-screen grid grid-cols-1 md:grid-cols-12 bg-paper">
    <!-- Brand panel -->
    <aside class="hidden md:flex md:col-span-7 bg-paper-deep p-12 lg:p-16 flex-col justify-between">
      <NuxtLink to="/about" class="font-display text-xl text-ink tracking-tight">
        House of Parfum
      </NuxtLink>

      <div class="max-w-xl">
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

      <p class="font-mono text-[11px] text-ink-mute">
        &copy; {{ new Date().getFullYear() }} AxelNova
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
