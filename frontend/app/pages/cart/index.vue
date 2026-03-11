<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Cart</h1>
    <div v-if="pending" class="text-center text-gray-500">Loading...</div>
    <div v-else-if="cart.length === 0" class="text-center text-gray-400 py-20">Your cart is empty.</div>
    <div v-else>
      <div v-for="item in cart" :key="item.id" class="bg-white rounded-lg shadow p-4 mb-4 flex items-center justify-between">
        <div>
          <p class="font-semibold text-gray-800">{{ item.perfume_name }}</p>
          <p class="text-sm text-gray-500">Qty: {{ item.quantity }}</p>
        </div>
        <p class="font-bold text-gray-900">RM {{ item.total_price }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const api = useApi()
const cart = ref([])
const pending = ref(true)

onMounted(async () => {
  try {
    cart.value = await api.get('/cart')
  } finally {
    pending.value = false
  }
})
</script>
