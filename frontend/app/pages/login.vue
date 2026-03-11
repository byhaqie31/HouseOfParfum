<template>
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Login</h2>
    <form @submit.prevent="handleLogin">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input v-model="form.email" type="email" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input v-model="form.password" type="password" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <p v-if="error" class="text-red-500 text-sm mb-4">{{ error }}</p>
      <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Login</button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-4">
      Don't have an account? <NuxtLink to="/register" class="text-black font-medium">Register</NuxtLink>
    </p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: 'guest' })

const api = useApi()
const auth = useAuthStore()
const form = reactive({ email: '', password: '' })
const error = ref('')

const router = useRouter()

const handleLogin = async () => {
  try {
    const data = await api.post('/login', form)
    auth.setToken(data.token)
    auth.setUser(data.user)
    await router.push('/')
  } catch (e: any) {
    error.value = 'Invalid credentials'
  }
}
</script>
