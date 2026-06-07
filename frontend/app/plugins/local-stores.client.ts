// Hydrate the wardrobe + journal Pinia stores on app start so page components
// can read state after mount. Both seed instantly from localStorage, then the
// wardrobe pulls from the API (in init) and the journal refreshes from the API
// here — wear counts, last-worn and the calendar are surfaced app-wide (Today,
// shelf, detail), not just on the journal page.
export default defineNuxtPlugin(() => {
  const wardrobe = useWardrobeStore()
  const journal = useJournalStore()
  wardrobe.init()
  journal.init()
  journal.load()
})
