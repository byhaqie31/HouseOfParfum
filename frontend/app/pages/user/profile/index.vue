<template>
  <div>
    <div class="max-w-4xl mx-auto">
      <!-- Greeting eyebrow -->
      <div class="kicker">Your account</div>

      <!-- Hero -->
      <div class="mt-4 flex items-center gap-4">
        <span
          class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full"
          :style="{ background: world.gradient, color: world.onGrad }"
        >
          <span class="fd" style="font-size: 20px;">{{ (auth.user?.name?.trim()?.split(/\s+/).slice(0, 2).map((p) => p[0]?.toUpperCase() ?? '').join('')) || '·' }}</span>
        </span>
        <div class="min-w-0">
          <h1 class="fd" style="font-size: clamp(30px, 5vw, 44px); line-height: 1.02; color: var(--color-ink);">
            <template v-if="auth.user?.name">
              {{ auth.user.name }}<em style="color: var(--color-ink-soft);">.</em>
            </template>
            <template v-else>
              Friend<em style="color: var(--color-ink-soft);">.</em>
            </template>
          </h1>
          <p class="fm mt-2 uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-mute);">
            {{ auth.user?.email || '—' }}
            <template v-if="memberSinceShort">
              <span class="mx-1" :style="{ color: world.accent }">·</span>
              Member since {{ memberSinceShort }}
            </template>
          </p>
        </div>
      </div>

      <!-- Section divider with scent accent -->
      <div class="relative mt-10 mb-10 border-b" style="border-color: var(--color-rule);">
        <div class="absolute -bottom-px left-0 h-px w-20" :style="{ background: world.accent }" />
      </div>

      <div class="space-y-6">
        <!-- Account -->
        <section class="rounded-panel border p-6 sm:p-8" style="border-color: var(--color-rule); background: var(--color-surface);">
          <p class="fm uppercase mb-6" style="font-size: 11px; letter-spacing: 0.22em;" :style="{ color: world.accent }">
            Account
          </p>

          <dl class="divide-y" style="border-color: var(--color-rule);">
            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 first:pt-0">
              <dt class="fm uppercase pt-2" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Name
              </dt>
              <dd class="mt-1 sm:mt-0">
                <input
                  v-model="editName"
                  type="text"
                  maxlength="255"
                  class="w-full rounded-field border px-3.5 py-2.5 fb focus:outline-none"
                  style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink); font-size: 14px;"
                >
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4">
              <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Email
              </dt>
              <dd class="fb mt-1 sm:mt-0" style="font-size: 14px; color: var(--color-ink);">
                {{ auth.user?.email || '—' }}
              </dd>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[180px_1fr] gap-x-8 py-4 last:pb-0">
              <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Member since
              </dt>
              <dd class="fb mt-1 sm:mt-0" style="font-size: 14px; color: var(--color-ink);">
                {{ memberSinceFull }}
              </dd>
            </div>
          </dl>

          <!-- Save row -->
          <div class="mt-6 flex items-center justify-end gap-4">
            <span
              v-if="accountError"
              class="fm uppercase"
              style="font-size: 10px; letter-spacing: 0.16em; color: oklch(0.62 0.2 25);"
            >
              {{ accountError }}
            </span>
            <span
              v-else-if="accountSaved"
              class="fm uppercase"
              style="font-size: 10px; letter-spacing: 0.16em;"
              :style="{ color: world.accent }"
            >
              Saved
            </span>
            <button
              type="button"
              :disabled="accountSaving"
              class="flex items-center gap-2 rounded-full px-6 py-2.5 disabled:opacity-40 disabled:cursor-not-allowed"
              :style="{ background: world.gradient, color: world.onGrad }"
              @click="saveAccount"
            >
              <span class="fb" style="font-weight: 700; font-size: 13px;">{{ accountSaving ? 'Saving…' : 'Save' }}</span>
            </button>
          </div>
        </section>

        <!-- Preferences -->
        <section class="rounded-panel border p-6 sm:p-8" style="border-color: var(--color-rule); background: var(--color-surface);">
          <p class="fm uppercase mb-6" style="font-size: 11px; letter-spacing: 0.22em;" :style="{ color: world.accent }">
            Preferences
          </p>

          <div class="divide-y" style="border-color: var(--color-rule);">
            <!-- Fragrance families -->
            <div class="py-4 first:pt-0">
              <p class="fm uppercase mb-3" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Fragrance families
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="family in FRAGRANCE_FAMILIES"
                  :key="family"
                  type="button"
                  class="pref-chip"
                  :class="{ 'pref-chip--active': prefs.families.includes(family) }"
                  :style="prefs.families.includes(family) ? { background: world.soft, color: world.accent, borderColor: world.accent } : {}"
                  @click="toggleFamily(family)"
                >
                  {{ family }}
                </button>
              </div>
            </div>

            <!-- Seasonal leaning -->
            <div class="py-4">
              <p class="fm uppercase mb-3" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Seasonal leaning
              </p>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="season in SEASONS"
                  :key="season"
                  type="button"
                  class="pref-chip"
                  :class="{ 'pref-chip--active': prefs.season === season }"
                  :style="prefs.season === season ? { background: world.soft, color: world.accent, borderColor: world.accent } : {}"
                  @click="prefs.season = prefs.season === season ? '' : season"
                >
                  {{ season }}
                </button>
              </div>
            </div>

            <!-- Signature scents -->
            <div class="py-4 last:pb-0">
              <p class="fm uppercase mb-3" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                Signature scents
              </p>
              <!-- Existing tags -->
              <div v-if="prefs.signatureScents.length" class="flex flex-wrap gap-2 mb-3">
                <span
                  v-for="(scent, idx) in prefs.signatureScents"
                  :key="scent"
                  class="inline-flex items-center gap-1.5 pref-chip pref-chip--active"
                  :style="{ background: world.soft, color: world.accent, borderColor: world.accent }"
                >
                  {{ scent }}
                  <button
                    type="button"
                    class="leading-none transition-opacity hover:opacity-100"
                    style="opacity: 0.6;"
                    :style="{ color: world.accent }"
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
                  class="min-w-0 flex-1 rounded-field border px-3.5 py-2.5 fb focus:outline-none"
                  style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink); font-size: 13px;"
                  @keydown.enter.prevent="addScent"
                >
                <button
                  type="button"
                  class="fb shrink-0 rounded-field border px-5 py-2.5 transition-colors"
                  style="border-color: var(--color-rule); background: var(--color-surface-2); color: var(--color-ink-soft); font-size: 13px; font-weight: 700;"
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
              class="fm uppercase"
              style="font-size: 10px; letter-spacing: 0.16em;"
              :style="{ color: world.accent }"
            >
              Saved
            </span>
            <button
              type="button"
              :disabled="prefsSaving"
              class="flex items-center gap-2 rounded-full px-6 py-2.5 disabled:opacity-40 disabled:cursor-not-allowed"
              :style="{ background: world.gradient, color: world.onGrad }"
              @click="savePrefs"
            >
              <span class="fb" style="font-weight: 700; font-size: 13px;">{{ prefsSaving ? 'Saving…' : 'Save' }}</span>
            </button>
          </div>
        </section>

        <!-- Notifications -->
        <section class="rounded-panel border p-6 sm:p-8" style="border-color: var(--color-rule); background: var(--color-surface);">
          <p class="fm uppercase mb-6" style="font-size: 11px; letter-spacing: 0.22em;" :style="{ color: world.accent }">
            Notifications
          </p>

          <dl class="divide-y" style="border-color: var(--color-rule);">
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0">
              <div class="min-w-0">
                <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                  Daily Today nudge
                </dt>
                <dd class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-mute);">
                  A gentle morning prompt to pick the day's wear.
                </dd>
              </div>
              <button
                type="button"
                role="switch"
                :aria-checked="prefs.dailyNudge"
                class="relative inline-flex shrink-0 h-6 w-11 items-center rounded-full border transition-colors"
                :style="prefs.dailyNudge
                  ? { background: world.gradient, borderColor: world.accent }
                  : { background: 'var(--color-surface-2)', borderColor: 'var(--color-rule)' }"
                @click="prefs.dailyNudge = !prefs.dailyNudge; saveNotification()"
              >
                <span
                  class="inline-block h-4 w-4 rounded-full transition-transform"
                  :style="{ background: prefs.dailyNudge ? world.onGrad : 'var(--color-ink-mute)' }"
                  :class="prefs.dailyNudge ? 'translate-x-6' : 'translate-x-1'"
                />
              </button>
            </div>

            <div class="flex items-center justify-between gap-x-6 py-4 last:pb-0">
              <div class="min-w-0">
                <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                  Weekly journal recap
                </dt>
                <dd class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-mute);">
                  A Sunday note of what you wore this week.
                </dd>
              </div>
              <button
                type="button"
                role="switch"
                :aria-checked="prefs.weeklyRecap"
                class="relative inline-flex shrink-0 h-6 w-11 items-center rounded-full border transition-colors"
                :style="prefs.weeklyRecap
                  ? { background: world.gradient, borderColor: world.accent }
                  : { background: 'var(--color-surface-2)', borderColor: 'var(--color-rule)' }"
                @click="prefs.weeklyRecap = !prefs.weeklyRecap; saveNotification()"
              >
                <span
                  class="inline-block h-4 w-4 rounded-full transition-transform"
                  :style="{ background: prefs.weeklyRecap ? world.onGrad : 'var(--color-ink-mute)' }"
                  :class="prefs.weeklyRecap ? 'translate-x-6' : 'translate-x-1'"
                />
              </button>
            </div>
          </dl>
        </section>

        <!-- Appearance -->
        <section class="rounded-panel border p-6 sm:p-8" style="border-color: var(--color-rule); background: var(--color-surface);">
          <p class="fm uppercase mb-6" style="font-size: 11px; letter-spacing: 0.22em;" :style="{ color: world.accent }">
            Appearance
          </p>

          <dl class="divide-y" style="border-color: var(--color-rule);">
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0 last:pb-0">
              <div class="min-w-0">
                <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                  Canvas
                </dt>
                <dd class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-mute);">
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
        <section class="rounded-panel border p-6 sm:p-8" style="border-color: var(--color-rule); background: var(--color-surface);">
          <p class="fm uppercase mb-6" style="font-size: 11px; letter-spacing: 0.22em;" :style="{ color: world.accent }">
            Data &amp; privacy
          </p>

          <dl class="divide-y" style="border-color: var(--color-rule);">
            <div class="flex items-center justify-between gap-x-6 py-4 first:pt-0">
              <div class="min-w-0">
                <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                  Export your collection
                </dt>
                <dd class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-mute);">
                  A JSON of your shelf, journal, and notes.
                </dd>
              </div>
              <button
                type="button"
                disabled
                class="fb shrink-0 rounded-full border px-4 py-2 cursor-not-allowed"
                style="border-color: var(--color-rule); color: var(--color-ink-mute); font-size: 12px; font-weight: 700;"
              >
                Soon
              </button>
            </div>

            <div class="flex items-center justify-between gap-x-6 py-4 last:pb-0">
              <div class="min-w-0">
                <dt class="fm uppercase" style="font-size: 10px; letter-spacing: 0.18em; color: var(--color-ink-soft);">
                  Delete account
                </dt>
                <dd class="fb mt-1 italic" style="font-size: 13px; color: var(--color-ink-mute);">
                  Removes your shelf, journal, and preferences. Permanent.
                </dd>
              </div>
              <button
                type="button"
                disabled
                class="fb shrink-0 rounded-full border px-4 py-2 cursor-not-allowed"
                style="border-color: var(--color-rule); color: var(--color-ink-mute); font-size: 12px; font-weight: 700;"
              >
                Soon
              </button>
            </div>
          </dl>
        </section>
      </div>

      <!-- Session footer -->
      <div class="mt-10 pt-6 border-t flex flex-wrap items-center justify-between gap-6" style="border-color: var(--color-rule);">
        <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-mute);">
          Signed in as {{ auth.user?.email || '—' }}
        </p>
        <button
          type="button"
          class="fb rounded-full border px-5 py-2 transition-colors"
          style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink-soft); font-size: 13px; font-weight: 700;"
          @click="handleSignOut"
        >
          Sign out
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

definePageMeta({ layout: 'app', middleware: 'auth' })

const auth = useAuthStore()
const router = useRouter()
const api = useApi()

// The day's scent colour tints the avatar, accents, and primary CTAs.
const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())

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
  padding: 0.4rem 0.9rem;
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-chip);
  font-family: var(--font-data);
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  font-size: 11px;
  letter-spacing: 0.04em;
  color: var(--color-ink-soft);
  background: var(--color-surface);
  transition: color 0.15s, background-color 0.15s, border-color 0.15s;
}

/* Inactive hover only — the active state is tinted inline with the day's scent world. */
.pref-chip:not(.pref-chip--active):hover {
  color: var(--color-ink);
  border-color: var(--color-ink-soft);
}
</style>
