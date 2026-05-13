import tailwindcss from '@tailwindcss/vite'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@pinia/nuxt', '@nuxt/icon'],

  icon: {
    // `svg` renders each <Icon> as an inline <svg> with the path bundled
    // at build time. Pairs reliably with Tailwind 4 (CSS-layer mode has
    // ordering quirks in v4) and handles dynamic icon names (e.g.
    // <Icon :name="someVar" />) that CSS-mode's static scanner misses.
    mode: 'svg',
    collections: ['lucide'],
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
