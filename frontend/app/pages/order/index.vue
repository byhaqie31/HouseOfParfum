<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>
    <div v-if="pending" class="text-center text-gray-500">Loading...</div>
    <div v-else-if="orders.length === 0" class="text-center text-gray-400 py-20">No orders found.</div>
    <div v-else>
      <div v-for="order in orders" :key="order.id" class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="flex justify-between items-center">
          <div>
            <p class="font-semibold text-gray-800">{{ order.perfume_name }}</p>
            <p class="text-sm text-gray-500">Status: {{ order.order_status }}</p>
            <p class="text-sm text-gray-500">Tracking: {{ order.tracking_no }}</p>
          </div>
          <p class="font-bold text-gray-900">RM {{ order.total_price }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const api = useApi()
const orders = ref([])
const pending = ref(true)

onMounted(async () => {
  try {
    orders.value = await api.get('/order')
  } finally {
    pending.value = false
  }
})
</script>
