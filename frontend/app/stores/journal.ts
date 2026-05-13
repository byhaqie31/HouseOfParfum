import { defineStore } from 'pinia'

export type Longevity = 'brief' | 'settled' | 'lasting' | 'all-day' | 'into-night'

export type JournalEntry = {
  id: string
  vanity_item_id: string | null   // links to VanityItem when worn from your shelf
  brand: string                   // denormalized so the entry survives a vanity edit
  name: string
  worn_at: string                 // ISO timestamp

  // Diary fields. All optional — quick wears from /today log with none of these set;
  // detailed wears from /vanity/[id] can fill any subset.
  experience?: string             // personal feeling — how it sat with you today
  compliments?: string            // what others said
  longevity?: Longevity | null    // how long it lasted

  // Legacy single-field note kept for entries created before the diary fields landed.
  notes?: string
}

const STORAGE_KEY = 'hop.journal'

export const useJournalStore = defineStore('journal', {
  state: () => ({
    entries: [] as JournalEntry[],
    hydrated: false,
  }),

  getters: {
    count: state => state.entries.length,

    /** Most recent worn_at (ISO) for the given vanity item, or null if never worn. */
    lastWornAt: state => (vanityItemId: string): string | null => {
      let latest: string | null = null
      for (const e of state.entries) {
        if (e.vanity_item_id !== vanityItemId) continue
        if (!latest || e.worn_at > latest) latest = e.worn_at
      }
      return latest
    },

    /** Total wears for the given vanity item. */
    wearCount: state => (vanityItemId: string): number =>
      state.entries.reduce(
        (n, e) => n + (e.vanity_item_id === vanityItemId ? 1 : 0),
        0,
      ),

    /** Entries sorted newest → oldest. */
    sorted: state =>
      [...state.entries].sort((a, b) => (a.worn_at < b.worn_at ? 1 : -1)),
  },

  actions: {
    init() {
      if (!import.meta.client || this.hydrated) return
      try {
        const raw = localStorage.getItem(STORAGE_KEY)
        if (raw) this.entries = JSON.parse(raw)
      } catch {
        // corrupted cache — start fresh
      }
      this.hydrated = true
    },

    persist() {
      if (!import.meta.client) return
      localStorage.setItem(STORAGE_KEY, JSON.stringify(this.entries))
    },

    log(input: Omit<JournalEntry, 'id' | 'worn_at'> & { worn_at?: string }) {
      const entry: JournalEntry = {
        ...input,
        id: crypto.randomUUID(),
        worn_at: input.worn_at ?? new Date().toISOString(),
      }
      this.entries.unshift(entry)
      this.persist()
      return entry
    },

    /**
     * Patch a journal entry. Used as the user updates today's diary
     * (experience, compliments, longevity) over the course of a wear.
     */
    update(id: string, patch: Partial<Omit<JournalEntry, 'id'>>) {
      const idx = this.entries.findIndex(e => e.id === id)
      if (idx === -1) return
      this.entries[idx] = { ...this.entries[idx], ...patch } as JournalEntry
      this.persist()
    },

    remove(id: string) {
      this.entries = this.entries.filter(e => e.id !== id)
      this.persist()
    },

    clear() {
      this.entries = []
      this.persist()
    },
  },
})
