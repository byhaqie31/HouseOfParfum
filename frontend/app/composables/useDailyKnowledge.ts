import { PERFUMERY_KNOWLEDGE, type KnowledgeEntry } from '~/data/perfumery-knowledge'

// Date-seeded so the same entry shows for everyone for the whole calendar day,
// rotating at midnight local time. Uses a small deterministic hash so adjacent
// days don't sit at adjacent indices in the array.
const hashYMD = (y: number, m: number, d: number): number => {
  let h = (y * 1000 + m * 50 + d) >>> 0
  h = ((h ^ (h >>> 16)) * 0x85ebca6b) >>> 0
  h = ((h ^ (h >>> 13)) * 0xc2b2ae35) >>> 0
  h = (h ^ (h >>> 16)) >>> 0
  return h
}

export const useDailyKnowledge = (date: Date = new Date()): KnowledgeEntry => {
  const idx = hashYMD(date.getFullYear(), date.getMonth() + 1, date.getDate())
    % PERFUMERY_KNOWLEDGE.length
  return PERFUMERY_KNOWLEDGE[idx]!
}
