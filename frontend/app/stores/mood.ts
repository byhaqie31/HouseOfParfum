import { defineStore } from 'pinia'
import type { Mood, Weather, Occasion } from '~/data/scent-matching'

export type TodayMood = {
  mood: Mood
  weather: Weather
  occasion: Occasion
}

// Keeps the same key as the old ScentMatcher localStorage format so
// unauthenticated users don't lose their in-progress day.
const STORAGE_KEY = 'hop:scent-match:v1'

function todayStr(): string {
  const d = new Date()
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
}

export const useMoodStore = defineStore('mood', {
  state: () => ({
    today: null as TodayMood | null,
    hydrated: false,
  }),

  actions: {
    async init() {
      if (!import.meta.client || this.hydrated) return
      const auth = useAuthStore()
      if (auth.token) {
        try {
          const api = useApi()
          const data = await api.get('/mood/today')
          this.today = data?.mood
            ? { mood: data.mood as Mood, weather: data.weather as Weather, occasion: data.occasion as Occasion }
            : null
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
        if (!raw) return
        const parsed = JSON.parse(raw)
        if (parsed.date !== todayStr()) return
        this.today = { mood: parsed.mood, weather: parsed.weather, occasion: parsed.occasion }
      } catch {
        // corrupted — ignore
      }
    },

    async save(mood: Mood, weather: Weather, occasion: Occasion) {
      this.today = { mood, weather, occasion }
      // Always write localStorage so the picker survives a refresh even without auth.
      if (import.meta.client) {
        try {
          localStorage.setItem(STORAGE_KEY, JSON.stringify({ date: todayStr(), mood, weather, occasion }))
        } catch {
          // quota / disabled — non-critical
        }
      }
      const auth = useAuthStore()
      if (auth.token) {
        const api = useApi()
        await api.post('/mood/today', { mood, weather, occasion }).catch(() => null)
      }
    },
  },
})
