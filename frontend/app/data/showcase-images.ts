/**
 * PLACEHOLDER ART for the /about showcase marquee.
 *
 * The discovery catalogue (~24k rows) ships no product photography, so the
 * landing-page showcase leans on a small pool of Unsplash bottle shots until
 * real imagery lands. Photos do NOT depict the perfume they're paired with —
 * assignment is deterministic (`id % pool.length`) purely so a card keeps
 * the same photo across visits and SSR/client renders match.
 *
 * Swap the pool for real imagery later; keep `showcaseImageFor`'s signature.
 * Note: source.unsplash.com is dead — only direct images.unsplash.com URLs.
 */
const POOL = [
  'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1594035910387-fea47794261f?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1615634260167-c8cdede054de?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1619994403073-2cec844b8e63?q=80&w=640&auto=format&fit=crop',
  'https://images.unsplash.com/photo-1563170351-be82bc888aa4?q=80&w=640&auto=format&fit=crop',
]

export function showcaseImageFor(id: number): string {
  return POOL[Math.abs(id) % POOL.length]!
}
