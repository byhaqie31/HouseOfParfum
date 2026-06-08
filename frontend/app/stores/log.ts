import { defineStore } from 'pinia'
import type { WardrobeItem } from '~/stores/wardrobe'

/** Drives the global log-wear modal: any "log" action opens it with a target bottle. */
export const useLogStore = defineStore('log', {
  state: () => ({
    open: false,
    item: null as WardrobeItem | null,
  }),
  actions: {
    start(item: WardrobeItem) {
      this.item = item
      this.open = true
    },
    close() {
      this.open = false
    },
  },
})
