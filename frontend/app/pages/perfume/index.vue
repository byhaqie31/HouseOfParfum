<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Fragrances</h1>
    <div v-if="pending" class="text-center text-gray-500">Loading...</div>
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <NuxtLink
        v-for="perfume in perfumes"
        :key="perfume.id"
        :to="`/perfume/${perfume.id}`"
        class="bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden"
      >
        <img :src="`/images/perfumes/${perfume.image}`" :alt="perfume.name" class="w-full h-48 object-cover" />
        <div class="p-4">
          <p class="text-xs text-gray-400 uppercase">{{ perfume.brand }}</p>
          <h3 class="font-semibold text-gray-800 mt-1">{{ perfume.name }}</h3>
          <p class="text-sm text-gray-500 mt-1">{{ perfume.size }} ml</p>
          <p class="text-lg font-bold text-gray-900 mt-2">RM {{ perfume.price }}</p>
        </div>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi()
const perfumes = ref([])
const pending = ref(true)

onMounted(async () => {
  try {
    perfumes.value = await api.get('/perfume')
  } finally {
    pending.value = false
  }
})
</script>
