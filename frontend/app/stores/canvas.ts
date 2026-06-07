import { defineStore } from 'pinia'

export type CanvasMode = 'light' | 'dark'

const KEY = 'hop.mode'

function applyMode(mode: CanvasMode) {
  if (!import.meta.client) return
  document.documentElement.dataset.mode = mode
  // Keep the browser chrome in step with the canvas.
  const meta = document.querySelector('meta[name="theme-color"]')
  if (meta) meta.setAttribute('content', mode === 'dark' ? '#1c1a26' : '#f7f5ef')
}

export const useCanvasStore = defineStore('canvas', {
  state: () => ({
    mode: 'light' as CanvasMode,
    hydrated: false,
  }),
  getters: {
    isDark: (s) => s.mode === 'dark',
  },
  actions: {
    init() {
      if (!import.meta.client || this.hydrated) return
      const stored = localStorage.getItem(KEY)
      if (stored === 'light' || stored === 'dark') {
        this.mode = stored
      }
      applyMode(this.mode)
      this.hydrated = true
    },
    set(mode: CanvasMode) {
      this.mode = mode
      if (import.meta.client) {
        localStorage.setItem(KEY, mode)
        applyMode(mode)
      }
    },
    toggle() {
      this.set(this.mode === 'dark' ? 'light' : 'dark')
    },
  },
})
