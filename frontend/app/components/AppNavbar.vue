<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-200/50"
  >
    <div class="max-w-300 mx-auto px-6 h-12 flex items-center justify-between">
      <NuxtLink to="/" class="text-sm font-semibold text-gray-900 tracking-tight">
        House of Parfum
      </NuxtLink>

      <!-- Desktop Nav -->
      <div class="hidden md:flex items-center gap-8">
        <NuxtLink to="/perfume" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
          Fragrances
        </NuxtLink>
        <NuxtLink to="/cart" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
          Cart
        </NuxtLink>
        <template v-if="auth.isLoggedIn">
          <NuxtLink to="/order" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
            Orders
          </NuxtLink>
          <NuxtLink to="/profile" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
            Profile
          </NuxtLink>
          <button
            @click="handleLogout"
            class="text-xs text-gray-400 hover:text-gray-900 transition-colors"
          >
            Logout
          </button>
        </template>
        <template v-else>
          <NuxtLink to="/login" class="text-xs text-gray-600 hover:text-gray-900 transition-colors">
            Sign In
          </NuxtLink>
          <NuxtLink
            to="/register"
            class="text-xs bg-gray-900 text-white px-4 py-1.5 rounded-full hover:bg-black transition-colors"
          >
            Sign Up
          </NuxtLink>
        </template>
      </div>

      <!-- Mobile Menu Button -->
      <button
        class="md:hidden text-gray-600 hover:text-gray-900"
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
        <NuxtLink to="/cart" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
          Cart
        </NuxtLink>
        <template v-if="auth.isLoggedIn">
          <NuxtLink to="/order" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Orders
          </NuxtLink>
          <NuxtLink to="/profile" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Profile
          </NuxtLink>
          <button @click="handleLogout" class="block text-sm text-gray-400 hover:text-gray-900">
            Logout
          </button>
        </template>
        <template v-else>
          <NuxtLink to="/login" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Sign In
          </NuxtLink>
          <NuxtLink to="/register" class="block text-sm text-gray-600 hover:text-gray-900" @click="mobileMenuOpen = false">
            Sign Up
          </NuxtLink>
        </template>
      </div>
    </Transition>
  </nav>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)

onMounted(() => auth.init())

const handleLogout = () => {
  auth.logout()
  mobileMenuOpen.value = false
  router.push('/login')
}
</script>
