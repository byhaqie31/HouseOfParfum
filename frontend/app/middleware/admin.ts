export default defineNuxtRouteMiddleware(() => {
  if (import.meta.server) return

  const auth = useAuthStore()

  if (!auth.token) {
    return navigateTo('/auth/login')
  }

  if (auth.user?.role !== 'admin') {
    return navigateTo('/user/today')
  }
})
