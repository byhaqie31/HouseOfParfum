<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-200/50"
  >
    <div class="max-w-300 mx-auto px-6 h-12 flex items-center justify-between">
      <NuxtLink to="/" class="text-sm font-semibold text-gray-900 tracking-tight">
        House of Parfum
      </NuxtLink>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-6">
        <NuxtLink to="/perfume" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
          Fragrances
        </NuxtLink>

        <!-- Cart Icon -->
        <NuxtLink to="/cart" class="text-gray-600 hover:text-gray-900 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
          </svg>
        </NuxtLink>

        <!-- Profile Dropdown -->
        <div class="relative">
          <button
            @click="profileOpen = !profileOpen"
            class="text-gray-600 hover:text-gray-900 transition-colors"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
          </button>

          <!-- Dropdown Menu -->
          <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95 -translate-y-1"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-1"
          >
            <div
              v-if="profileOpen"
              class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-200/80 py-2 overflow-hidden"
            >
              <template v-if="auth.isLoggedIn">
                <NuxtLink
                  to="/profile"
                  class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="profileOpen = false"
                >
                  Profile
                </NuxtLink>
                <NuxtLink
                  to="/order"
                  class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="profileOpen = false"
                >
                  Orders
                </NuxtLink>
                <div class="border-t border-gray-100 my-1" />
                <button
                  @click="handleLogout"
                  class="block w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-gray-50 transition-colors"
                >
                  Sign Out
                </button>
              </template>
              <template v-else>
                <NuxtLink
                  to="/login"
                  class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="profileOpen = false"
                >
                  Sign In
                </NuxtLink>
                <NuxtLink
                  to="/register"
                  class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                  @click="profileOpen = false"
                >
                  Create Account
                </NuxtLink>
              </template>
            </div>
          </Transition>
        </div>
      </div>

      <!-- Mobile Nav -->
      <div class="flex md:hidden items-center gap-4">
        <!-- Cart Icon (Mobile) -->
        <NuxtLink to="/cart" class="text-gray-600 hover:text-gray-900 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
          </svg>
        </NuxtLink>

        <!-- Hamburger -->
        <button
          class="text-gray-600 hover:text-gray-900"
          @click="mobileMenuOpen = !mobileMenuOpen"
        >
          <svg v-if="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="mobileMenuOpen"
        class="md:hidden bg-white/95 backdrop-blur-xl border-b border-gray-200/50 px-6 py-4 space-y-3"
      >
        <NuxtLink to="/perfume" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
          Fragrances
        </NuxtLink>
        <div class="border-t border-gray-100 my-2" />
        <template v-if="auth.isLoggedIn">
          <NuxtLink to="/profile" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Profile
          </NuxtLink>
          <NuxtLink to="/order" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Orders
          </NuxtLink>
          <div class="border-t border-gray-100 my-2" />
          <button @click="handleLogout" class="block text-sm text-red-500 hover:text-red-700">
            Sign Out
          </button>
        </template>
        <template v-else>
          <NuxtLink to="/login" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Sign In
          </NuxtLink>
          <NuxtLink to="/register" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Create Account
          </NuxtLink>
        </template>
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

onMounted(() => auth.init())

const handleLogout = () => {
  auth.logout()
  mobileMenuOpen.value = false
  profileOpen.value = false
  router.push('/login')
}
</script>
