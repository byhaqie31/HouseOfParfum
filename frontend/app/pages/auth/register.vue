<template>
  <div class="grid min-h-screen grid-cols-1 md:grid-cols-12" style="background: var(--color-canvas);">

    <!-- ── Brand / visual panel (shares the login hero) ─────────────────────-->
    <aside class="relative hidden overflow-hidden md:col-span-7 md:flex md:flex-col md:justify-between md:p-12 lg:p-16">
      <div class="absolute inset-0" style="background:
        radial-gradient(110% 90% at 18% 12%, oklch(0.96 0.06 350 / 0.85) 0%, transparent 55%),
        linear-gradient(150deg, oklch(0.86 0.11 350) 0%, oklch(0.74 0.11 38) 100%);" />

      <img
        v-show="!heroFailed"
        :src="heroSrc"
        alt=""
        aria-hidden="true"
        class="absolute inset-0 h-full w-full object-cover"
        @error="heroFailed = true"
      >

      <div class="absolute inset-0" style="background:
        linear-gradient(to top, color-mix(in oklab, oklch(0.16 0.03 320) 64%, transparent) 0%, transparent 50%),
        linear-gradient(to bottom, color-mix(in oklab, oklch(0.16 0.03 320) 34%, transparent) 0%, transparent 24%);" />

      <NuxtLink to="/about" class="fd relative z-10" style="font-size: 22px; color: oklch(0.99 0.004 90);">
        House of Parfum
      </NuxtLink>

      <div class="relative z-10 max-w-xl">
        <h1 class="fd" style="font-size: clamp(36px, 4.4vw, 58px); line-height: 1.06; color: oklch(0.99 0.004 90);">
          Begin your shelf.
          <em style="color: oklch(0.99 0.004 90 / 0.78);">One bottle at a time.</em>
        </h1>
        <p class="fb mt-7 max-w-md" style="font-size: 15px; line-height: 1.6; color: oklch(0.99 0.004 90 / 0.82);">
          A quiet space to record what you wear, how it sits with you, and what to reach for tomorrow.
        </p>
        <NuxtLink
          to="/about"
          class="fm mt-9 inline-flex items-center gap-2 uppercase"
          style="font-size: 11px; letter-spacing: 0.16em; color: oklch(0.99 0.004 90);"
        >
          Learn more
          <Icon name="lucide:arrow-right" size="14" />
        </NuxtLink>
      </div>

      <p class="fm relative z-10" style="font-size: 11px; color: oklch(0.99 0.004 90 / 0.7);">
        © {{ new Date().getFullYear() }} Axel Nova Ventures
      </p>
    </aside>

    <!-- ── Form panel ───────────────────────────────────────────────────── -->
    <section class="col-span-1 flex items-center justify-center px-6 py-12 md:col-span-5 md:py-16">
      <div class="w-full max-w-sm">
        <NuxtLink to="/about" class="fd mb-10 block text-center md:hidden" style="font-size: 24px; color: var(--color-ink);">
          House of Parfum
        </NuxtLink>

        <h2 class="fd" style="font-size: 30px; line-height: 1.1; color: var(--color-ink);">Create an account</h2>
        <p class="fb mt-2" style="font-size: 14px; color: var(--color-ink-soft);">Takes less than a minute.</p>

        <form class="mt-8 space-y-4" @submit.prevent="handleRegister">
          <div>
            <label for="name" class="fm mb-2 block uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);">Name</label>
            <input id="name" v-model="form.name" type="text" required autocomplete="name" placeholder="Your name" class="auth-input fb">
          </div>

          <div>
            <label for="email" class="fm mb-2 block uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);">Email</label>
            <input id="email" v-model="form.email" type="email" required autocomplete="email" placeholder="you@example.com" class="auth-input fb">
          </div>

          <div>
            <label for="password" class="fm mb-2 block uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);">Password</label>
            <input id="password" v-model="form.password" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input fb">
          </div>

          <div>
            <label for="password_confirmation" class="fm mb-2 block uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-soft);">Confirm password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password" required autocomplete="new-password" placeholder="••••••••" class="auth-input fb">
          </div>

          <p v-if="error" class="fb" style="font-size: 13px; color: oklch(0.6 0.15 25);">{{ error }}</p>

          <button type="submit" :disabled="submitting" class="auth-submit fm uppercase">
            {{ submitting ? 'Creating account…' : 'Create account' }}
          </button>
        </form>

        <p class="fm mt-8 text-center uppercase" style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink-soft);">
          Already a member?
          <NuxtLink to="/auth/login" class="ml-1" style="color: var(--color-accent-deep);">Sign in</NuxtLink>
        </p>

        <NuxtLink
          to="/about"
          class="fm mt-8 flex items-center justify-center gap-2 uppercase md:hidden"
          style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink-soft);"
        >
          Learn more
          <Icon name="lucide:arrow-right" size="14" />
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: 'guest' })

// Shares the login hero. Want a distinct image for register? Save it as
// public/auth/register-hero.png and point heroSrc at it.
const heroSrc = '/auth/login-hero.png'
const heroFailed = ref(false)

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
    await router.push('/user/today')
  } catch (_e) {
    error.value = 'Registration failed. Please check your details.'
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.auth-input {
  width: 100%;
  background: var(--color-surface);
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-field);
  padding: 12px 14px;
  font-size: 14px;
  color: var(--color-ink);
  outline: none;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.auth-input::placeholder { color: var(--color-ink-mute); }
.auth-input:focus {
  border-color: var(--color-accent);
  box-shadow: 0 0 0 3px color-mix(in oklab, var(--color-accent) 18%, transparent);
}

.auth-submit {
  width: 100%;
  border-radius: var(--radius-field);
  padding: 14px;
  font-size: 11px;
  letter-spacing: 0.16em;
  color: var(--color-canvas);
  background: linear-gradient(150deg, var(--color-accent) 0%, var(--color-accent-deep) 100%);
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.auth-submit:hover { opacity: 0.92; }
.auth-submit:active { transform: translateY(1px); }
.auth-submit:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
