import { defineStore } from 'pinia'

export type WardrobeItem = {
  id: string                  // local UUID, scoped to this device until backend persistence lands
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
    init() {
      if (!import.meta.client || this.hydrated) return
      try {
        const raw = localStorage.getItem(STORAGE_KEY)
        if (raw) this.items = JSON.parse(raw)
      } catch {
        // corrupted cache — start fresh
      }
      this.hydrated = true
    },

    persist() {
      if (!import.meta.client) return
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.items))
    },

    add(input: Omit<WardrobeItem, 'id' | 'created_at'>) {
      const item: WardrobeItem = {
        ...input,
        id: crypto.randomUUID(),
        created_at: new Date().toISOString(),
      }
      this.items.unshift(item)
      this.persist()
      return item
    },

    remove(id: string) {
      this.items = this.items.filter(i => i.id !== id)
      this.persist()
    },

    clear() {
      this.items = []
      this.persist()
    },
  },
})
