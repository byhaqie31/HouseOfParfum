import type { ScentFamilyKey } from '~/utils/scent'

/** One row of the curation registry, as returned by /admin/perfumes. */
export interface RegistryPerfume {
  id: number
  name: string
  brand: string
  year: number | null
  rating: number | null
  family: ScentFamilyKey | null
  notes: { top: string[]; heart: string[]; base: string[] }
  image: string | null
  history: string | null
  wears: number
}

/** The patch the curate drawer emits on save. */
export interface CuratePayload {
  id: number
  family: ScentFamilyKey | null
  notes: { top: string[]; heart: string[]; base: string[] }
  image: string | null
  history: string | null
}
