<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Chat</h1>
    <div class="bg-white rounded-lg shadow p-6 h-96 overflow-y-auto mb-4">
      <div v-for="msg in messages" :key="msg.id" class="mb-3">
        <p class="text-sm text-gray-500">{{ msg.sender }}</p>
        <p class="bg-gray-100 rounded px-3 py-2 inline-block">{{ msg.message }}</p>
      </div>
    </div>
    <form @submit.prevent="sendMessage" class="flex gap-2">
      <input v-model="newMessage" type="text" placeholder="Type a message..." class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
      <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition">Send</button>
    </form>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const api = useApi()
const messages = ref([])
const newMessage = ref('')

onMounted(async () => {
  messages.value = await api.get('/chat')
})

const sendMessage = async () => {
  if (!newMessage.value.trim()) return
  const msg = await api.post('/chat', { message: newMessage.value })
  messages.value.push(msg)
  newMessage.value = ''
}
</script>
