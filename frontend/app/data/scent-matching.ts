// Scent-matching rules: turn (mood, weather, occasion) into a perfume from the
// real catalog by comparing perceptual fingerprints. No AI — deterministic, fast,
// and editorial. AI is reserved for layering suggestions on top of a chosen base.

export type Fingerprint = {
  intensity: number   // 0..10, projection / sillage weight
  freshness: number   // 0..10, citrus / aquatic / green
  sweetness: number   // 0..10, gourmand / honey / vanilla
  warmth: number      // 0..10, amber / spice / wood
  florality: number   // 0..10, floral prominence
  darkness: number    // 0..10, leather / incense / oud / animalic
}

export type Mood =
  | 'calm'
  | 'playful'
  | 'romantic'
  | 'bold'
  | 'cocooning'

export type Weather =
  | 'hot_dry'
  | 'hot_humid'
  | 'cool'
  | 'cold'
  | 'rainy'

export type Occasion =
  | 'work'
  | 'leisure'
  | 'evening'
  | 'date'
  | 'active'

// ────────────────────────────────────────────────────────────────
//   Display labels (UI uses these)
// ────────────────────────────────────────────────────────────────

export const MOOD_LABELS: Record<Mood, string> = {
  calm: 'Calm',
  playful: 'Playful',
  romantic: 'Romantic',
  bold: 'Bold',
  cocooning: 'Cocooning',
}

export const WEATHER_LABELS: Record<Weather, string> = {
  hot_dry: 'Hot & dry',
  hot_humid: 'Hot & humid',
  cool: 'Cool',
  cold: 'Cold',
  rainy: 'Rainy',
}

export const OCCASION_LABELS: Record<Occasion, string> = {
  work: 'Work',
  leisure: 'Leisure',
  evening: 'Evening',
  date: 'Date',
  active: 'Active',
}

// ────────────────────────────────────────────────────────────────
//   Input mappings — contribute deltas to the target fingerprint
// ────────────────────────────────────────────────────────────────

type Delta = Partial<Fingerprint>

const MOOD_DELTA: Record<Mood, Delta> = {
  calm:      { warmth: +2, darkness: +1, intensity: -1 },
  playful:   { freshness: +2, florality: +2, darkness: -1 },
  romantic:  { sweetness: +2, florality: +2, warmth: +1 },
  bold:      { intensity: +2, darkness: +2, warmth: +1 },
  cocooning: { sweetness: +2, warmth: +2, intensity: -1 },
}

const WEATHER_DELTA: Record<Weather, Delta> = {
  hot_dry:   { freshness: +2, sweetness: -1, warmth: -2 },
  hot_humid: { freshness: +2, intensity: -2 },
  cool:      { warmth: +1, intensity: +1 },
  cold:      { warmth: +2, sweetness: +1, intensity: +1 },
  rainy:     { darkness: +2, warmth: +1 },
}

const OCCASION_DELTA: Record<Occasion, Delta> = {
  work:    { intensity: -2, freshness: +1 },
  leisure: { freshness: +1 },
  evening: { intensity: +2, darkness: +2, warmth: +1 },
  date:    { sweetness: +1, warmth: +2, intensity: +1 },
  active:  { freshness: +2, sweetness: -1 },
}

// ────────────────────────────────────────────────────────────────
//   Perfume → Fingerprint (derived from existing catalog fields)
// ────────────────────────────────────────────────────────────────

// Coarse mapping from a main-accord token to fingerprint deltas. Tokens are
// lowercased and matched as substrings so "Amber, Floral" yields both rows.
const ACCORD_DELTA: Array<[string, Delta]> = [
  ['citrus',   { freshness: +3 }],
  ['aquatic',  { freshness: +3, intensity: -1 }],
  ['marine',   { freshness: +3, intensity: -1 }],
  ['green',    { freshness: +2 }],
  ['aromatic', { freshness: +1 }],
  ['fruity',   { sweetness: +2, freshness: +1 }],
  ['gourmand', { sweetness: +3, warmth: +1 }],
  ['floral',   { florality: +3 }],
  ['powdery',  { florality: +1 }],
  ['iris',     { florality: +2 }],
  ['amber',    { warmth: +3, sweetness: +1, darkness: +1 }],
  ['oriental', { warmth: +2, darkness: +2, intensity: +2 }],
  ['spicy',    { warmth: +2, intensity: +1 }],
  ['woody',    { warmth: +2, darkness: +2 }],
  ['chypre',   { warmth: +1, darkness: +1 }],
  ['leather',  { darkness: +3, warmth: +1 }],
  ['oud',      { darkness: +3, warmth: +2, intensity: +2 }],
  ['incense',  { darkness: +2, warmth: +1 }],
  ['musk',     { warmth: +1, sweetness: +1 }],
  ['vanilla',  { sweetness: +3, warmth: +2 }],
  ['tobacco',  { darkness: +2, warmth: +2 }],
]

// Bare minimum we need from the catalog payload. Matches /api/perfume shape.
export type CatalogPerfume = {
  id: number
  brand: string
  name: string
  image?: string | null
  size?: number | null
  main_accord?: string | null
  top_notes?: string | null
  middle_notes?: string | null
  base_notes?: string | null
  suit_season?: string | null
  suit_time?: string | null
  percent_summer?: number | null
  percent_autumn?: number | null
  percent_winter?: number | null
  percent_day?: number | null
  percent_night?: number | null
}

function emptyFp(): Fingerprint {
  return { intensity: 5, freshness: 5, sweetness: 5, warmth: 5, florality: 5, darkness: 5 }
}

function applyDelta(fp: Fingerprint, delta: Delta): void {
  for (const k of Object.keys(delta) as Array<keyof Fingerprint>) {
    fp[k] = clamp(fp[k] + (delta[k] ?? 0))
  }
}

function clamp(n: number, lo = 0, hi = 10): number {
  return Math.max(lo, Math.min(hi, n))
}

export function derivePerfumeFingerprint(p: CatalogPerfume): Fingerprint {
  const fp = emptyFp()

  // Accord-driven attributes (the strongest signal)
  const accord = (p.main_accord ?? '').toLowerCase()
  for (const [token, delta] of ACCORD_DELTA) {
    if (accord.includes(token)) applyDelta(fp, delta)
  }

  // Season suitability nudges freshness vs. warmth
  const season = (p.suit_season ?? '').toLowerCase()
  if (season.includes('summer')) applyDelta(fp, { freshness: +1, intensity: -1 })
  if (season.includes('spring')) applyDelta(fp, { florality: +1, freshness: +1 })
  if (season.includes('fall') || season.includes('autumn')) applyDelta(fp, { warmth: +1 })
  if (season.includes('winter')) applyDelta(fp, { warmth: +2, intensity: +1 })

  // Time of day nudges intensity / darkness
  const time = (p.suit_time ?? '').toLowerCase()
  if (time.includes('day')) applyDelta(fp, { intensity: -1, freshness: +1 })
  if (time.includes('night')) applyDelta(fp, { intensity: +2, darkness: +1 })

  // If the catalog has explicit percentages, let them refine warmth/freshness
  // around the season already coded. Treat percent as a 0..100 vote.
  if (typeof p.percent_summer === 'number' && p.percent_summer >= 70) {
    applyDelta(fp, { freshness: +1 })
  }
  if (typeof p.percent_winter === 'number' && p.percent_winter >= 70) {
    applyDelta(fp, { warmth: +1 })
  }
  if (typeof p.percent_night === 'number' && p.percent_night >= 75) {
    applyDelta(fp, { intensity: +1, darkness: +1 })
  }

  return fp
}

// ────────────────────────────────────────────────────────────────
//   Recommendation
// ────────────────────────────────────────────────────────────────

export function targetFingerprint(mood: Mood, weather: Weather, occasion: Occasion): Fingerprint {
  const fp = emptyFp()
  applyDelta(fp, MOOD_DELTA[mood])
  applyDelta(fp, WEATHER_DELTA[weather])
  applyDelta(fp, OCCASION_DELTA[occasion])
  return fp
}

export function distance(a: Fingerprint, b: Fingerprint): number {
  let sum = 0
  for (const k of Object.keys(a) as Array<keyof Fingerprint>) {
    const d = a[k] - b[k]
    sum += d * d
  }
  return Math.sqrt(sum)
}

// Derive + score a single catalog entry against a target fingerprint.
// Used when matching wardrobe items (each item carries a catalog_id we resolve).
export function scoreCatalogEntry(
  p: CatalogPerfume,
  target: Fingerprint,
): { fingerprint: Fingerprint; score: number } {
  const fingerprint = derivePerfumeFingerprint(p)
  return { fingerprint, score: 1 / (1 + distance(fingerprint, target)) }
}

export type Match = {
  perfume: CatalogPerfume
  score: number       // 0..1, higher is better
  fingerprint: Fingerprint
}

export function recommend(
  catalog: CatalogPerfume[],
  target: Fingerprint,
  limit = 3,
): Match[] {
  return catalog
    .map((perfume) => {
      const fingerprint = derivePerfumeFingerprint(perfume)
      const score = 1 / (1 + distance(fingerprint, target))
      return { perfume, fingerprint, score }
    })
    .sort((a, b) => b.score - a.score)
    .slice(0, limit)
}

// ────────────────────────────────────────────────────────────────
//   Display helpers
// ────────────────────────────────────────────────────────────────

// One-line "why this matches" copy — pulls from the dominant axis of the target
// so it reads as editorial rather than mechanical.
export function whyMatches(target: Fingerprint, match: Fingerprint): string {
  const dims: Array<{ key: keyof Fingerprint; label: string; aligned: boolean }> = [
    { key: 'freshness',  label: 'fresh',    aligned: false },
    { key: 'warmth',     label: 'warm',     aligned: false },
    { key: 'sweetness',  label: 'sweet',    aligned: false },
    { key: 'florality',  label: 'floral',   aligned: false },
    { key: 'darkness',   label: 'dark',     aligned: false },
    { key: 'intensity',  label: 'intense',  aligned: false },
  ]
  for (const d of dims) {
    d.aligned = Math.abs(target[d.key] - match[d.key]) <= 1 && target[d.key] >= 6
  }
  const matched = dims.filter((d) => d.aligned).map((d) => d.label)
  if (matched.length === 0) return 'A quiet match for the mix you chose.'
  if (matched.length === 1) return `Matches the ${matched[0]} you asked for.`
  return `Matches the ${matched.slice(0, -1).join(', ')} and ${matched[matched.length - 1]} of your pick.`
}
