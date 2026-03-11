<template>
  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Register</h2>
    <form @submit.prevent="handleRegister">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
        <input v-model="form.name" type="text" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input v-model="form.email" type="email" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input v-model="form.password" type="password" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input v-model="form.password_confirmation" type="password" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      </div>
      <p v-if="error" class="text-red-500 text-sm mb-4">{{ error }}</p>
      <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">Register</button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-4">
      Already have an account? <NuxtLink to="/login" class="text-black font-medium">Login</NuxtLink>
    </p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'auth', middleware: 'guest' })

const api = useApi()
const auth = useAuthStore()
const form = reactive({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref('')

const router = useRouter()

const handleRegister = async () => {
  try {
    const data = await api.post('/register', form)
    auth.setToken(data.token)
    auth.setUser(data.user)
    await router.push('/')
  } catch (e: any) {
    error.value = 'Registration failed. Please check your details.'
  }
}
</script>
