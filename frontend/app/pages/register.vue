<template>
  <div class="min-h-screen grid grid-cols-1 md:grid-cols-12 bg-paper">
    <!-- Brand panel -->
    <aside class="hidden md:flex md:col-span-7 bg-paper-deep p-12 lg:p-16 flex-col justify-between">
      <NuxtLink to="/about" class="font-display text-xl text-ink tracking-tight">
        House of Parfum
      </NuxtLink>

      <div class="max-w-xl">
        <h1 class="font-display text-5xl lg:text-6xl leading-[1.05] text-ink tracking-tight">
          Begin your shelf.<br>
          <em class="text-ink-soft">One bottle at a time.</em>
        </h1>
        <p class="mt-8 text-base text-ink-soft leading-relaxed max-w-md">
          A quiet space to record what you wear, how it sits with you, and what to reach for tomorrow.
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
        <NuxtLink to="/about" class="md:hidden font-display text-2xl text-ink tracking-tight block mb-10 text-center">
          House of Parfum
        </NuxtLink>

        <h2 class="font-display text-3xl text-ink tracking-tight">Create an account.</h2>
        <p class="mt-2 text-sm text-ink-soft">
          Takes less than a minute.
        </p>

        <form class="mt-10 space-y-5" @submit.prevent="handleRegister">
          <div>
            <label for="name" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
              Name
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              autocomplete="name"
              class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors"
            >
          </div>

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
            <label for="password" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              autocomplete="new-password"
              class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors"
            >
          </div>

          <div>
            <label for="password_confirmation" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
              Confirm Password
            </label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              required
              autocomplete="new-password"
              class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors"
            >
          </div>

          <p v-if="error" class="text-sm text-accent">{{ error }}</p>

          <button
            type="submit"
            :disabled="submitting"
            class="w-full bg-ink text-paper text-xs uppercase tracking-widest py-3.5 hover:bg-ink-soft transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ submitting ? 'Creating account…' : 'Create account' }}
          </button>
        </form>

        <p class="mt-8 text-xs uppercase tracking-widest text-ink-soft text-center">
          Already a member?
          <NuxtLink to="/" class="text-ink hover:text-accent transition-colors ml-1">
            Sign in
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

const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref('')
const submitting = ref(false)

const handleRegister = async () => {
  error.value = ''
  submitting.value = true
  try {
    const data = await api.post('/register', form)
    auth.setToken(data.token)
    auth.setUser(data.user)
    await router.push('/today')
  } catch (_e) {
    error.value = 'Registration failed. Please check your details.'
  } finally {
    submitting.value = false
  }
}
</script>
