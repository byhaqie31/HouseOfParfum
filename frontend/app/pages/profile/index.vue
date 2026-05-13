<template>
  <div class="max-w-lg mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>
    <div v-if="user" class="bg-white rounded-lg shadow p-6">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <p class="text-gray-900 mt-1">{{ user.name }}</p>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <p class="text-gray-900 mt-1">{{ user.email }}</p>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <p class="text-gray-900 mt-1">{{ user.role === '1' ? 'Admin' : 'Customer' }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const api = useApi()
const user = ref(null)

onMounted(async () => {
  user.value = await api.get('/profile')
})
</script>
