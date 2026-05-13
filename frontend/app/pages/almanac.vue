<template>
  <div id="top" class="min-h-screen pt-28 pb-32 px-6">
    <div class="max-w-3xl mx-auto">
      <!-- ─────────────────────────  Cover page  ───────────────────────── -->
      <header class="text-center border-b border-ink pb-16 relative">
        <div class="absolute -top-px left-1/2 -translate-x-1/2 w-24 h-px bg-accent" />

        <p class="font-mono text-[10px] uppercase tracking-[0.32em] text-ink-mute">
          A reference volume
        </p>
        <p class="mt-8 font-display font-medium text-[12px] uppercase tracking-[0.32em] text-accent-deep">
          The Almanac
        </p>
        <h1 class="mt-6 font-display text-5xl sm:text-6xl text-ink tracking-[-0.005em] leading-[1.05]">
          A field guide to <br><em class="text-ink-soft">the perfumed world.</em>
        </h1>
        <p class="mt-10 font-display italic text-[17px] text-ink-soft leading-[1.65] max-w-xl mx-auto">
          What perfume is, how it is made, why it lives so differently on one wearer
          than on another. A small library to thumb through whenever the bottle in your hand
          becomes an open question.
        </p>
        <p class="mt-12 font-mono text-[10px] uppercase tracking-[0.22em] text-ink-mute">
          {{ chapters.length }} chapters
          <span class="text-accent-deep mx-2">·</span>
          {{ entryTotal }} entries
        </p>
      </header>

      <!-- ─────────────────────────  Table of contents  ───────────────────────── -->
      <section class="mt-24">
        <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute mb-8">
          <span class="text-accent-deep mr-2">/</span>Contents
        </p>
        <ol class="border-t border-rule">
          <li
            v-for="ch in chapters"
            :key="ch.id"
            class="border-b border-rule"
          >
            <a
              :href="`#${ch.id}`"
              class="group flex items-baseline gap-6 py-5 hover:bg-paper-deep transition-colors px-2 -mx-2"
            >
              <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-accent-deep w-8 shrink-0">
                {{ ch.number }}
              </span>
              <span class="font-display text-[19px] sm:text-[21px] text-ink group-hover:text-accent-deep tracking-[-0.005em] flex-1 transition-colors">
                {{ ch.title }}
              </span>
              <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-mute shrink-0 hidden sm:inline">
                {{ ch.entries.length }} entries
              </span>
            </a>
          </li>
        </ol>
      </section>

      <!-- ─────────────────────────  Chapters  ───────────────────────── -->
      <section
        v-for="ch in chapters"
        :id="ch.id"
        :key="ch.id"
        class="mt-28 sm:mt-32 scroll-mt-24"
      >
        <!-- Chapter opener -->
        <header class="relative pt-10 border-t border-ink">
          <div class="absolute -top-px left-0 w-20 h-px bg-accent" />

          <p class="font-mono text-[11px] uppercase tracking-[0.24em] text-accent-deep">
            Chapter {{ ch.number }}
          </p>
          <h2 class="mt-3 font-display text-4xl sm:text-5xl text-ink tracking-[-0.005em] leading-[1.05]">
            {{ ch.title }}
          </h2>
          <p class="mt-6 font-display italic text-[17px] text-ink-soft leading-[1.6] max-w-prose">
            {{ ch.intro }}
          </p>
        </header>

        <!-- Q&A entries -->
        <ol class="mt-12 space-y-12">
          <li
            v-for="(entry, i) in ch.entries"
            :key="`${ch.id}-${i}`"
            class="grid grid-cols-[auto_1fr] gap-x-5 sm:gap-x-7"
          >
            <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-accent-deep pt-1.5">
              {{ String(i + 1).padStart(2, '0') }}
            </span>
            <div>
              <h3 class="font-display text-[20px] sm:text-[22px] text-ink leading-[1.25] tracking-[-0.005em]">
                {{ entry.q }}
              </h3>
              <p class="mt-3 text-[15px] text-ink-soft leading-[1.7] max-w-prose">
                {{ entry.a }}
              </p>
            </div>
          </li>
        </ol>
      </section>

      <!-- ─────────────────────────  Colophon  ───────────────────────── -->
      <footer class="mt-32 pt-12 border-t border-ink relative text-center">
        <div class="absolute -top-px right-0 w-16 h-px bg-accent" />

        <p class="font-display italic text-[16px] text-ink-soft leading-[1.6] max-w-prose mx-auto">
          The Almanac grows with the shelf. Entries are added as the questions arrive.
        </p>
        <a
          href="#top"
          class="mt-8 inline-block font-display italic text-[14px] text-ink hover:text-accent-deep pb-1 border-b border-accent transition-colors"
        >
          Back to the beginning &uarr;
        </a>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
import { PERFUME_FAQ, PERFUME_FAQ_ENTRY_COUNT } from '~/data/perfume-faq'

useHead({
  title: 'The Almanac — House of Parfum',
  meta: [
    {
      name: 'description',
      content: 'A field guide to perfume: how it is made, how it wears, how to choose. A reference volume from House of Parfum.',
    },
  ],
})

const chapters = PERFUME_FAQ
const entryTotal = PERFUME_FAQ_ENTRY_COUNT
</script>
