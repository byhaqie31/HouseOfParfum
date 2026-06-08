<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

const auth = useAuthStore()
const route = useRoute()
const { worldFor } = useScentWorld()

// A gentle daily tint for active nav + the account avatar.
const houseWorld = worldFor(() => familyOfTheHour())

const nav = [
  { to: '/user/today', label: 'Today', icon: 'lucide:home' },
  { to: '/user/wardrobe', label: 'Shelf', icon: 'lucide:library' },
  { to: '/user/journal', label: 'Journal', icon: 'lucide:calendar-days' },
  { to: '/user/perfume', label: 'Discover', icon: 'lucide:compass' },
  { to: '/user/almanac', label: 'Almanac', icon: 'lucide:book-open' },
  { to: '/user/spectrum', label: 'Spectrum', icon: 'lucide:palette' },
]

// The four daily surfaces for the mobile bottom bar (Add is the raised centre).
const bottomNav = [
  { to: '/user/today', label: 'Today', icon: 'lucide:home' },
  { to: '/user/wardrobe', label: 'Shelf', icon: 'lucide:library' },
  { to: '/user/journal', label: 'Journal', icon: 'lucide:calendar-days' },
  { to: '/user/almanac', label: 'Almanac', icon: 'lucide:book-open' },
]

function isActive(to: string) {
  if (to === '/user/today') return route.path === '/user/today'
  return route.path.startsWith(to)
}

const initials = computed(() => {
  const name = auth.user?.name?.trim()
  if (!name) return ''
  return name.split(/\s+/).slice(0, 2).map((p: string) => p[0]?.toUpperCase() ?? '').join('')
})

// Admins get a shortcut into the back-office (mirrors the admin shell's "View the app").
const isAdmin = computed(() => auth.user?.role === 'admin')
</script>

<template>
  <!-- The authenticated shell is auth- + canvas-mode driven (client state), so
       it renders client-side: avoids SSR/after-dark hydration mismatches and the
       auth middleware gates these routes client-side anyway. -->
  <ClientOnly>
    <div class="min-h-screen" style="background: var(--color-canvas);">
    <!-- ── Web sidebar (lg+) ───────────────────────────────────────────── -->
    <aside
      class="fixed inset-y-0 left-0 z-40 hidden w-62 flex-col border-r px-5 py-6 lg:flex"
      style="border-color: var(--color-rule); background: var(--color-surface);"
    >
      <NuxtLink to="/user/today" class="block px-1">
        <div class="fd leading-none" style="font-size: 22px;">House of Parfum</div>
        <div class="kicker mt-1.5">Your vanity</div>
      </NuxtLink>

      <nav class="mt-8 flex flex-col gap-1">
        <NuxtLink
          v-for="item in nav"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 rounded-field px-3 py-2.5"
          :style="isActive(item.to)
            ? { background: houseWorld.soft, color: houseWorld.accent }
            : { color: 'var(--color-ink-soft)' }"
        >
          <Icon :name="item.icon" size="19" />
          <span class="fb" style="font-size: 14px;">{{ item.label }}</span>
        </NuxtLink>
      </nav>

      <NuxtLink
        to="/user/wardrobe/add"
        class="mt-5 flex items-center justify-center gap-2 rounded-field py-3"
        :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
      >
        <Icon name="lucide:plus" size="18" />
        <span class="fb" style="font-size: 14px; font-weight: 700;">Add a bottle</span>
      </NuxtLink>

      <div class="mt-auto flex flex-col gap-3 pt-5">
        <PortalSwitch v-if="isAdmin" current="app" />

        <CanvasToggle variant="row" />
        <NuxtLink
          to="/user/profile"
          class="flex items-center gap-3 rounded-field border px-3 py-2.5"
          style="border-color: var(--color-rule);"
        >
          <span
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
            :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
          >
            <span class="fd" style="font-size: 13px;">{{ initials || '·' }}</span>
          </span>
          <span class="min-w-0">
            <span class="fb block truncate" style="font-size: 13px; color: var(--color-ink);">{{ auth.user?.name || 'Friend' }}</span>
            <span class="fb block truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ auth.user?.email || '—' }}</span>
          </span>
        </NuxtLink>
      </div>
    </aside>

    <!-- ── Mobile top bar (below lg) ───────────────────────────────────── -->
    <header
      class="sticky top-0 z-30 flex items-center justify-between border-b px-5 py-3 lg:hidden"
      style="border-color: var(--color-rule); background: color-mix(in oklab, var(--color-canvas) 88%, transparent); backdrop-filter: blur(12px);"
    >
      <NuxtLink to="/user/today" class="fd" style="font-size: 18px;">House of Parfum</NuxtLink>
      <div class="flex items-center gap-2">
        <NuxtLink
          v-if="isAdmin"
          to="/admin"
          class="flex h-10 w-10 items-center justify-center rounded-full border"
          style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink-soft);"
          aria-label="Admin portal"
        >
          <Icon name="lucide:layout-dashboard" size="18" />
        </NuxtLink>
        <CanvasToggle variant="icon" />
        <NuxtLink
          to="/user/profile"
          class="flex h-10 w-10 items-center justify-center rounded-full"
          :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
          aria-label="Profile"
        >
          <span class="fd" style="font-size: 13px;">{{ initials || '·' }}</span>
        </NuxtLink>
      </div>
    </header>

    <!-- ── Main content ────────────────────────────────────────────────── -->
    <main class="lg:pl-62">
      <div class="mx-auto w-full max-w-330 px-5 pb-28 pt-5 md:px-8 md:pt-8 lg:pb-12">
        <slot />
      </div>
    </main>

    <!-- ── Mobile bottom nav (below lg) ────────────────────────────────── -->
    <nav
      class="fixed inset-x-0 bottom-0 z-40 flex items-stretch justify-around border-t px-2 pb-[env(safe-area-inset-bottom)] pt-1.5 lg:hidden"
      style="border-color: var(--color-rule); background: color-mix(in oklab, var(--color-canvas) 92%, transparent); backdrop-filter: blur(14px);"
    >
      <NuxtLink
        v-for="item in bottomNav.slice(0, 2)"
        :key="item.to"
        :to="item.to"
        class="flex flex-1 flex-col items-center gap-0.5 py-1.5"
        :style="{ color: isActive(item.to) ? houseWorld.accent : 'var(--color-ink-mute)' }"
      >
        <Icon :name="item.icon" size="21" />
        <span class="fm" style="font-size: 9px; letter-spacing: 0.06em;">{{ item.label }}</span>
      </NuxtLink>

      <!-- raised centre Add -->
      <div class="flex flex-1 items-start justify-center">
        <NuxtLink
          to="/user/wardrobe/add"
          class="-mt-5 flex h-14 w-14 items-center justify-center rounded-full shadow-lg"
          :style="{ background: houseWorld.gradient, color: houseWorld.onGrad, boxShadow: `0 10px 24px -10px ${houseWorld.c2}` }"
          aria-label="Add a bottle"
        >
          <Icon name="lucide:plus" size="24" />
        </NuxtLink>
      </div>

      <NuxtLink
        v-for="item in bottomNav.slice(2)"
        :key="item.to"
        :to="item.to"
        class="flex flex-1 flex-col items-center gap-0.5 py-1.5"
        :style="{ color: isActive(item.to) ? houseWorld.accent : 'var(--color-ink-mute)' }"
      >
        <Icon :name="item.icon" size="21" />
        <span class="fm" style="font-size: 9px; letter-spacing: 0.06em;">{{ item.label }}</span>
      </NuxtLink>
    </nav>

    <!-- global log-wear modal, opened by any "log" action -->
    <LogWearModal />
    </div>
    <template #fallback>
      <div class="flex min-h-screen items-center justify-center" style="background: var(--color-canvas);">
        <div class="fd animate-pulse" style="font-size: 22px; color: var(--color-ink-mute);">House of Parfum</div>
      </div>
    </template>
  </ClientOnly>
</template>
