import { defineStore } from 'pinia'

export type ToastItem = {
  id: number
  message: string
  type: 'success' | 'error'
}

const DURATION = 3500

export const useToastStore = defineStore('toast', {
  state: () => ({
    items: [] as ToastItem[],
  }),
  actions: {
    show(message: string, type: ToastItem['type']) {
      if (!import.meta.client) return
      const id = Date.now()
      this.items.push({ id, message, type })
      setTimeout(() => {
        this.items = this.items.filter(t => t.id !== id)
      }, DURATION)
    },
    success(message: string) { this.show(message, 'success') },
    error(message: string) { this.show(message, 'error') },
  },
})
