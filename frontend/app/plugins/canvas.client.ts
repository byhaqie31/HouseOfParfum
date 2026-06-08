// Hydrate the canvas-mode store from localStorage on app start and reflect
// it on <html data-mode>. A no-flash inline script in app.vue applies the
// attribute before paint; this keeps the Pinia store in sync afterwards.
export default defineNuxtPlugin(() => {
  useCanvasStore().init()
})
