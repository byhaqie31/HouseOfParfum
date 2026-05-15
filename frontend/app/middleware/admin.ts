export default defineNuxtRouteMiddleware(() => {
  if (import.meta.server) return

  const auth = useAuthStore()

  if (!auth.token || auth.user?.role !== 'admin') {
    return navigateTo('/auth/login')
  }
})
