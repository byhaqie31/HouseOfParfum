import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null as string | null,
    user: null as Record<string, any> | null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.token,
  },

  actions: {
    setToken(token: string) {
      this.token = token
      if (import.meta.client) localStorage.setItem('token', token)
    },

    setUser(user: Record<string, any>) {
      this.user = user
    },

    logout() {
      this.token = null
      this.user = null
      if (import.meta.client) localStorage.removeItem('token')
    },

    init() {
      if (import.meta.client) {
        const token = localStorage.getItem('token')
        if (token) this.token = token
      }
    },
  },
})
