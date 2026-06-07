<template>
  <div>
    <div class="max-w-4xl mx-auto">
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
              <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft pt-2">
                Name
              </dt>
              <dd class="mt-1 sm:mt-0">
                <input
                  v-model="editName"
                  type="text"
                  maxlength="255"
                  class="w-full bg-paper border border-rule px-3 py-2 text-[14px] text-ink focus:outline-none focus:border-accent transition-colors"
                >
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
              <dd class="mt-1 sm:mt-0 text-[14px] text-ink">
                {{ memberSinceFull }}
              </dd>
            </div>
          </dl>

          <!-- Save row -->
          <div class="mt-6 flex items-center justify-end gap-4">
            <span
              v-if="accountError"
              class="font-mono text-[9px] uppercase tracking-[0.18em] text-red-500"
            >
              {{ accountError }}
            </span>
            <span
              v-else-if="accountSaved"
              class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep"
            >
              Saved
            </span>
            <button
              type="button"
              :disabled="accountSaving"
              class="font-mono text-[10px] uppercase tracking-[0.18em] border px-5 py-2 transition-colors"
              :class="accountSaving
                ? 'text-ink-mute border-rule cursor-not-allowed'
                : 'text-ink border-ink hover:border-accent hover:text-accent-deep'"
              @click="saveAccount"
            >
              {{ accountSaving ? 'Saving…' : 'Save' }}
            </button>
          </div>
        </section>

        <!-- Preferences -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Preferences
          </p>

          <div class="divide-y divide-rule">
            <!-- Fragrance families -->
            <div class="py-4 first:pt-0">
              <p class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
                Fragrance families
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="family in FRAGRANCE_FAMILIES"
                  :key="family"
                  type="button"
                  class="pref-chip"
                  :class="{ 'pref-chip--active': prefs.families.includes(family) }"
                  @click="toggleFamily(family)"
                >
                  {{ family }}
                </button>
              </div>
            </div>

            <!-- Seasonal leaning -->
            <div class="py-4">
              <p class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
                Seasonal leaning
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="season in SEASONS"
                  :key="season"
                  type="button"
                  class="pref-chip"
                  :class="{ 'pref-chip--active': prefs.season === season }"
                  @click="prefs.season = prefs.season === season ? '' : season"
                >
                  {{ season }}
                </button>
              </div>
            </div>

            <!-- Signature scents -->
            <div class="py-4 last:pb-0">
              <p class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft mb-3">
                Signature scents
              </p>
              <!-- Existing tags -->
              <div v-if="prefs.signatureScents.length" class="flex flex-wrap gap-2 mb-3">
                <span
                  v-for="(scent, idx) in prefs.signatureScents"
                  :key="scent"
                  class="inline-flex items-center gap-1.5 pref-chip pref-chip--active"
                >
                  {{ scent }}
                  <button
                    type="button"
                    class="text-accent-deep/60 hover:text-accent-deep transition-colors leading-none"
                    :aria-label="`Remove ${scent}`"
                    @click="removeScent(idx)"
                  >
                    ×
                  </button>
                </span>
              </div>
              <!-- Add input -->
              <div class="flex gap-2">
                <input
                  v-model="newScent"
                  type="text"
                  placeholder="e.g. Bleu de Chanel, Tobacco Vanille…"
                  maxlength="80"
                  class="min-w-0 flex-1 bg-paper border border-rule px-3 py-2 text-[13px] text-ink placeholder:text-ink-mute focus:outline-none focus:border-accent transition-colors"
                  @keydown.enter.prevent="addScent"
                >
                <button
                  type="button"
                  class="shrink-0 font-mono text-[9px] uppercase tracking-[0.18em] border border-rule px-4 py-2 text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                  @click="addScent"
                >
                  Add
                </button>
              </div>
            </div>
          </div>

          <!-- Save row -->
          <div class="mt-6 flex items-center justify-end gap-4">
            <span
              v-if="prefsSaved"
              class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep"
            >
              Saved
            </span>
            <button
              type="button"
              :disabled="prefsSaving"
              class="font-mono text-[10px] uppercase tracking-[0.18em] border px-5 py-2 transition-colors"
              :class="prefsSaving
                ? 'text-ink-mute border-rule cursor-not-allowed'
                : 'text-ink border-ink hover:border-accent hover:text-accent-deep'"
              @click="savePrefs"
            >
              {{ prefsSaving ? 'Saving…' : 'Save' }}
            </button>
          </div>
        </section>

        <!-- Notifications -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Notifications
          </p>

          <dl class="divide-y divide-rule">
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0">
              <div class="min-w-0">
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
                class="relative inline-flex shrink-0 h-6 w-11 items-center border transition-colors"
                :class="prefs.dailyNudge ? 'bg-accent-soft border-accent' : 'bg-paper border-rule'"
                @click="prefs.dailyNudge = !prefs.dailyNudge; saveNotification()"
              >
                <span
                  class="inline-block h-4 w-4 bg-ink transition-transform"
                  :class="prefs.dailyNudge ? 'translate-x-6' : 'translate-x-1'"
                />
              </button>
            </div>

            <div class="flex items-center justify-between gap-x-6 py-4 last:pb-0">
              <div class="min-w-0">
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
                class="relative inline-flex shrink-0 h-6 w-11 items-center border transition-colors"
                :class="prefs.weeklyRecap ? 'bg-accent-soft border-accent' : 'bg-paper border-rule'"
                @click="prefs.weeklyRecap = !prefs.weeklyRecap; saveNotification()"
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
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0 last:pb-0">
              <div class="min-w-0">
                <dt class="font-display font-medium text-[10px] uppercase tracking-[0.22em] text-ink-soft">
                  Canvas
                </dt>
                <dd class="mt-1 font-display italic text-[13px] text-ink-mute">
                  Daytime, or an after-dark canvas that lets the scent gradients glow.
                </dd>
              </div>
              <div class="w-44 shrink-0">
                <CanvasToggle variant="row" />
              </div>
            </div>
          </dl>
        </section>

        <!-- Data & privacy -->
        <section class="rounded-2xl border border-rule bg-paper-deep p-6 sm:p-8">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            Data &amp; privacy
          </p>

          <dl class="divide-y divide-rule">
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0">
              <div class="min-w-0">
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
                class="shrink-0 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute border border-rule px-4 py-2 cursor-not-allowed"
              >
                Soon
              </button>
            </div>

            <div class="flex items-center justify-between gap-x-6 py-4 last:pb-0">
              <div class="min-w-0">
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
                class="shrink-0 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute border border-rule px-4 py-2 cursor-not-allowed"
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
definePageMeta({ layout: 'app', middleware: 'auth' })

const auth = useAuthStore()
const router = useRouter()
const api = useApi()

const FRAGRANCE_FAMILIES = [
  'Floral', 'Woody', 'Citrus', 'Oriental', 'Gourmand',
  'Fresh', 'Spicy', 'Aquatic', 'Chypre', 'Fougère',
]
const SEASONS = ['Spring', 'Summer', 'Autumn', 'Winter', 'Year-round']

type Prefs = {
  dailyNudge: boolean
  weeklyRecap: boolean
  families: string[]
  season: string
  signatureScents: string[]
}

const prefs = reactive<Prefs>({
  dailyNudge: false,
  weeklyRecap: false,
  families: [],
  season: '',
  signatureScents: [],
})

const applyPrefs = (source: Record<string, any> | null | undefined) => {
  if (!source) return
  if (Array.isArray(source.families)) prefs.families = source.families
  if (typeof source.season === 'string') prefs.season = source.season
  if (Array.isArray(source.signatureScents)) prefs.signatureScents = source.signatureScents
  if (typeof source.dailyNudge === 'boolean') prefs.dailyNudge = source.dailyNudge
  if (typeof source.weeklyRecap === 'boolean') prefs.weeklyRecap = source.weeklyRecap
}

onMounted(async () => {
  // Seed from cached user immediately so the page isn't blank
  applyPrefs(auth.user?.preferences)
  editName.value = auth.user?.name ?? ''

  // Refresh from DB in the background
  try {
    const freshUser = await api.get('/user')
    auth.setUser(freshUser)
    applyPrefs(freshUser.preferences)
    editName.value = freshUser.name ?? editName.value
  } catch {}
})

// Preferences save
const prefsSaved = ref(false)
const prefsSaving = ref(false)

const savePrefs = async () => {
  prefsSaving.value = true
  prefsSaved.value = false
  try {
    const updated = await api.patch('/user', { preferences: { ...prefs } })
    auth.setUser({ ...auth.user, ...updated })
    prefsSaved.value = true
    setTimeout(() => { prefsSaved.value = false }, 3000)
  } finally {
    prefsSaving.value = false
  }
}

// Notification toggles auto-save to DB
const saveNotification = async () => {
  try {
    const updated = await api.patch('/user', { preferences: { ...prefs } })
    auth.setUser({ ...auth.user, ...updated })
  } catch {}
}

// Signature scents
const newScent = ref('')

const addScent = () => {
  const s = newScent.value.trim()
  if (!s || prefs.signatureScents.includes(s)) return
  prefs.signatureScents.push(s)
  newScent.value = ''
}

const removeScent = (idx: number) => {
  prefs.signatureScents.splice(idx, 1)
}

const toggleFamily = (family: string) => {
  const idx = prefs.families.indexOf(family)
  if (idx === -1) prefs.families.push(family)
  else prefs.families.splice(idx, 1)
}

// Account save
const editName = ref('')
const accountSaving = ref(false)
const accountSaved = ref(false)
const accountError = ref('')

const saveAccount = async () => {
  const name = editName.value.trim()
  if (!name) return
  accountSaving.value = true
  accountSaved.value = false
  accountError.value = ''
  try {
    const updated = await api.patch('/user', { name })
    auth.setUser({ ...auth.user, ...updated })
    accountSaved.value = true
    setTimeout(() => { accountSaved.value = false }, 3000)
  } catch {
    accountError.value = 'Could not save. Please try again.'
  } finally {
    accountSaving.value = false
  }
}

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
  return new Date(created).toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
})

const handleSignOut = () => {
  auth.logout()
  router.push('/auth/login')
}
</script>

<style scoped>
.pref-chip {
  padding: 0.375rem 0.875rem;
  border: 1px solid var(--color-rule);
  font-family: var(--font-mono);
  font-size: 9px;
  text-transform: uppercase;
  letter-spacing: 0.18em;
  color: var(--color-ink-soft);
  background: var(--color-paper);
  transition: color 0.15s, background-color 0.15s, border-color 0.15s;
}

.pref-chip:hover {
  color: var(--color-ink);
  border-color: var(--color-ink-soft);
}

.pref-chip--active {
  color: var(--color-accent-deep);
  background: var(--color-accent-soft);
  border-color: var(--color-accent);
}
</style>
