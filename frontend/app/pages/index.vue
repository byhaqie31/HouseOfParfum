<template>
  <div>
    <!-- Hero Carousel -->
    <section class="relative pt-12 bg-black text-white overflow-hidden">
      <div class="relative h-[90vh] flex items-center justify-center">
        <!-- Slides -->
        <TransitionGroup
          enter-active-class="transition-all duration-700 ease-out"
          enter-from-class="opacity-0 scale-105"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition-all duration-700 ease-out absolute inset-0"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div
            v-for="(slide, index) in slides"
            v-show="currentSlide === index"
            :key="index"
            class="absolute inset-0 flex flex-col items-center justify-center px-6"
          >
            <!-- Background Image -->
            <div class="absolute inset-0">
              <img
                :src="`/${slide.image}`"
                :alt="slide.name"
                class="w-full h-full object-cover opacity-40"
              />
              <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/40 to-black/60" />
            </div>

            <!-- Content -->
            <div class="relative z-10 text-center max-w-3xl">
              <p class="text-xs uppercase tracking-[0.3em] text-gray-400 mb-4">
                {{ slide.brand }}
              </p>
              <h1 class="text-5xl sm:text-7xl font-semibold tracking-tight mb-4 leading-tight">
                {{ slide.name }}
              </h1>
              <p class="text-lg sm:text-xl text-gray-300 font-light mb-2">
                {{ slide.tagline }}
              </p>
              <p class="text-2xl font-light text-white/90 mb-8">
                RM {{ slide.price }}
              </p>
              <div class="flex items-center justify-center gap-4">
                <NuxtLink
                  :to="`/perfume/${slide.id}`"
                  class="text-sm text-blue-400 hover:text-blue-300 hover:underline transition-colors"
                >
                  Learn more &gt;
                </NuxtLink>
                <NuxtLink
                  to="/perfume"
                  class="text-sm text-blue-400 hover:text-blue-300 hover:underline transition-colors"
                >
                  Shop now &gt;
                </NuxtLink>
              </div>
            </div>
          </div>
        </TransitionGroup>

        <!-- Carousel Controls -->
        <button
          @click="prevSlide"
          class="absolute left-4 sm:left-8 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button
          @click="nextSlide"
          class="absolute right-4 sm:right-8 z-20 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Dots -->
        <div class="absolute bottom-8 z-20 flex items-center gap-2">
          <button
            v-for="(_, index) in slides"
            :key="index"
            @click="goToSlide(index)"
            class="w-2 h-2 rounded-full transition-all duration-300"
            :class="currentSlide === index ? 'bg-white w-6' : 'bg-white/40 hover:bg-white/60'"
          />
        </div>
      </div>
    </section>

    <!-- Featured Collection Grid -->
    <section class="py-20 px-6 bg-gray-50">
      <div class="max-w-300 mx-auto">
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 text-center mb-4">
          Latest Collection
        </h2>
        <p class="text-gray-500 text-center mb-12 text-lg font-light">
          Explore our newest arrivals
        </p>

        <div v-if="loadingPerfumes" class="text-center text-gray-400 py-16">Loading...</div>
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-1">
          <NuxtLink
            v-for="perfume in featuredPerfumes"
            :key="perfume.id"
            :to="`/perfume/${perfume.id}`"
            class="group bg-white p-8 flex flex-col items-center text-center hover:shadow-lg transition-all duration-300"
          >
            <div class="w-full aspect-square mb-6 overflow-hidden rounded-lg bg-gray-100">
              <img
                :src="`/${perfume.image}`"
                :alt="perfume.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">{{ perfume.brand }}</p>
            <h3 class="text-base font-medium text-gray-900 mb-2">{{ perfume.name }}</h3>
            <p class="text-sm text-gray-500 mb-3">{{ perfume.size }} ml</p>
            <p class="text-lg font-semibold text-gray-900">RM {{ perfume.price }}</p>
          </NuxtLink>
        </div>

        <div class="text-center mt-12">
          <NuxtLink
            to="/perfume"
            class="inline-block text-sm text-blue-600 hover:text-blue-700 hover:underline transition-colors"
          >
            View all fragrances &gt;
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Brands Section -->
    <section class="py-20 px-6 bg-white">
      <div class="max-w-300 mx-auto">
        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-900 text-center mb-4">
          Our Brands
        </h2>
        <p class="text-gray-500 text-center mb-16 text-lg font-light">
          Curated from the world's finest houses
        </p>

        <div v-if="loadingBrands" class="text-center text-gray-400 py-16">Loading...</div>
        <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-8">
          <div
            v-for="brand in brands"
            :key="brand.id"
            class="group flex flex-col items-center justify-center py-8 px-4 rounded-2xl hover:bg-gray-50 transition-colors duration-300 cursor-pointer"
          >
            <div
              class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4 group-hover:bg-gray-200 transition-colors duration-300 overflow-hidden"
            >
              <img
                v-if="brand.image"
                :src="`/${brand.image}`"
                :alt="brand.name"
                class="w-full h-full object-cover"
              />
              <span v-else class="text-xl font-bold text-gray-400">{{ brand.code }}</span>
            </div>
            <h3 class="text-sm font-medium text-gray-900 text-center">{{ brand.name }}</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Banner -->
    <section class="py-24 px-6 bg-gray-950 text-white text-center">
      <div class="max-w-2xl mx-auto">
        <h2 class="text-4xl sm:text-5xl font-semibold tracking-tight mb-4">
          Find Your Signature
        </h2>
        <p class="text-lg text-gray-400 font-light mb-8">
          Every scent tells a story. Discover yours.
        </p>
        <NuxtLink
          to="/perfume"
          class="inline-block bg-white text-gray-900 text-sm font-medium px-8 py-3 rounded-full hover:bg-gray-100 transition-colors"
        >
          Explore Fragrances
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const api = useApi()

// Perfumes
const perfumes = ref<any[]>([])
const loadingPerfumes = ref(true)

// Brands
const brands = ref<any[]>([])
const loadingBrands = ref(true)

// Carousel
const currentSlide = ref(0)
let autoplayTimer: ReturnType<typeof setInterval> | null = null

const slides = computed(() =>
  perfumes.value.slice(0, 4).map((p) => ({
    id: p.id,
    name: p.name,
    brand: p.brand,
    image: p.image,
    price: p.price,
    tagline: `${p.size} ml \u00B7 ${p.quality}`,
  }))
)

const featuredPerfumes = computed(() => perfumes.value.slice(0, 8))

function nextSlide() {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

function prevSlide() {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
}

function goToSlide(index: number) {
  currentSlide.value = index
}

function startAutoplay() {
  stopAutoplay()
  autoplayTimer = setInterval(nextSlide, 5000)
}

function stopAutoplay() {
  if (autoplayTimer) {
    clearInterval(autoplayTimer)
    autoplayTimer = null
  }
}

onMounted(async () => {
  try {
    const [perfumeData, brandData] = await Promise.all([
      api.get('/perfume'),
      api.get('/brand'),
    ])
    perfumes.value = perfumeData
    brands.value = brandData
  } finally {
    loadingPerfumes.value = false
    loadingBrands.value = false
  }

  startAutoplay()
})

onUnmounted(() => {
  stopAutoplay()
})
</script>
