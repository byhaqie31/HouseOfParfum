/* Shared vocabulary + small helpers for the wear/log flow and journal display.
   Per-wear fields (mood/occasion/weather/longevity) live on journal_entries.
   Display strings are sentence case, no em dashes (handoff copy rules). */

import type { ScentFamilyKey } from './scent'
import type { Longevity } from '~/stores/journal'

export const MOODS = ['Confident', 'Calm', 'Playful', 'Romantic', 'Focused', 'Cosy'] as const
export const OCCASIONS = ['Everyday', 'Work', 'Date', 'Evening out', 'Travel', 'At home'] as const
export const WEATHERS = ['Humid day', 'After rain', 'Cool evening', 'Hot noon'] as const

export const LONGEVITY: { value: Longevity; label: string; hint: string }[] = [
  { value: 'brief', label: 'Brief', hint: 'Faded in an hour or two.' },
  { value: 'settled', label: 'Settled', hint: 'Held through the afternoon.' },
  { value: 'lasting', label: 'Lasting', hint: 'Carried most of the day.' },
  { value: 'all-day', label: 'All day', hint: 'Still there at dinner.' },
  { value: 'into-night', label: 'Into the night', hint: 'Found it on the pillow.' },
]

export function longevityLabel(value?: string | null): string {
  return LONGEVITY.find((l) => l.value === value)?.label ?? ''
}

/** Time-of-day greeting word: < 12 morning, < 18 afternoon, else evening. */
export function greetingWord(d = new Date()): 'morning' | 'afternoon' | 'evening' {
  const h = d.getHours()
  if (h < 12) return 'morning'
  if (h < 18) return 'afternoon'
  return 'evening'
}

/** The scent family that suits this part of the day — the pick "has a reason". */
export function familyOfTheHour(d = new Date()): ScentFamilyKey {
  const h = d.getHours()
  if (h < 11) return 'citrus' // bright morning
  if (h < 16) return 'aquatic' // fresh midday
  if (h < 20) return 'floral' // warm dusk
  return 'woody' // resinous night
}

/**
 * Choose today's pick from the shelf: prefer a bottle whose family suits the
 * hour; fall back to the most-worn, then the first bottle. Keeps the pick
 * meaningful rather than random.
 */
export function pickOfTheDay<T extends { family?: ScentFamilyKey | null; id: string }>(
  items: T[],
  wearCount: (id: string) => number,
  now = new Date(),
): T | null {
  if (!items.length) return null
  const want = familyOfTheHour(now)
  const matches = items.filter((i) => i.family === want)
  const pool = matches.length ? matches : items
  return [...pool].sort((a, b) => wearCount(b.id) - wearCount(a.id))[0] ?? items[0]!
}
