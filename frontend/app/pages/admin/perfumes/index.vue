<template>
  <div class="space-y-6">

    <!-- Search -->
    <div class="flex items-center gap-3">
      <div class="relative flex-1 max-w-sm">
        <Icon name="lucide:search" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-mute" size="14" />
        <input
          v-model="searchInput"
          type="text"
          placeholder="Search perfumes…"
          class="w-full pl-9 pr-4 py-2 border border-rule bg-paper text-sm text-ink placeholder:text-ink-mute focus:outline-none focus:border-accent transition-colors"
        >
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
      Loading…
    </div>

    <!-- Empty -->
    <div v-else-if="!perfumes.length" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
      No perfumes found.
    </div>

    <template v-else>
      <!-- Desktop table (md+) -->
      <div class="hidden md:block border border-rule">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-rule">
              <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Name</th>
              <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Brand</th>
              <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Year</th>
              <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Rating</th>
              <th class="px-6 py-3 text-center font-mono text-[10px] uppercase tracking-widest text-ink-mute">Image</th>
              <th class="px-6 py-3 text-center font-mono text-[10px] uppercase tracking-widest text-ink-mute">History</th>
              <th class="px-6 py-3" />
            </tr>
          </thead>
          <tbody>
            <template v-for="perfume in perfumes" :key="perfume.id">
              <tr
                class="border-b border-rule hover:bg-paper-deep transition-colors"
                :class="{ 'bg-paper-deep': expandedId === perfume.id }"
              >
                <td class="px-6 py-4 text-ink font-medium">{{ perfume.name }}</td>
                <td class="px-6 py-4 text-ink-soft">{{ perfume.brand }}</td>
                <td class="px-6 py-4 font-mono text-xs text-ink-mute">{{ perfume.year ?? '—' }}</td>
                <td class="px-6 py-4 font-mono text-xs text-ink-mute">{{ perfume.rating != null ? perfume.rating : '—' }}</td>
                <td class="px-6 py-4 text-center">
                  <span v-if="perfume.image" class="inline-block w-2 h-2 rounded-full bg-green-500" title="Has image" />
                  <span v-else class="text-ink-mute">—</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span v-if="perfume.history" class="inline-block w-2 h-2 rounded-full bg-green-500" title="Has history" />
                  <span v-else class="text-ink-mute">—</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button
                    class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors"
                    @click="toggleExpand(perfume.id)"
                  >
                    {{ expandedId === perfume.id ? 'Close' : 'Edit' }}
                  </button>
                </td>
              </tr>
              <!-- Inline edit form -->
              <tr v-if="expandedId === perfume.id" :key="`${perfume.id}-edit`">
                <td colspan="7" class="p-0">
                  <div class="bg-paper border-t border-rule p-4 space-y-3">
                    <div>
                      <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Image URL</label>
                      <input
                        v-model="editForm.image"
                        type="text"
                        class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                        placeholder="https://…"
                      >
                    </div>
                    <div>
                      <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">History</label>
                      <textarea
                        v-model="editForm.history"
                        rows="5"
                        class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                        placeholder="Enter history…"
                      />
                    </div>
                    <div class="flex items-center gap-4 pt-1">
                      <button
                        class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
                        :disabled="saving"
                        @click="saveEdit(perfume)"
                      >
                        {{ saving ? 'Saving…' : 'Save' }}
                      </button>
                      <button
                        class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                        @click="cancelEdit"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <!-- Mobile cards (< md) -->
      <div class="md:hidden border border-rule divide-y divide-rule">
        <div v-for="perfume in perfumes" :key="perfume.id">
          <div class="px-4 py-4 space-y-2">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="text-sm text-ink font-medium truncate">{{ perfume.name }}</p>
                <p class="font-mono text-[10px] text-ink-mute truncate">{{ perfume.brand }}</p>
              </div>
              <button
                class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors shrink-0"
                @click="toggleExpand(perfume.id)"
              >
                {{ expandedId === perfume.id ? 'Close' : 'Edit' }}
              </button>
            </div>
            <div class="flex items-center gap-4">
              <span class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">{{ perfume.year ?? '—' }}</span>
              <span class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">{{ perfume.rating != null ? `${perfume.rating} ★` : 'No rating' }}</span>
              <span class="flex items-center gap-1 font-mono text-[10px] text-ink-mute uppercase tracking-widest">
                <span :class="perfume.image ? 'text-green-500' : ''">
                  {{ perfume.image ? '● Img' : '○ Img' }}
                </span>
              </span>
              <span class="flex items-center gap-1 font-mono text-[10px] text-ink-mute uppercase tracking-widest">
                <span :class="perfume.history ? 'text-green-500' : ''">
                  {{ perfume.history ? '● Hist' : '○ Hist' }}
                </span>
              </span>
            </div>
          </div>
          <!-- Inline edit form (mobile) -->
          <div v-if="expandedId === perfume.id" class="bg-paper border-t border-rule p-4 space-y-3">
            <div>
              <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">Image URL</label>
              <input
                v-model="editForm.image"
                type="text"
                class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors"
                placeholder="https://…"
              >
            </div>
            <div>
              <label class="block font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-1">History</label>
              <textarea
                v-model="editForm.history"
                rows="5"
                class="w-full border border-rule bg-paper px-3 py-2 text-sm text-ink focus:outline-none focus:border-accent transition-colors resize-none"
                placeholder="Enter history…"
              />
            </div>
            <div class="flex items-center gap-4 pt-1">
              <button
                class="bg-ink text-paper text-xs uppercase tracking-widest px-4 py-2 hover:bg-ink-soft transition-colors disabled:opacity-40"
                :disabled="saving"
                @click="saveEdit(perfume)"
              >
                {{ saving ? 'Saving…' : 'Save' }}
              </button>
              <button
                class="text-xs uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
                @click="cancelEdit"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Pagination -->
    <div v-if="meta && !loading" class="flex items-center justify-between">
      <p class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">
        {{ meta.from }}–{{ meta.to }} of {{ meta.total }}
      </p>
      <div class="flex items-center gap-3">
        <button
          class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-30"
          :disabled="!meta.prev_page_url"
          @click="loadPage(page - 1)"
        >
          ← Prev
        </button>
        <button
          class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-30"
          :disabled="!meta.next_page_url"
          @click="loadPage(page + 1)"
        >
          Next →
        </button>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()

interface Perfume {
  id: number
  name: string
  brand: string
  year: number | null
  rating: number | null
  image: string | null
  history: string | null
}

interface Meta {
  from: number
  to: number
  total: number
  prev_page_url: string | null
  next_page_url: string | null
}

const perfumes = ref<Perfume[]>([])
const meta = ref<Meta | null>(null)
const loading = ref(true)
const page = ref(1)
const searchInput = ref('')
const expandedId = ref<number | null>(null)
const saving = ref(false)
const editForm = ref({ image: '', history: '' })

let debounceTimer: ReturnType<typeof setTimeout> | null = null

watch(searchInput, () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    page.value = 1
    loadPage(1)
  }, 400)
})

const loadPage = async (p: number) => {
  loading.value = true
  page.value = p
  const res = await api.get(`/admin/perfumes?search=${encodeURIComponent(searchInput.value)}&page=${p}`)
  perfumes.value = res.data
  meta.value = res
  loading.value = false
}

const toggleExpand = (id: number) => {
  if (expandedId.value === id) {
    expandedId.value = null
    return
  }
  const perfume = perfumes.value.find(p => p.id === id)
  editForm.value = {
    image: perfume?.image ?? '',
    history: perfume?.history ?? '',
  }
  expandedId.value = id
}

const cancelEdit = () => {
  expandedId.value = null
}

const saveEdit = async (perfume: Perfume) => {
  saving.value = true
  try {
    const updated = await api.patch(`/admin/perfumes/${perfume.id}`, {
      image: editForm.value.image || null,
      history: editForm.value.history || null,
    })
    const idx = perfumes.value.findIndex(p => p.id === perfume.id)
    if (idx !== -1) {
      perfumes.value[idx] = { ...perfumes.value[idx], ...updated }
    }
    expandedId.value = null
  }
  finally {
    saving.value = false
  }
}

onMounted(() => loadPage(1))
</script>
