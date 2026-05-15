export const useApi = () => {
  const config = useRuntimeConfig()
  const auth = useAuthStore()

  // SSR calls the backend over the docker network (apiBase);
  // browser calls go via the host loopback (public.apiBase).
  const baseUrl = import.meta.server ? config.apiBase : config.public.apiBase

  const request = async (endpoint: string, options: RequestInit = {}) => {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    }

    if (auth.token) {
      headers['Authorization'] = `Bearer ${auth.token}`
    }

    const response = await fetch(`${baseUrl}${endpoint}`, {
      ...options,
      headers: { ...headers, ...(options.headers as Record<string, string>) },
    })

    if (!response.ok) {
      const error = await response.json()
      throw error
    }

    return response.json()
  }

  return {
    get: (endpoint: string) => request(endpoint),
    post: (endpoint: string, body: unknown) =>
      request(endpoint, { method: 'POST', body: JSON.stringify(body) }),
    put: (endpoint: string, body: unknown) =>
      request(endpoint, { method: 'PUT', body: JSON.stringify(body) }),
    patch: (endpoint: string, body: unknown) =>
      request(endpoint, { method: 'PATCH', body: JSON.stringify(body) }),
    delete: (endpoint: string) => request(endpoint, { method: 'DELETE' }),
  }
}
