/**
 * Generates a local-time ISO string (no Z / no offset) so the value stored
 * in the DB reflects the device's clock directly — e.g. "2026-05-15T11:56:00".
 * Never use new Date().toISOString() for worn_at: that gives UTC.
 */
export function localTimestamp(): string {
  const now = new Date()
  const pad = (n: number) => String(n).padStart(2, '0')
  return (
    `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`
    + `T${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`
  )
}

/**
 * Parses a timestamp string from the DB or store into a local Date.
 * Strings without a timezone marker are treated as local time (device clock).
 * Strings with Z or ±HH:MM are converted from UTC/offset to local as normal.
 */
export function parseTimestamp(str: string): Date {
  if (!str) return new Date()
  // Already has explicit timezone — let the browser handle it.
  if (/Z$|[+-]\d{2}:\d{2}$/.test(str)) return new Date(str)
  // Local-time string (our format or Laravel "YYYY-MM-DD HH:MM:SS") — normalise
  // the separator so all browsers treat it as local, not UTC.
  return new Date(str.replace(' ', 'T'))
}

/** Formats a timestamp string as device-local "11:56 AM" */
export function formatTime(str: string): string {
  return parseTimestamp(str).toLocaleTimeString([], {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
  })
}

/** Local calendar key "YYYY-MM-DD" for grouping wears by day. */
export function dateKey(d: Date | string): string {
  const date = typeof d === 'string' ? parseTimestamp(d) : d
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`
}

/** Warm relative-day label: "today", "yesterday", "3 days ago", else a short date. */
export function relativeDay(str: string | null | undefined): string {
  if (!str) return 'never'
  const then = parseTimestamp(str)
  const today = new Date()
  const a = new Date(then.getFullYear(), then.getMonth(), then.getDate())
  const b = new Date(today.getFullYear(), today.getMonth(), today.getDate())
  const days = Math.round((b.getTime() - a.getTime()) / 86400000)
  if (days <= 0) return 'today'
  if (days === 1) return 'yesterday'
  if (days < 7) return `${days} days ago`
  if (days < 14) return 'last week'
  if (days < 31) return `${Math.floor(days / 7)} weeks ago`
  return then.toLocaleDateString([], { month: 'short', day: 'numeric' })
}
