<template>
  <div v-if="perfume" class="pt-12">
    <!-- Hero Section -->
    <section class="max-w-300 mx-auto px-6 py-16">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Image -->
        <div class="aspect-square rounded-3xl overflow-hidden bg-gray-50">
          <img
            :src="`/${perfume.image}`"
            :alt="perfume.name"
            class="w-full h-full object-cover"
          />
        </div>

        <!-- Details -->
        <div class="lg:sticky lg:top-24">
          <p class="text-xs uppercase tracking-[0.2em] text-gray-400 mb-2">{{ brandName }}</p>
          <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 mb-2">
            {{ perfume.name }}
          </h1>
          <p class="text-sm text-gray-400 mb-6">{{ perfume.size }} ml · {{ perfume.suit }} · {{ perfume.year }}</p>

          <!-- Price -->
          <div class="mb-8">
            <p class="text-3xl font-semibold text-gray-900">RM {{ perfume.price }}</p>
            <p v-if="perfume.rrp_rm" class="text-sm text-gray-400 mt-1">
              RRP <span class="line-through">RM {{ perfume.rrp_rm }}</span>
            </p>
          </div>

          <!-- Quality Badge -->
          <div class="flex items-center gap-3 mb-8">
            <span
              class="px-3 py-1 rounded-full text-xs font-medium"
              :class="perfume.quality === 'Original'
                ? 'bg-gray-900 text-white'
                : 'bg-gray-100 text-gray-600'"
            >
              {{ perfume.quality }}
            </span>
            <span v-if="perfume.suit_season" class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
              {{ perfume.suit_season }}
            </span>
            <span v-if="perfume.suit_time" class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
              {{ perfume.suit_time }}
            </span>
          </div>

          <!-- Description -->
          <p v-if="perfume.history" class="text-sm text-gray-500 leading-relaxed mb-8">
            {{ perfume.history }}
          </p>

          <!-- Add to Cart -->
          <button
            @click="addToCart"
            class="w-full bg-gray-900 text-white text-sm font-medium py-4 rounded-full hover:bg-black transition-colors"
          >
            Add to Cart
          </button>

          <NuxtLink
            to="/perfume"
            class="block text-center text-sm text-blue-600 hover:text-blue-700 hover:underline mt-4 transition-colors"
          >
            Continue shopping
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Fragrance Notes -->
    <section v-if="perfume.top_notes || perfume.middle_notes || perfume.base_notes" class="bg-gray-50 py-20 px-6">
      <div class="max-w-300 mx-auto">
        <h2 class="text-3xl font-semibold text-gray-900 text-center mb-4">Fragrance Notes</h2>
        <p v-if="perfume.main_accord" class="text-gray-500 text-center mb-14 text-sm">
          Main Accord: {{ perfume.main_accord }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-gray-200 rounded-2xl overflow-hidden">
          <!-- Top Notes -->
          <div v-if="perfume.top_notes" class="bg-white p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
              </svg>
            </div>
            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-3">Top Notes</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ perfume.top_notes }}</p>
          </div>

          <!-- Middle Notes -->
          <div v-if="perfume.middle_notes" class="bg-white p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 8.25c0 2.485-2.099 4.5-4.688 4.5-1.935 0-3.597-1.126-4.312-2.733-.715 1.607-2.377 2.733-4.313 2.733C5.1 12.75 3 10.735 3 8.25 3 5.765 5.1 3.75 7.688 3.75c1.935 0 3.597 1.126 4.312 2.733.715-1.607 2.377-2.733 4.313-2.733C18.9 3.75 21 5.765 21 8.25Z" />
              </svg>
            </div>
            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-3">Heart Notes</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ perfume.middle_notes }}</p>
          </div>

          <!-- Base Notes -->
          <div v-if="perfume.base_notes" class="bg-white p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
              </svg>
            </div>
            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-3">Base Notes</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ perfume.base_notes }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Season & Time Suitability -->
    <section class="bg-white py-20 px-6">
      <div class="max-w-300 mx-auto">
        <h2 class="text-3xl font-semibold text-gray-900 text-center mb-14">When to Wear</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 max-w-3xl mx-auto">
          <!-- Season -->
          <div>
            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-6 text-center">Season</h3>
            <div class="space-y-4">
              <div v-for="season in seasons" :key="season.label">
                <div class="flex items-center justify-between mb-1.5">
                  <span class="text-sm text-gray-600">{{ season.label }}</span>
                  <span class="text-xs text-gray-400">{{ season.value }}%</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                  <div
                    class="h-full bg-gray-900 rounded-full transition-all duration-700"
                    :style="{ width: `${season.value}%` }"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Time -->
          <div>
            <h3 class="text-xs uppercase tracking-widest text-gray-400 mb-6 text-center">Time of Day</h3>
            <div class="space-y-4">
              <div v-for="time in times" :key="time.label">
                <div class="flex items-center justify-between mb-1.5">
                  <span class="text-sm text-gray-600">{{ time.label }}</span>
                  <span class="text-xs text-gray-400">{{ time.value }}%</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                  <div
                    class="h-full bg-gray-900 rounded-full transition-all duration-700"
                    :style="{ width: `${time.value}%` }"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Loading -->
  <div v-else class="pt-12 min-h-screen flex items-center justify-center">
    <div class="inline-block w-6 h-6 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin" />
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const api = useApi()
const perfume = ref<any>(null)
const brands = ref<any[]>([])

const brandName = computed(() => {
  if (!perfume.value) return ''
  const found = brands.value.find((b) => b.code === perfume.value.brand)
  return found ? found.name : perfume.value.brand
})

const seasons = computed(() => {
  if (!perfume.value) return []
  return [
    { label: 'Spring', value: perfume.value.percent_spring ?? 0 },
    { label: 'Summer', value: perfume.value.percent_summer ?? 0 },
    { label: 'Autumn', value: perfume.value.percent_autumn ?? 0 },
    { label: 'Winter', value: perfume.value.percent_winter ?? 0 },
  ]
})

const times = computed(() => {
  if (!perfume.value) return []
  return [
    { label: 'Day', value: perfume.value.percent_day ?? 0 },
    { label: 'Night', value: perfume.value.percent_night ?? 0 },
  ]
})

onMounted(async () => {
  const [perfumeData, brandData] = await Promise.all([
    api.get(`/perfume/${route.params.id}`),
    api.get('/brand'),
  ])
  perfume.value = perfumeData
  brands.value = brandData
})

const addToCart = async () => {
  await api.post('/cart', { perfume_id: perfume.value.id, quantity: 1 })
  navigateTo('/cart')
}
</script>
