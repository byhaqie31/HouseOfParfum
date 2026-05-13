<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <!-- Step indicator + back -->
    <header class="max-w-5xl mx-auto flex items-center justify-between mb-12 sm:mb-20">
      <button
        :disabled="step === 1"
        class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
        @click="goBack"
      >
        <Icon name="lucide:arrow-left" class="h-3.5 w-3.5" />
        {{ step === 1 ? 'Cancel' : 'Back' }}
      </button>

      <div class="flex items-center gap-3">
        <span class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          {{ String(displayStep).padStart(2, '0') }} / 03
        </span>
        <div class="w-20 h-px bg-rule relative overflow-hidden">
          <div
            class="absolute inset-y-0 left-0 bg-ink transition-all duration-500 ease-out"
            :style="{ width: `${(displayStep / 3) * 100}%` }"
          />
        </div>
      </div>
    </header>

    <!-- Step body with crossfade -->
    <Transition
      enter-active-class="transition duration-500 ease-out"
      enter-from-class="opacity-0 translate-y-3"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
      mode="out-in"
    >
      <!-- ── Step 1: which house? ───────────────────────────── -->
      <section v-if="step === 1" key="step-1" class="max-w-6xl mx-auto">
        <div class="max-w-2xl">
          <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
            Where does it come from?
          </p>
          <h1 class="mt-4 font-serif text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
            Begin with <em class="text-ink-soft">the house.</em>
          </h1>
          <p class="mt-6 text-base text-ink-soft leading-relaxed">
            Each fragrance lives under a name. Choose the one you're adding from.
          </p>
        </div>

        <div v-if="loadingBrands" class="mt-24 text-center">
          <p class="font-serif italic text-ink-soft">Drawing from the cabinet…</p>
        </div>

        <div v-else class="mt-16 sm:mt-20 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-px bg-rule">
          <button
            v-for="brand in brands"
            :key="brand.id"
            class="group bg-paper aspect-[4/5] p-8 flex flex-col items-center justify-center text-center hover:bg-paper-deep transition-colors duration-300 cursor-pointer"
            @click="selectBrand(brand)"
          >
            <div class="w-16 h-16 rounded-full overflow-hidden bg-paper-deep border border-rule flex items-center justify-center mb-6 group-hover:scale-105 transition-transform duration-500">
              <img
                v-if="brand.image"
                :src="asset(brand.image)"
                :alt="brand.name"
                class="w-full h-full object-cover"
              >
              <span v-else class="font-serif text-xl text-ink-soft">{{ brand.code }}</span>
            </div>
            <h3 class="font-serif text-xl text-ink tracking-tight">{{ brand.name }}</h3>
            <p class="mt-2 font-mono text-[10px] uppercase tracking-widest text-ink-mute">
              {{ perfumeCountByBrand(brand.code) }} {{ perfumeCountByBrand(brand.code) === 1 ? 'fragrance' : 'fragrances' }}
            </p>
          </button>
        </div>

        <p class="mt-16 text-center text-xs uppercase tracking-widest text-ink-mute">
          Don't see your house?
          <span class="ml-2 text-ink-soft italic normal-case tracking-normal">— manual entry, soon.</span>
        </p>
      </section>

      <!-- ── Step 2: which fragrance? ──────────────────────── -->
      <section v-else-if="step === 2" key="step-2" class="max-w-6xl mx-auto">
        <div class="max-w-2xl">
          <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
            From {{ selectedBrand?.name }}
          </p>
          <h1 class="mt-4 font-serif text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
            Which <em class="text-ink-soft">fragrance?</em>
          </h1>
        </div>

        <div v-if="filteredPerfumes.length === 0" class="mt-24 text-center">
          <p class="font-serif italic text-ink-soft">
            We don't have any {{ selectedBrand?.name }} fragrances on file yet.
          </p>
          <button
            class="mt-8 inline-flex items-center gap-2 text-xs uppercase tracking-widest text-ink hover:text-accent transition-colors"
            @click="step = 1"
          >
            <Icon name="lucide:arrow-left" class="h-3.5 w-3.5" />
            Pick another house
          </button>
        </div>

        <div v-else class="mt-16 sm:mt-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-px bg-rule">
          <button
            v-for="perfume in filteredPerfumes"
            :key="perfume.id"
            class="group bg-paper p-8 flex flex-col text-left hover:bg-paper-deep transition-colors duration-300 cursor-pointer"
            @click="selectPerfume(perfume)"
          >
            <div class="aspect-square w-full overflow-hidden bg-paper-deep border border-rule flex items-center justify-center">
              <img
                v-if="perfume.image"
                :src="asset(perfume.image)"
                :alt="perfume.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              >
              <Icon v-else name="lucide:flower-2" class="h-8 w-8 text-ink-mute" />
            </div>
            <h3 class="mt-6 font-serif text-2xl text-ink tracking-tight leading-tight">
              {{ perfume.name }}
            </h3>
            <p v-if="perfume.main_accord" class="mt-3 font-mono text-[10px] uppercase tracking-widest text-ink-mute">
              {{ formatAccord(perfume.main_accord) }}
            </p>
            <p v-if="perfume.year" class="mt-2 font-mono text-[10px] uppercase tracking-widest text-ink-mute">
              {{ perfume.year }} &middot; {{ perfume.size }} ml
            </p>
          </button>
        </div>
      </section>

      <!-- ── Step 3: your bottle ───────────────────────────── -->
      <section v-else-if="step === 3" key="step-3" class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-16">
          <!-- Bottle preview -->
          <aside class="md:col-span-5">
            <div class="aspect-square w-full overflow-hidden bg-paper-deep border border-rule flex items-center justify-center">
              <img
                v-if="selectedPerfume?.image"
                :src="asset(selectedPerfume.image)"
                :alt="selectedPerfume.name"
                class="w-full h-full object-cover"
              >
              <Icon v-else name="lucide:flower-2" class="h-10 w-10 text-ink-mute" />
            </div>
            <p class="mt-6 font-mono text-[10px] uppercase tracking-widest text-ink-mute">
              {{ selectedBrand?.name }}
            </p>
            <h2 class="mt-2 font-serif text-3xl text-ink tracking-tight leading-tight">
              {{ selectedPerfume?.name }}
            </h2>
            <p v-if="selectedPerfume?.main_accord" class="mt-3 font-mono text-[10px] uppercase tracking-widest text-ink-mute">
              {{ formatAccord(selectedPerfume.main_accord) }}
            </p>
          </aside>

          <!-- Form -->
          <div class="md:col-span-7">
            <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
              Your bottle
            </p>
            <h1 class="mt-4 font-serif text-4xl sm:text-5xl text-ink tracking-tight leading-[1.05]">
              Tell us <em class="text-ink-soft">about it.</em>
            </h1>

            <form class="mt-12 space-y-10" @submit.prevent="handleSubmit">
              <!-- Size -->
              <fieldset>
                <legend class="text-[11px] uppercase tracking-widest text-ink-soft">
                  Size
                </legend>
                <div class="mt-4 flex flex-wrap gap-2">
                  <button
                    v-for="opt in sizeOptions"
                    :key="opt.value ?? 'decant'"
                    type="button"
                    class="px-5 py-2.5 text-xs uppercase tracking-widest border transition-colors"
                    :class="details.size === opt.value
                      ? 'bg-ink text-paper border-ink'
                      : 'bg-transparent text-ink-soft border-rule hover:border-ink hover:text-ink'"
                    @click="details.size = opt.value"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </fieldset>

              <!-- Status -->
              <fieldset>
                <legend class="text-[11px] uppercase tracking-widest text-ink-soft">
                  How full?
                </legend>
                <div class="mt-4 flex flex-wrap gap-2">
                  <button
                    v-for="opt in statusOptions"
                    :key="opt.value"
                    type="button"
                    class="px-5 py-2.5 text-xs uppercase tracking-widest border transition-colors"
                    :class="details.status === opt.value
                      ? 'bg-ink text-paper border-ink'
                      : 'bg-transparent text-ink-soft border-rule hover:border-ink hover:text-ink'"
                    @click="details.status = opt.value"
                  >
                    {{ opt.label }}
                  </button>
                </div>
              </fieldset>

              <!-- Acquired -->
              <div>
                <label for="acquired" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
                  When did it arrive?
                </label>
                <input
                  id="acquired"
                  v-model="details.acquired"
                  type="date"
                  class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink focus:outline-none focus:border-ink transition-colors font-mono text-sm"
                >
              </div>

              <!-- Notes -->
              <div>
                <label for="notes" class="block text-[11px] uppercase tracking-widest text-ink-soft mb-2">
                  How does it sit with you?
                </label>
                <textarea
                  id="notes"
                  v-model="details.notes"
                  rows="3"
                  placeholder="A line or two — what it brings to mind."
                  class="w-full bg-transparent border-b border-rule px-0 py-2 text-ink placeholder:text-ink-mute focus:outline-none focus:border-ink transition-colors resize-none"
                />
              </div>

              <button
                type="submit"
                :disabled="submitting"
                class="w-full bg-ink text-paper text-xs uppercase tracking-widest py-4 hover:bg-ink-soft transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ submitting ? 'Adding to shelf…' : 'Add to my shelf' }}
              </button>
            </form>
          </div>
        </div>
      </section>

      <!-- ── Confirmation ──────────────────────────────────── -->
      <section v-else key="step-4" class="max-w-xl mx-auto text-center pt-16">
        <Icon name="lucide:check" class="h-8 w-8 text-ink mx-auto" />
        <h1 class="mt-8 font-serif text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
          Added.
        </h1>
        <p class="mt-6 font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          {{ selectedBrand?.name }} &middot; {{ selectedPerfume?.name }}
        </p>
        <p class="mt-8 text-base text-ink-soft">
          It's on your shelf.
        </p>
      </section>
    </Transition>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

type Brand = { id: number; code: string; name: string; image?: string }
type Perfume = {
  id: number
  brand: string
  name: string
  image?: string
  size: number
  year?: string
  main_accord?: string
}

const api = useApi()
const asset = useAssetUrl()
const router = useRouter()

const step = ref<1 | 2 | 3 | 4>(1)
const direction = ref<'forward' | 'back'>('forward')

const brands = ref<Brand[]>([])
const perfumes = ref<Perfume[]>([])
const loadingBrands = ref(true)

const selectedBrand = ref<Brand | null>(null)
const selectedPerfume = ref<Perfume | null>(null)

const today = new Date().toISOString().split('T')[0]
const details = reactive({
  size: 50 as number | null,
  acquired: today,
  status: '' as 'full' | 'half' | 'nearly-empty' | '',
  notes: '',
})
const submitting = ref(false)

const sizeOptions = [
  { label: '30 ml', value: 30 },
  { label: '50 ml', value: 50 },
  { label: '75 ml', value: 75 },
  { label: '100 ml', value: 100 },
  { label: 'Decant', value: null },
]

const statusOptions = [
  { label: 'Full', value: 'full' as const },
  { label: 'Half', value: 'half' as const },
  { label: 'Nearly empty', value: 'nearly-empty' as const },
]

// Step 4 is the confirmation; the indicator should hold at 3.
const displayStep = computed(() => Math.min(step.value, 3))

const filteredPerfumes = computed(() =>
  perfumes.value.filter(p => p.brand === selectedBrand.value?.code),
)

const perfumeCountByBrand = (code: string) =>
  perfumes.value.filter(p => p.brand === code).length

const formatAccord = (raw: string) =>
  raw.split(',').map(s => s.trim()).filter(Boolean).join(' · ')

const selectBrand = (brand: Brand) => {
  selectedBrand.value = brand
  direction.value = 'forward'
  step.value = 2
}

const selectPerfume = (perfume: Perfume) => {
  selectedPerfume.value = perfume
  direction.value = 'forward'
  step.value = 3
}

const goBack = () => {
  direction.value = 'back'
  if (step.value === 1) {
    router.push('/vanity')
    return
  }
  step.value = (step.value - 1) as 1 | 2 | 3
}

const handleSubmit = async () => {
  if (submitting.value) return
  submitting.value = true

  // TODO(step-4): POST to /api/vanity once the backend model lands.
  console.log('[vanity/add] mock submit', {
    brand: selectedBrand.value?.code,
    perfume_id: selectedPerfume.value?.id,
    ...details,
  })

  await new Promise(r => setTimeout(r, 600))
  submitting.value = false
  direction.value = 'forward'
  step.value = 4

  setTimeout(() => router.push('/vanity'), 1800)
}

onMounted(async () => {
  try {
    const [brandData, perfumeData] = await Promise.all([
      api.get('/brand'),
      api.get('/perfume'),
    ])
    brands.value = brandData
    perfumes.value = perfumeData
  } finally {
    loadingBrands.value = false
  }
})
</script>
