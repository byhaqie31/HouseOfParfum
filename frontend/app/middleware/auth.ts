export default defineNuxtRouteMiddleware(() => {
  // Skip on the server — the token lives in localStorage and isn't visible to
  // SSR. The .client plugin hydrates the auth store before this runs on the
  // client, so by then we know the real state.
  if (import.meta.server) return

  const auth = useAuthStore()
  if (!auth.isLoggedIn) {
    return navigateTo('/')
  }
})
