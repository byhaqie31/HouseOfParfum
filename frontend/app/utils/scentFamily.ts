/* Map a discovery-catalogue accord/note vocabulary onto one of the seven scent
   families that drive the colour system. Mirrors the backend AccordProfile
   keyword buckets but splits them into the 7 families the UI colours by.
   Used to pre-select the family chip when adding a bottle from the catalogue,
   and to colour catalog-linked bottles that have no explicit family yet. */

import { FAMILY_ORDER, type ScentFamilyKey } from './scent'

// Keyword → family. Scanned as substrings against lowercased accords + notes.
// Ordered most-specific first within each family.
const FAMILY_KEYWORDS: Record<ScentFamilyKey, string[]> = {
  citrus: ['citrus', 'lime', 'lemon', 'bergamot', 'orange', 'grapefruit', 'mandarin', 'yuzu', 'neroli', 'lemongrass'],
  aquatic: ['aquatic', 'marine', 'ozonic', 'water', 'sea', 'salt', 'fresh', 'oceanic', 'rain', 'melon', 'cucumber'],
  gourmand: ['gourmand', 'sweet', 'vanilla', 'caramel', 'honey', 'chocolate', 'cacao', 'coffee', 'praline', 'tonka', 'sugar', 'nutty', 'almond'],
  powdery: ['powdery', 'iris', 'orris', 'violet', 'aldehyd', 'soft spicy'],
  spicy: ['spicy', 'cinnamon', 'pepper', 'clove', 'cardamom', 'saffron', 'leather', 'suede', 'ginger', 'nutmeg'],
  woody: ['woody', 'oud', 'sandalwood', 'cedar', 'vetiver', 'amber', 'oriental', 'balsamic', 'mossy', 'chypre', 'smoky', 'incense', 'tobacco', 'musk', 'earthy', 'patchouli', 'resin'],
  floral: ['floral', 'rose', 'jasmine', 'tuberose', 'peony', 'lily', 'magnolia', 'ylang', 'gardenia', 'lavender', 'white flower', 'flower'],
}

/**
 * Derive the most likely scent family from a list of accords (and optional
 * supporting notes). Scores each family by keyword hits, weighting earlier
 * accords higher since the catalogue orders them by prominence.
 */
export function deriveFamily(
  accords: string[] | null | undefined,
  fallback: ScentFamilyKey = 'woody',
): ScentFamilyKey {
  const terms = (accords ?? []).map((a) => String(a).toLowerCase())
  if (!terms.length) return fallback

  const scores = {} as Record<ScentFamilyKey, number>
  for (const fam of FAMILY_ORDER) scores[fam] = 0

  terms.forEach((term, idx) => {
    const weight = Math.max(1, terms.length - idx) // earlier accord = more prominent
    for (const fam of FAMILY_ORDER) {
      if (FAMILY_KEYWORDS[fam].some((kw) => term.includes(kw))) {
        scores[fam] += weight
      }
    }
  })

  let best: ScentFamilyKey | null = null
  let bestScore = 0
  for (const fam of FAMILY_ORDER) {
    if (scores[fam] > bestScore) {
      bestScore = scores[fam]
      best = fam
    }
  }

  return best ?? fallback
}
