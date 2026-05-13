// Hydrate the auth store from localStorage before any route middleware runs.
// localStorage is browser-only, so this plugin is .client.ts (skipped on SSR).
export default defineNuxtPlugin(() => {
  const auth = useAuthStore()
  auth.init()
})
