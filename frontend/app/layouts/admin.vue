<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const { worldFor } = useScentWorld()

// The admin chrome borrows the day's accent, same as the app shell.
const houseWorld = worldFor(() => familyOfTheHour())

// Back-office IA — English label + smaller italic Malay sub-label. Houses and
// Community are designed-next placeholders (see pages/admin/{brands,community}).
const nav = [
  { to: '/admin', label: 'Overview', my: 'keseluruhan', icon: 'lucide:layout-dashboard', exact: true },
  { to: '/admin/perfumes', label: 'Registry', my: 'daftar', icon: 'lucide:spray-can' },
  { to: '/admin/brands', label: 'Houses', my: 'rumah', icon: 'lucide:building-2' },
  { to: '/admin/users', label: 'Members', my: 'ahli', icon: 'lucide:users' },
  { to: '/admin/almanac', label: 'Almanac', my: 'almanak', icon: 'lucide:book-open' },
  { to: '/admin/community', label: 'Community', my: 'komuniti', icon: 'lucide:messages-square' },
]

const sidebarOpen = ref(false)

function isActive(item: { to: string; exact?: boolean }) {
  if (item.exact) return route.path === item.to
  return route.path === item.to || route.path.startsWith(item.to + '/')
}

const initials = computed(() => {
  const name = auth.user?.name?.trim()
  if (!name) return 'A'
  return name.split(/\s+/).slice(0, 2).map((p: string) => p[0]?.toUpperCase() ?? '').join('')
})

// Close the mobile drawer whenever the route changes.
watch(() => route.path, () => { sidebarOpen.value = false })

async function handleLogout() {
  const api = useApi()
  try { await api.post('/logout', {}) } catch {}
  auth.logout()
  await router.push('/auth/login')
}
</script>

<template>
  <!-- Auth- + canvas-mode driven (client state); render client-side to avoid
       SSR/after-dark hydration mismatches, mirroring the app shell. -->
  <ClientOnly>
    <div class="min-h-screen lg:flex" style="background: var(--color-canvas);">

      <!-- Mobile drawer backdrop -->
      <Transition
        enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0"
      >
        <div
          v-if="sidebarOpen"
          class="fixed inset-0 z-40 lg:hidden"
          style="background: color-mix(in oklab, var(--color-ink) 32%, transparent);"
          @click="sidebarOpen = false"
        />
      </Transition>

      <!-- ── Sidebar ─────────────────────────────────────────────────────── -->
      <aside
        class="fixed inset-y-0 left-0 z-50 flex w-[236px] flex-col border-r px-[18px] py-[26px]
               transition-transform duration-200 ease-in-out
               -translate-x-full lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
        :class="{ 'translate-x-0': sidebarOpen }"
        style="border-color: var(--color-rule); background: var(--color-surface);"
      >
        <!-- Wordmark -->
        <div class="flex items-start justify-between px-2">
          <NuxtLink to="/admin" class="block">
            <div class="fd leading-tight" style="font-size: 20px; color: var(--color-ink);">House of Parfum</div>
            <div
              class="fm mt-1 uppercase"
              :style="{ fontSize: '8.5px', letterSpacing: '0.2em', color: houseWorld.accent }"
            >Admin portal</div>
          </NuxtLink>
          <button
            class="lg:hidden"
            style="color: var(--color-ink-mute);"
            aria-label="Close menu"
            @click="sidebarOpen = false"
          >
            <Icon name="lucide:x" size="18" />
          </button>
        </div>

        <!-- Nav -->
        <nav class="mt-8 flex flex-col gap-[3px]">
          <NuxtLink
            v-for="item in nav"
            :key="item.to"
            :to="item.to"
            class="flex items-center gap-3 rounded-field px-[13px] py-[11px]"
            :style="{ background: isActive(item) ? 'var(--color-surface-2)' : 'transparent' }"
          >
            <span class="flex w-[22px] shrink-0 justify-center">
              <Icon
                :name="item.icon"
                size="19"
                :style="{ color: isActive(item) ? houseWorld.accent : 'var(--color-ink-mute)' }"
              />
            </span>
            <span class="min-w-0">
              <span
                class="fb block leading-tight"
                :style="{ fontSize: '14px', fontWeight: 600, color: isActive(item) ? 'var(--color-ink)' : 'var(--color-ink-soft)' }"
              >{{ item.label }}</span>
              <span class="fb block italic leading-tight" style="font-size: 10px; color: var(--color-ink-mute);">{{ item.my }}</span>
            </span>
          </NuxtLink>
        </nav>

        <!-- Footer: preview · canvas toggle · account -->
        <div class="mt-auto flex flex-col gap-[10px] pt-5">
          <NuxtLink
            to="/user/today"
            class="flex items-center gap-2 px-[13px] py-1 fm uppercase"
            style="font-size: 9.5px; letter-spacing: 0.14em; color: var(--color-ink-mute);"
          >
            <Icon name="lucide:eye" size="13" /> View the app
          </NuxtLink>

          <CanvasToggle variant="row" />

          <div class="flex items-center gap-[11px] rounded-field border px-3 py-2.5" style="border-color: var(--color-rule);">
            <span
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
              :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
            >
              <span class="fd" style="font-size: 12.5px;">{{ initials }}</span>
            </span>
            <div class="min-w-0 flex-1">
              <div class="fb truncate leading-tight" style="font-size: 12.5px; font-weight: 600; color: var(--color-ink);">{{ auth.user?.name || 'Admin' }}</div>
              <div class="fb truncate leading-tight" style="font-size: 10.5px; color: var(--color-ink-mute);">Curation team</div>
            </div>
            <button
              class="shrink-0"
              style="color: var(--color-ink-mute);"
              aria-label="Sign out"
              title="Sign out"
              @click="handleLogout"
            >
              <Icon name="lucide:log-out" size="16" />
            </button>
          </div>
        </div>
      </aside>

      <!-- ── Content column ──────────────────────────────────────────────── -->
      <div class="flex min-w-0 flex-1 flex-col">
        <!-- Mobile top bar (hamburger) -->
        <header
          class="sticky top-0 z-30 flex items-center gap-3 border-b px-5 py-3 lg:hidden"
          style="border-color: var(--color-rule); background: color-mix(in oklab, var(--color-canvas) 88%, transparent); backdrop-filter: blur(12px);"
        >
          <button style="color: var(--color-ink-soft);" aria-label="Open menu" @click="sidebarOpen = true">
            <Icon name="lucide:menu" size="20" />
          </button>
          <NuxtLink to="/admin" class="fd" style="font-size: 17px; color: var(--color-ink);">House of Parfum</NuxtLink>
          <span class="fm ml-auto uppercase" :style="{ fontSize: '9px', letterSpacing: '0.18em', color: houseWorld.accent }">Admin</span>
        </header>

        <main class="mx-auto w-full max-w-[1280px] flex-1 px-5 py-7 md:px-11 md:py-9">
          <slot />
        </main>
      </div>
    </div>

    <template #fallback>
      <div class="flex min-h-screen items-center justify-center" style="background: var(--color-canvas);">
        <div class="fd animate-pulse" style="font-size: 20px; color: var(--color-ink-mute);">House of Parfum · Admin</div>
      </div>
    </template>
  </ClientOnly>
</template>
