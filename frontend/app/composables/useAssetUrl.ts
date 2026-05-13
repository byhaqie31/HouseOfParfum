// Catalog images (brand logos, perfume bottles) live on the Laravel public
// disk and are served from the backend's host, not Nuxt's. Browser <img> tags
// must resolve against the browser-side base so the host loopback works.
export const useAssetUrl = () => {
  const config = useRuntimeConfig()
  const host = config.public.apiBase.replace(/\/api\/?$/, '')

  return (path?: string | null) => {
    if (!path) return ''
    return `${host}/${path.replace(/^\//, '')}`
  }
}
