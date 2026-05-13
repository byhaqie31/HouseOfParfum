// Hydrate the local-mock Pinia stores (wardrobe + journal) on app start so
// page components can read state synchronously after mount. Both stores
// are localStorage-backed until real backend persistence lands.
export default defineNuxtPlugin(() => {
  const wardrobe = useWardrobeStore()
  const journal = useJournalStore()
  wardrobe.init()
  journal.init()
})
