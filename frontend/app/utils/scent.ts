/* ───────────────────────────────────────────────────────────────────────
   HOUSE OF PARFUM — scent-driven colour system
   Every fragrance generates its own colour world from its scent family.
   OKLCH tokens, two canvas modes (light / dark). Ported verbatim from the
   design handoff's prototypes/scent-system.js — this is the heart of the
   visual system; everything colourful flows from scentWorld().
   ─────────────────────────────────────────────────────────────────────── */

export type ScentFamilyKey =
  | 'floral'
  | 'woody'
  | 'aquatic'
  | 'spicy'
  | 'powdery'
  | 'citrus'
  | 'gourmand'

export interface ScentFamily {
  key: ScentFamilyKey
  label: string
  my: string
  hue: [number, number]
  lc: number
  dc: number
}

export interface ScentWorld {
  c1: string
  c2: string
  accent: string
  soft: string
  gradient: string
  bloom: string
  onGrad: string
  h1: number
  h2: number
  C: number
  family: ScentFamily
}

// Scent families → hue band [primary, secondary] + chroma per canvas mode.
// The band gives each fragrance a *living* gradient; a per-bottle seed nudges
// the hue within the band so two florals never look identical.
export const FAMILIES: Record<ScentFamilyKey, ScentFamily> = {
  floral: { key: 'floral', label: 'Floral', my: 'Bunga', hue: [350, 328], lc: 0.105, dc: 0.150 },
  woody: { key: 'woody', label: 'Woody & oud', my: 'Kayu', hue: [74, 48], lc: 0.098, dc: 0.145 },
  aquatic: { key: 'aquatic', label: 'Aquatic & fresh', my: 'Laut', hue: [205, 188], lc: 0.092, dc: 0.140 },
  spicy: { key: 'spicy', label: 'Spicy & leather', my: 'Rempah', hue: [38, 20], lc: 0.115, dc: 0.165 },
  powdery: { key: 'powdery', label: 'Powdery & iris', my: 'Serbuk', hue: [308, 288], lc: 0.088, dc: 0.135 },
  citrus: { key: 'citrus', label: 'Citrus', my: 'Sitrus', hue: [118, 96], lc: 0.118, dc: 0.170 },
  gourmand: { key: 'gourmand', label: 'Gourmand', my: 'Manis', hue: [58, 22], lc: 0.108, dc: 0.155 },
}

export const FAMILY_ORDER: ScentFamilyKey[] = [
  'floral', 'woody', 'aquatic', 'spicy', 'powdery', 'citrus', 'gourmand',
]

export function isScentFamily(key: unknown): key is ScentFamilyKey {
  return typeof key === 'string' && key in FAMILIES
}

// Deterministic 0..1 seed from a string (FNV-1a). Seed each bottle once from id+name.
export function hashSeed(str: string): number {
  let h = 2166136261
  for (let i = 0; i < str.length; i++) {
    h ^= str.charCodeAt(i)
    h = Math.imul(h, 16777619)
  }
  return ((h >>> 0) % 1000) / 1000
}

// The heart of the system: a fragrance's full colour world in a given mode.
export function scentWorld(familyKey: string, seed = 0.5, dark = false): ScentWorld {
  const f = FAMILIES[familyKey as ScentFamilyKey] ?? FAMILIES.floral
  const [h1b, h2b] = f.hue
  const shift = (seed - 0.5) * 14 // ±7° within the band → each bottle unique
  const h1 = h1b + shift
  const h2 = h2b + shift
  const C = dark ? f.dc : f.lc

  // Gradient stop lightnesses
  const L1 = dark ? 0.64 : 0.875
  const L2 = dark ? 0.46 : 0.795
  const c1 = `oklch(${L1} ${C.toFixed(3)} ${h1.toFixed(1)})`
  const c2 = `oklch(${L2} ${(C * 1.02).toFixed(3)} ${h2.toFixed(1)})`

  const accent = dark
    ? `oklch(0.78 ${C.toFixed(3)} ${h1.toFixed(1)})`
    : `oklch(0.52 ${(C + 0.02).toFixed(3)} ${h1.toFixed(1)})`
  const soft = dark
    ? `oklch(0.30 ${(C * 0.55).toFixed(3)} ${h1.toFixed(1)})`
    : `oklch(0.945 ${(C * 0.42).toFixed(3)} ${h1.toFixed(1)})`
  const onGrad = dark ? 'oklch(0.99 0.004 90)' : `oklch(0.20 0.03 ${h2.toFixed(1)})`

  const gradient = `linear-gradient(150deg, ${c1} 0%, ${c2} 100%)`
  const bloom = dark
    ? `radial-gradient(120% 90% at 22% 8%, oklch(0.74 ${(C * 1.1).toFixed(3)} ${h1.toFixed(1)} / 0.55) 0%, transparent 60%), ${gradient}`
    : `radial-gradient(120% 95% at 20% 6%, oklch(0.95 ${(C * 0.7).toFixed(3)} ${h1.toFixed(1)} / 0.8) 0%, transparent 55%), ${gradient}`

  return { c1, c2, accent, soft, gradient, bloom, onGrad, h1, h2, C, family: f }
}

// The per-bottle seed: deterministic, so it can be recomputed anywhere.
export function bottleSeed(id: string, name: string): number {
  return hashSeed(`${id}${name}`)
}
