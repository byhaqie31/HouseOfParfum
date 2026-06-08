<template>
  <nav
    class="fixed top-0 right-0 left-0 z-50 border-b backdrop-blur-xl"
    style="background: color-mix(in oklab, var(--color-canvas) 85%, transparent); border-color: var(--color-rule);"
  >
    <div class="mx-auto flex h-14 max-w-300 items-center justify-between px-6">
      <NuxtLink
        to="/"
        class="fd inline-flex items-center gap-2.5"
        style="font-size: 20px; letter-spacing: -0.005em; color: var(--color-ink);"
      >
        <span
          class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-field"
          :style="{ background: world.soft }"
        >
          <img
            src="/favicon/favicon.svg"
            alt=""
            aria-hidden="true"
            class="h-6 w-6 shrink-0"
          >
        </span>
        House of Parfum
      </NuxtLink>

      <!-- Authenticated nav -->
      <template v-if="auth.isLoggedIn">
        <!-- Desktop -->
        <div class="hidden items-center gap-10 md:flex">
          <NuxtLink
            v-for="link in appLinks"
            :key="link.to"
            :to="link.to"
            class="fm nav-link pb-1 uppercase"
            style="font-size: 11px; letter-spacing: 0.2em; color: var(--color-ink-soft);"
          >
            {{ link.label }}
          </NuxtLink>

          <!-- Profile Dropdown -->
          <div ref="profileDropdownRef" class="relative flex items-center">
            <button
              class="nav-icon-btn"
              aria-label="Account menu"
              @click="profileOpen = !profileOpen"
            >
              <Icon name="lucide:user" size="20" />
            </button>

            <Transition
              enter-active-class="transition duration-150 ease-out"
              enter-from-class="opacity-0 -translate-y-1"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition duration-100 ease-in"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 -translate-y-1"
            >
              <div
                v-if="profileOpen"
                class="absolute top-full right-0 mt-3 w-72 overflow-hidden rounded-panel border"
                style="background: var(--color-surface); border-color: var(--color-rule);"
              >
                <!-- Summary header -->
                <div class="px-5 pt-6 pb-6 text-center" style="background: var(--color-surface-2);">
                  <div
                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full"
                    :style="{ background: world.soft, color: world.accent }"
                  >
                    <span
                      v-if="userInitials"
                      class="fd"
                      style="font-size: 18px; letter-spacing: -0.005em;"
                    >
                      {{ userInitials }}
                    </span>
                    <Icon v-else name="lucide:user" size="22" />
                  </div>
                  <p class="fd mt-3" style="font-size: 18px; line-height: 1.15; color: var(--color-ink);">
                    {{ auth.user?.name || 'Friend' }}
                  </p>
                  <p class="fm mt-1 truncate uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-mute);">
                    {{ auth.user?.email || '—' }}
                  </p>
                  <p
                    v-if="memberSinceShort"
                    class="fm mt-2 uppercase"
                    style="font-size: 9px; letter-spacing: 0.18em; color: var(--color-ink-mute);"
                  >
                    Member since {{ memberSinceShort }}
                  </p>

                  <!-- Actions -->
                  <div class="mt-5 space-y-2">
                    <NuxtLink
                      v-if="auth.user?.role === 'admin'"
                      to="/admin"
                      class="fm menu-outline-btn block w-full rounded-field py-2 uppercase"
                      style="font-size: 10px; letter-spacing: 0.18em;"
                      @click="profileOpen = false"
                    >
                      Back to Admin
                    </NuxtLink>
                    <NuxtLink
                      v-else
                      to="/user/profile"
                      class="fm menu-outline-btn block w-full rounded-field py-2 uppercase"
                      style="font-size: 10px; letter-spacing: 0.18em;"
                      @click="profileOpen = false"
                    >
                      View profile
                    </NuxtLink>
                    <button
                      class="fd menu-signout block w-full py-2 italic"
                      style="font-size: 15px;"
                      @click="handleLogout"
                    >
                      Sign out
                    </button>
                  </div>
                </div>
              </div>
            </Transition>
          </div>
        </div>

        <!-- Mobile -->
        <div class="flex items-center gap-4 md:hidden">
          <button
            ref="mobileButtonRef"
            class="nav-icon-btn"
            aria-label="Toggle menu"
            @click="mobileMenuOpen = !mobileMenuOpen"
          >
            <Icon :name="mobileMenuOpen ? 'lucide:x' : 'lucide:menu'" size="20" />
          </button>
        </div>
      </template>

      <!-- Public nav (unauthenticated) -->
      <template v-else>
        <NuxtLink
          to="/auth/login"
          class="fm inline-flex items-center rounded-full px-5 py-2 uppercase"
          style="font-size: 11px; letter-spacing: 0.16em;"
          :style="{ background: world.gradient, color: world.onGrad }"
        >
          Sign in
        </NuxtLink>
      </template>
    </div>

    <!-- Mobile drawer (auth only) -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="auth.isLoggedIn && mobileMenuOpen"
        ref="mobileDrawerRef"
        class="space-y-5 border-b px-6 py-5 backdrop-blur-xl md:hidden"
        style="background: color-mix(in oklab, var(--color-canvas) 95%, transparent); border-color: var(--color-rule);"
      >
        <!-- Profile chip -->
        <div class="flex items-center gap-3">
          <div
            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full"
            :style="{ background: world.soft, color: world.accent }"
          >
            <span
              v-if="userInitials"
              class="fd"
              style="font-size: 15px; letter-spacing: -0.005em;"
            >
              {{ userInitials }}
            </span>
            <Icon v-else name="lucide:user" size="18" />
          </div>
          <div class="min-w-0">
            <p class="fd truncate" style="font-size: 16px; line-height: 1.15; color: var(--color-ink);">
              {{ auth.user?.name || 'Friend' }}
            </p>
            <p class="fm truncate uppercase" style="font-size: 10px; letter-spacing: 0.16em; color: var(--color-ink-mute);">
              {{ auth.user?.email || '—' }}
            </p>
          </div>
        </div>

        <!-- Two-column nav grid (includes Profile) -->
        <div class="grid grid-cols-2 gap-2">
          <NuxtLink
            v-for="link in mobileGridLinks"
            :key="link.to"
            :to="link.to"
            class="fm mobile-tile rounded-field border px-4 py-3 text-center uppercase"
            style="font-size: 11px; letter-spacing: 0.2em;"
            @click="mobileMenuOpen = false"
          >
            {{ link.label }}
          </NuxtLink>
        </div>

        <!-- Sign out bar -->
        <button
          type="button"
          class="fd mobile-signout block w-full rounded-field border px-4 py-3 italic"
          style="font-size: 15px;"
          @click="handleLogout"
        >
          Sign out
        </button>
      </div>
    </Transition>
  </nav>
</template>

<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

const auth = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)
const profileOpen = ref(false)

const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())

const profileDropdownRef = ref<HTMLElement | null>(null)
const mobileButtonRef = ref<HTMLElement | null>(null)
const mobileDrawerRef = ref<HTMLElement | null>(null)

const appLinks = [
  { to: '/user/today', label: 'Today' },
  { to: '/user/wardrobe', label: 'Wardrobe' },
  { to: '/user/journal', label: 'Journal' },
  { to: '/user/almanac', label: 'Almanac' },
  { to: '/user/perfume', label: 'Discover' },
]

const mobileGridLinks = [
  ...appLinks,
  { to: '/user/profile', label: 'Profile' },
]

const userInitials = computed(() => {
  const name = auth.user?.name?.trim()
  if (!name) return ''
  return name
    .split(/\s+/)
    .slice(0, 2)
    .map(part => part[0]?.toUpperCase() ?? '')
    .join('')
})

const memberSinceShort = computed(() => {
  const created = auth.user?.created_at
  if (!created) return ''
  return new Date(created).toLocaleDateString('en-GB', {
    month: 'short',
    year: 'numeric',
  })
})

const handleClickOutside = (e: MouseEvent) => {
  const target = e.target as Node | null
  if (!target) return

  if (profileOpen.value && profileDropdownRef.value && !profileDropdownRef.value.contains(target)) {
    profileOpen.value = false
  }

  if (
    mobileMenuOpen.value
    && !mobileButtonRef.value?.contains(target)
    && !mobileDrawerRef.value?.contains(target)
  ) {
    mobileMenuOpen.value = false
  }
}

const handleEscape = (e: KeyboardEvent) => {
  if (e.key !== 'Escape') return
  profileOpen.value = false
  mobileMenuOpen.value = false
}

onMounted(() => {
  auth.init()
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleEscape)
})

const handleLogout = () => {
  auth.logout()
  mobileMenuOpen.value = false
  profileOpen.value = false
  router.push('/auth/login')
}
</script>

<style scoped>
.nav-link {
  transition: color 0.15s ease;
}
.nav-link:hover {
  color: var(--color-ink);
}

.nav-icon-btn {
  color: var(--color-ink-soft);
  transition: color 0.15s ease;
}
.nav-icon-btn:hover {
  color: var(--color-ink);
}

.menu-outline-btn {
  border: 1px solid var(--color-rule);
  color: var(--color-ink-soft);
  transition: color 0.15s ease, border-color 0.15s ease;
}
.menu-outline-btn:hover {
  color: var(--color-ink);
  border-color: var(--color-ink-soft);
}

.menu-signout {
  color: var(--color-ink-mute);
  transition: color 0.15s ease;
}
.menu-signout:hover {
  color: var(--color-ink);
}

.mobile-tile {
  background: var(--color-surface);
  border-color: var(--color-rule);
  color: var(--color-ink-soft);
  transition: background 0.15s ease, color 0.15s ease;
}
.mobile-tile:hover {
  background: var(--color-surface-2);
  color: var(--color-ink);
}

.mobile-signout {
  background: var(--color-surface-2);
  border-color: var(--color-rule);
  color: var(--color-ink-soft);
  transition: color 0.15s ease, border-color 0.15s ease;
}
.mobile-signout:hover {
  color: var(--color-ink);
  border-color: var(--color-ink-soft);
}
</style>
