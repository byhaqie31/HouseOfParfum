<template>
  <div v-if="perfume" class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <img :src="`/images/perfumes/${perfume.image}`" :alt="perfume.name" class="rounded-lg w-full object-cover" />
      <div>
        <p class="text-sm text-gray-400 uppercase mb-1">{{ perfume.brand }}</p>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ perfume.name }}</h1>
        <p class="text-gray-500 mb-4">{{ perfume.size }} ml · {{ perfume.suit }}</p>
        <p class="text-2xl font-bold text-gray-900 mb-6">RM {{ perfume.price }}</p>
        <p class="text-sm text-gray-600 mb-6">{{ perfume.history }}</p>
        <button @click="addToCart" class="w-full bg-black text-white py-3 rounded hover:bg-gray-800 transition">
          Add to Cart
        </button>
      </div>
    </div>
  </div>
  <div v-else class="text-center text-gray-500">Loading...</div>
</template>

<script setup lang="ts">
const route = useRoute()
const api = useApi()
const perfume = ref(null)

onMounted(async () => {
  perfume.value = await api.get(`/perfume/${route.params.id}`)
})

const addToCart = async () => {
  await api.post('/cart', { perfume_id: perfume.value.id, quantity: 1 })
  navigateTo('/cart')
}
</script>
