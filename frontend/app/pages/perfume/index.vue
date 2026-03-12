<template>
  <div class="pt-12">
    <!-- Hero Header -->
    <section class="py-16 px-6 text-center">
      <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 mb-4">
        Fragrances
      </h1>
      <p class="text-lg text-gray-500 font-light max-w-xl mx-auto">
        Explore our curated collection of premium scents
      </p>
    </section>

    <!-- Filters -->
    <section class="max-w-300 mx-auto px-6 mb-12">
      <div class="flex flex-wrap items-center justify-center gap-3">
        <button
          v-for="filter in filters"
          :key="filter.value"
          @click="activeFilter = filter.value"
          class="px-5 py-2 rounded-full text-sm transition-all duration-200"
          :class="
            activeFilter === filter.value
              ? 'bg-gray-900 text-white'
              : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
          "
        >
          {{ filter.label }}
        </button>
      </div>
    </section>

    <!-- Loading -->
    <section v-if="pending" class="py-24 text-center">
      <div class="inline-block w-6 h-6 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin" />
    </section>

    <!-- Product Grid -->
    <section v-else class="max-w-300 mx-auto px-6 pb-24">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-px bg-gray-200">
        <NuxtLink
          v-for="perfume in filteredPerfumes"
          :key="perfume.id"
          :to="`/perfume/${perfume.id}`"
          class="group bg-white p-8 flex flex-col items-center text-center hover:z-10 hover:shadow-xl transition-all duration-300"
        >
          <div class="w-full aspect-square mb-6 overflow-hidden rounded-2xl bg-gray-50">
            <img
              :src="`/${perfume.image}`"
              :alt="perfume.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
          </div>
          <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">{{ perfume.brand }}</p>
          <h3 class="text-base font-medium text-gray-900 mb-1">{{ perfume.name }}</h3>
          <p class="text-sm text-gray-400 mb-3">{{ perfume.size }} ml</p>
          <p class="text-lg font-semibold text-gray-900">RM {{ perfume.price }}</p>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-if="filteredPerfumes.length === 0" class="py-24 text-center">
        <p class="text-gray-400 text-lg">No fragrances found</p>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const api = useApi()
const perfumes = ref<any[]>([])
const pending = ref(true)
const activeFilter = ref('all')

const filters = [
  { label: 'All', value: 'all' },
  { label: 'Women', value: 'Women' },
  { label: 'Men', value: 'Men' },
  { label: 'Unisex', value: 'Unisex' },
]

const filteredPerfumes = computed(() => {
  if (activeFilter.value === 'all') return perfumes.value
  return perfumes.value.filter((p) => p.suit === activeFilter.value)
})

onMounted(async () => {
  try {
    perfumes.value = await api.get('/perfume')
  } finally {
    pending.value = false
  }
})
</script>
