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
      if (import.meta.client) localStorage.setItem('user', JSON.stringify(user))
    },

    logout() {
      this.token = null
      this.user = null
      if (import.meta.client) {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    },

    init() {
      if (import.meta.client) {
        const token = localStorage.getItem('token')
        if (token) this.token = token
        const userJson = localStorage.getItem('user')
        if (userJson) {
          try {
            this.user = JSON.parse(userJson)
          } catch {
            /* corrupted cache — ignore */
          }
        }
      }
    },
  },
})
