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
          <div class="relative flex items-center">
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
                class="absolute top-full right-0 mt-3 w-52 bg-paper border border-rule py-2"
              >
                <NuxtLink
                  to="/profile"
                  class="block px-5 py-2.5 text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink hover:bg-paper-deep transition-colors"
                  @click="profileOpen = false"
                >
                  Profile
                </NuxtLink>
                <NuxtLink
                  to="/order"
                  class="block px-5 py-2.5 text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink hover:bg-paper-deep transition-colors"
                  @click="profileOpen = false"
                >
                  Orders
                </NuxtLink>
                <div class="border-t border-rule my-1" />
                <button
                  class="block w-full text-left px-5 py-2.5 font-display italic text-base text-ink-soft hover:text-ink hover:bg-paper-deep transition-colors"
                  @click="handleLogout"
                >
                  Sign out
                </button>
              </div>
            </Transition>
          </div>
        </div>

        <!-- Mobile -->
        <div class="flex md:hidden items-center gap-4">
          <button
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
          to="/"
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
        class="md:hidden bg-paper/95 backdrop-blur-xl border-b border-rule px-6 py-5 space-y-4"
      >
        <NuxtLink
          v-for="link in appLinks"
          :key="link.to"
          :to="link.to"
          class="nav-link block text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink"
          @click="mobileMenuOpen = false"
        >
          {{ link.label }}
        </NuxtLink>
        <div class="border-t border-rule my-2" />
        <NuxtLink
          to="/profile"
          class="block text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink"
          @click="mobileMenuOpen = false"
        >
          Profile
        </NuxtLink>
        <NuxtLink
          to="/order"
          class="block text-xs uppercase tracking-[0.2em] font-medium text-ink-soft hover:text-ink"
          @click="mobileMenuOpen = false"
        >
          Orders
        </NuxtLink>
        <div class="border-t border-rule my-2" />
        <button
          class="block font-display italic text-base text-ink-soft hover:text-ink"
          @click="handleLogout"
        >
          Sign out
        </button>
      </div>
    </Transition>
  </nav>

  <!-- Click outside to close dropdown -->
  <div v-if="profileOpen" class="fixed inset-0 z-40" @click="profileOpen = false" />
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)
const profileOpen = ref(false)

const appLinks = [
  { to: '/today', label: 'Today' },
  { to: '/vanity', label: 'Vanity' },
  { to: '/journal', label: 'Journal' },
  { to: '/perfume', label: 'Discover' },
]

onMounted(() => auth.init())

const handleLogout = () => {
  auth.logout()
  mobileMenuOpen.value = false
  profileOpen.value = false
  router.push('/')
}
</script>
