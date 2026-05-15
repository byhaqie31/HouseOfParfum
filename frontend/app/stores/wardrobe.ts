import { defineStore } from 'pinia'

export type WardrobeItem = {
  id: string                  // UUID — generated client-side, used as PK on the backend too
  catalog_id: number | null   // optional reference to /api/perfume id (free entry leaves it null)
  brand: string               // brand display name (denormalized — survives catalog edits)
  name: string                // fragrance name
  size: number                // ml
  acquired: string            // free text — "March 2025"
  notes: string
  created_at: string          // ISO timestamp
}

const STORAGE_KEY = 'hop.wardrobe'

export const useWardrobeStore = defineStore('wardrobe', {
  state: () => ({
    items: [] as WardrobeItem[],
    hydrated: false,
  }),

  getters: {
    count: state => state.items.length,
    byId: state => (id: string) => state.items.find(i => i.id === id),
  },

  actions: {
    async init() {
      if (!import.meta.client || this.hydrated) return
      const auth = useAuthStore()
      if (auth.token) {
        try {
          const api = useApi()
          const data = await api.get('/wardrobe')
          this.items = Array.isArray(data) ? data : []
          this.persist()
        } catch {
          this._loadFromStorage()
        }
      } else {
        this._loadFromStorage()
      }
      this.hydrated = true
    },

    _loadFromStorage() {
      try {
        const raw = localStorage.getItem(STORAGE_KEY)
        if (raw) this.items = JSON.parse(raw)
      } catch {
        // corrupted cache — start fresh
      }
    },

    persist() {
      if (!import.meta.client) return
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items))
    },

    async add(input: Omit<WardrobeItem, 'id' | 'created_at'>) {
      const item: WardrobeItem = {
        ...input,
        id: crypto.randomUUID(),
        created_at: new Date().toISOString(),
      }
      const auth = useAuthStore()
      if (auth.token) {
        const api = useApi()
        const saved = await api.post('/wardrobe', item) as WardrobeItem
        this.items.unshift(saved)
      } else {
        this.items.unshift(item)
      }
      this.persist()
      return item
    },

    async remove(id: string) {
      const auth = useAuthStore()
      if (auth.token) {
        const api = useApi()
        await api.delete(`/wardrobe/${id}`)
      }
      this.items = this.items.filter(i => i.id !== id)
      this.persist()
    },

    clear() {
      this.items = []
      this.persist()
    },
  },
})
