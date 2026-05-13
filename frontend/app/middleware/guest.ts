export default defineNuxtRouteMiddleware(() => {
  // Skip on the server — see note in auth.ts. The .client plugin hydrates
  // the auth store before this runs on the client.
  if (import.meta.server) return

  const auth = useAuthStore()
  if (auth.isLoggedIn) {
    return navigateTo('/today')
  }
})
