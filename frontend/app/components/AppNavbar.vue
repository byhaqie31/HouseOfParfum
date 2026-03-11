<template>
  <nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <NuxtLink to="/" class="text-xl font-bold text-gray-800">House of Parfum</NuxtLink>
      <div class="flex items-center gap-6">
        <NuxtLink to="/perfume" class="text-gray-600 hover:text-gray-900">Fragrances</NuxtLink>
        <NuxtLink to="/cart" class="text-gray-600 hover:text-gray-900">Cart</NuxtLink>
        <template v-if="auth.isLoggedIn">
          <NuxtLink to="/profile" class="text-gray-600 hover:text-gray-900">Profile</NuxtLink>
          <button @click="handleLogout" class="text-red-500 hover:text-red-700">Logout</button>
        </template>
        <template v-else>
          <NuxtLink to="/login" class="text-gray-600 hover:text-gray-900">Login</NuxtLink>
          <NuxtLink to="/register" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Register</NuxtLink>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()

onMounted(() => auth.init())

const handleLogout = () => {
  auth.logout()
  router.push('/login')
}
</script>
