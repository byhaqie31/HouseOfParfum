// Hydrate the local-mock Pinia stores (vanity + journal) on app start so
// page components can read state synchronously after mount. Both stores
// are localStorage-backed until real backend persistence lands.
export default defineNuxtPlugin(() => {
  const vanity = useVanityStore()
  const journal = useJournalStore()
  vanity.init()
  journal.init()
})
