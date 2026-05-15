<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 bg-paper/85 backdrop-blur-xl border-b border-rule
           after:hidden md:after:block after:absolute after:left-0 after:-bottom-px
           after:w-15 after:h-px after:bg-accent after:content-['']"
  >
    <div class="max-w-300 mx-auto px-6 h-14 flex items-center justify-between">
      <NuxtLink
        to="/"
        class="inline-flex items-center gap-2.5 font-display text-xl font-normal text-ink tracking-[-0.005em]"
      >
        <img
          src="/favicon/favicon.svg"
          alt=""
          aria-hidden="true"
          class="h-9 w-9 shrink-0 brightness-0"
        >
        House of Parfum
      </NuxtLink>

      <!-- Authenticated nav -->
      <template v-if="auth.isLoggedIn">
        <!-- Desktop -->
        <div class="hidden md:flex items-center gap-10">
          <NuxtLink
            v-for="link in appLinks"
            :key="link.to"
            :to="link.to"
            class="nav-link text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink transition-colors pb-1"
          >
            {{ link.label }}
          </NuxtLink>

          <!-- Profile Dropdown -->
          <div ref="profileDropdownRef" class="relative flex items-center">
            <button
              class="text-ink-soft hover:text-ink transition-colors"
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
                class="absolute top-full right-0 mt-3 w-72 bg-paper border border-rule overflow-hidden"
              >
                <!-- Summary header -->
                <div class="px-5 pt-6 pb-6 bg-paper-deep text-center">
                  <div class="mx-auto h-14 w-14 rounded-full bg-accent-soft border border-accent flex items-center justify-center">
                    <span
                      v-if="userInitials"
                      class="font-display text-[18px] text-accent-deep tracking-tight"
                    >
                      {{ userInitials }}
                    </span>
                    <Icon v-else name="lucide:user" size="22" class="text-accent-deep" />
                  </div>
                  <p class="mt-3 font-display text-[18px] text-ink leading-tight">
                    {{ auth.user?.name || 'Friend' }}
                  </p>
                  <p class="mt-1 font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute truncate">
                    {{ auth.user?.email || '—' }}
                  </p>
                  <p
                    v-if="memberSinceShort"
                    class="mt-2 font-mono text-[9px] uppercase tracking-[0.18em] text-ink-mute"
                  >
                    Member since {{ memberSinceShort }}
                  </p>

                  <!-- Actions -->
                  <div class="mt-5 space-y-2">
                    <NuxtLink
                      v-if="auth.user?.role === 'admin'"
                      to="/admin"
                      class="block w-full py-2 border border-rule font-mono text-[10px] uppercase tracking-[0.18em] text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                      @click="profileOpen = false"
                    >
                      Back to Admin
                    </NuxtLink>
                    <NuxtLink
                      v-else
                      to="/user/profile"
                      class="block w-full py-2 border border-rule font-mono text-[10px] uppercase tracking-[0.18em] text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
                      @click="profileOpen = false"
                    >
                      View profile
                    </NuxtLink>
                    <button
                      class="block w-full py-2 font-display italic text-[15px] text-ink-mute hover:text-ink transition-colors"
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
        <div class="flex md:hidden items-center gap-4">
          <button
            ref="mobileButtonRef"
            class="text-ink-soft hover:text-ink transition-colors"
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
          class="text-xs uppercase tracking-[0.2em] font-medium text-ink hover:text-ink-soft transition-colors"
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
        class="md:hidden bg-paper/95 backdrop-blur-xl border-b border-rule px-6 py-5 space-y-5"
      >
        <!-- Profile chip -->
        <div class="flex items-center gap-3">
          <div class="h-11 w-11 rounded-full bg-accent-soft border border-accent flex items-center justify-center shrink-0">
            <span
              v-if="userInitials"
              class="font-display text-[15px] text-accent-deep tracking-tight"
            >
              {{ userInitials }}
            </span>
            <Icon v-else name="lucide:user" size="18" class="text-accent-deep" />
          </div>
          <div class="min-w-0">
            <p class="font-display text-[16px] text-ink leading-tight truncate">
              {{ auth.user?.name || 'Friend' }}
            </p>
            <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-mute truncate">
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
            class="mobile-tile border border-rule bg-paper px-4 py-3 text-center text-[11px] uppercase tracking-[0.2em] font-medium text-ink-soft hover:bg-paper-deep hover:text-ink transition-colors"
            @click="mobileMenuOpen = false"
          >
            {{ link.label }}
          </NuxtLink>
        </div>

        <!-- Sign out bar -->
        <button
          type="button"
          class="block w-full bg-paper-deep border border-rule px-4 py-3 font-display italic text-[15px] text-ink-soft hover:text-ink hover:border-ink-soft transition-colors"
          @click="handleLogout"
        >
          Sign out
        </button>
      </div>
    </Transition>
  </nav>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)
const profileOpen = ref(false)

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
