import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@pinia/nuxt', '@nuxt/icon'],

  icon: {
    defaultCollection: 'lucide',
  },

  css: ['~/assets/css/main.css'],

  vite: {
    plugins: [tailwindcss()],
  },

  runtimeConfig: {
    // Server-side base — populated from NUXT_API_BASE (e.g. http://backend:8000/api).
    apiBase: '',
    public: {
      // Browser-side base — populated from NUXT_PUBLIC_API_BASE (e.g. http://127.0.0.1:8000/api).
      apiBase: '',
    },
  },
})
