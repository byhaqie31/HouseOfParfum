<template>
  <div class="min-h-screen pt-20 pb-24 px-6">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <header class="border-b border-rule pb-8">
        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-mute">
          Your wear log
        </p>
        <h1 class="mt-3 font-display text-5xl sm:text-6xl text-ink tracking-tight leading-[1.05]">
          Journal
          <span class="font-mono text-[14px] uppercase tracking-[0.16em] text-ink-mute align-middle ml-3">
            {{ String(journal.count).padStart(2, '0') }}
          </span>
        </h1>
      </header>

      <!-- Populated -->
      <div v-if="journal.count > 0" class="mt-12 space-y-14">
        <section v-for="group in groups" :key="group.key">
          <p class="font-display font-medium text-[11px] uppercase tracking-[0.28em] text-accent-deep mb-6">
            {{ group.label }}
          </p>
          <ul class="border-t border-rule">
            <li
              v-for="entry in group.entries"
              :key="entry.id"
              class="border-b border-rule py-6 grid grid-cols-[88px_1fr] sm:grid-cols-[140px_1fr] gap-6"
            >
              <div>
                <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-mute">
                  {{ entry.dayName }}
                </p>
                <p class="font-mono text-[28px] sm:text-[32px] text-ink leading-none mt-1">
                  {{ entry.dayNum }}
                </p>
                <p class="font-mono text-[9px] uppercase tracking-[0.16em] text-ink-mute mt-1">
                  {{ entry.timeLabel }}
                </p>
              </div>
              <div>
                <p class="font-display italic text-[14px] text-ink-soft">
                  {{ entry.brand }}
                </p>
                <h3 class="font-display text-2xl text-ink tracking-tight leading-tight">
                  {{ entry.name }}
                </h3>
                <p
                  v-if="entry.notes"
                  class="mt-3 font-display italic text-[14px] text-ink-soft leading-normal max-w-prose"
                >
                  {{ entry.notes }}
                </p>
              </div>
            </li>
          </ul>
        </section>
      </div>

      <!-- Empty -->
      <section v-else class="mt-24 sm:mt-32 max-w-xl mx-auto text-center">
        <div class="w-60 h-60 rounded-full border border-rule bg-paper-deep flex items-center justify-center mx-auto">
          <Icon name="lucide:book-open" size="100" class="text-ink-mute" />
        </div>
        <h2 class="mt-12 font-display text-3xl sm:text-4xl text-ink tracking-tight leading-[1.1]">
          Nothing in the journal yet.
        </h2>
        <p class="mt-4 text-base text-ink-soft leading-relaxed">
          Log a wear from today's pick and it'll appear here.
        </p>
        <NuxtLink
          to="/today"
          class="mt-12 inline-flex items-center gap-2 bg-ink text-paper text-xs uppercase tracking-[0.2em] px-8 py-3.5 hover:bg-ink-soft transition-colors"
        >
          Go to today
          <Icon name="lucide:arrow-right" size="14" />
        </NuxtLink>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

import type { JournalEntry } from '~/stores/journal'

const journal = useJournalStore()

type Group = {
  key: string
  label: string
  entries: Array<JournalEntry & { dayName: string; dayNum: string; timeLabel: string }>
}

const groups = computed<Group[]>(() => {
  const buckets = new Map<string, Group>()
  for (const entry of journal.sorted) {
    const d = new Date(entry.worn_at)
    const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`
    const label = d
      .toLocaleDateString('en-GB', { month: 'long', year: 'numeric' })
      .toUpperCase()

    const decorated = {
      ...entry,
      dayName: d
        .toLocaleDateString('en-GB', { weekday: 'short' })
        .toUpperCase(),
      dayNum: String(d.getDate()).padStart(2, '0'),
      timeLabel: d.toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
      }),
    }

    if (!buckets.has(key)) {
      buckets.set(key, { key, label, entries: [decorated] })
    } else {
      buckets.get(key)!.entries.push(decorated)
    }
  }
  return Array.from(buckets.values())
})
</script>
