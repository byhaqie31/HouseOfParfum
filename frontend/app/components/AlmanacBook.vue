<template>
  <div class="almanac-book-wrap">
    <!-- Top-left nav links (hidden on the front cover) -->
    <div class="book-toolbar">
      <button
        type="button"
        class="book-btn book-btn-nav book-btn-icon"
        :class="{ 'is-shown': currentPage > 0 }"
        aria-label="Back to cover"
        @click="goToCover"
      >
        <Icon name="lucide:home" size="14" />
      </button>
      <span
        class="book-toolbar-divider fm"
        :class="{ 'is-shown': currentPage > 0 }"
        :style="{ color: world.accent }"
        aria-hidden="true"
      >/</span>
      <button
        type="button"
        class="book-btn book-btn-nav fm"
        :class="{ 'is-shown': currentPage > 0 }"
        :disabled="currentPage === TOC_PAGE"
        @click="goToTOC"
      >
        Contents
      </button>
    </div>

    <div
      ref="bookEl"
      class="almanac-book"
      :class="{
        'is-spread': isSpread,
        'is-cover-front': coverState === 'front',
        'is-cover-back': coverState === 'back',
      }"
    >
      <!-- Front cover (hard) -->
      <div
        class="page page-cover page-cover-front"
        data-density="hard"
        >
        <div class="cover-inner">
          <div class="cover-frame">
            <p class="fm text-[10px] uppercase tracking-[0.32em] cover-eyebrow">
              A reference volume
            </p>
            <h1 class="mt-10 fd text-5xl leading-[1.05] cover-title">
              The<br><em class="cover-title-em">Almanac.</em>
            </h1>
            <p class="mt-10 fd italic text-[15px] leading-[1.6] max-w-[28ch] cover-tagline">
              A field guide to the perfumed world.
            </p>
            <p class="mt-auto fm text-[9px] uppercase tracking-[0.28em] cover-imprint">
              House of Parfum
            </p>
          </div>
        </div>
      </div>

      <!-- Inside front cover -->
      <div class="page page-blank" data-density="hard">
        <div class="page-inner justify-center items-center text-center">
          <p class="fd italic text-[15px] leading-[1.7] max-w-[32ch]" style="color: var(--color-ink-soft);">
            What perfume is, how it is made, why it lives so differently
            on one wearer than on another.
          </p>
          <div class="mt-6 h-px w-12 rounded-full" :style="{ background: world.gradient }" />
        </div>
      </div>

      <!-- Title spread (left) -->
      <div class="page">
        <div class="page-inner justify-center items-center text-center">
          <p class="fm text-[9px] uppercase tracking-[0.32em]" style="color: var(--color-ink-mute);">
            Volume One
          </p>
          <p class="mt-4 fd italic text-[14px]" style="color: var(--color-ink-soft);">
            of the perfumed world
          </p>
        </div>
        <div class="page-folio fm">i</div>
      </div>

      <!-- TOC (right) -->
      <div class="page">
        <div class="page-inner">
          <p class="fm text-[10px] uppercase tracking-[0.24em] mb-4" style="color: var(--color-ink-mute);">
            <span class="mr-2" :style="{ color: world.accent }">/</span>Contents
          </p>
          <ol class="border-t" style="border-color: var(--color-rule);">
            <li
              v-for="ch in chapters"
              :key="ch.id"
              class="border-b"
              style="border-color: var(--color-rule);"
            >
              <button
                type="button"
                class="toc-link group flex items-baseline gap-3 py-2 w-full text-left transition-colors"
                @mousedown.stop
                @click.stop="goToChapter(ch.id)"
              >
                <span class="fm text-[10px] uppercase tracking-[0.18em] w-6 shrink-0" :style="{ color: world.accent }">
                  {{ ch.number }}
                </span>
                <span class="fd text-[14px] flex-1 leading-tight transition-colors toc-title" style="color: var(--color-ink);">
                  {{ ch.title }}
                </span>
                <span class="fm text-[9px] uppercase tracking-[0.14em] shrink-0" style="color: var(--color-ink-mute);">
                  {{ String(chapterPage.get(ch.id) ?? 0).padStart(3, '0') }}
                </span>
              </button>
            </li>
          </ol>
        </div>
        <div class="page-folio fm">ii</div>
      </div>

      <!-- Chapters -->
      <template v-for="ch in chapters" :key="ch.id">
        <!-- Chapter opener -->
        <div class="page">
          <div class="page-inner justify-center">
            <div class="w-16 h-px rounded-full mb-8" :style="{ background: world.gradient }" />
            <p class="fm text-[10px] uppercase tracking-[0.24em]" :style="{ color: world.accent }">
              Chapter {{ ch.number }}
            </p>
            <h2 class="mt-3 fd text-3xl leading-[1.05]" style="color: var(--color-ink);">
              {{ ch.title }}
            </h2>
            <p class="mt-6 fd italic text-[14px] leading-[1.6]" style="color: var(--color-ink-soft);">
              {{ ch.intro }}
            </p>
          </div>
          <div class="page-folio fm">{{ ch.title }}</div>
        </div>

        <!-- Entries (1 per page) -->
        <div
          v-for="(entry, i) in ch.entries"
          :key="`${ch.id}-${i}`"
          class="page"
        >
          <div class="page-inner">
            <p class="fm text-[9px] uppercase tracking-[0.18em]" :style="{ color: world.accent }">
              {{ ch.number }} · {{ String(i + 1).padStart(2, '0') }}
            </p>
            <h3 class="mt-3 fd text-[18px] leading-tight" style="color: var(--color-ink);">
              {{ entry.q }}
            </h3>
            <div class="mt-3 w-8 h-px rounded-full" style="background: var(--color-rule);" />
            <p class="fb mt-4 text-[13px] leading-[1.7]" style="color: var(--color-ink-soft);">
              {{ entry.a }}
            </p>
          </div>
          <div class="page-folio fm">{{ ch.title }}</div>
        </div>
      </template>

      <!-- Colophon -->
      <div class="page">
        <div class="page-inner justify-center items-center text-center">
          <div class="w-12 h-px rounded-full mb-8" :style="{ background: world.gradient }" />
          <p class="fm text-[10px] uppercase tracking-[0.24em] mb-6" style="color: var(--color-ink-mute);">
            Colophon
          </p>
          <p class="fd italic text-[14px] leading-[1.6] max-w-[30ch]" style="color: var(--color-ink-soft);">
            The Almanac grows with the shelf. Entries are added as the questions arrive.
          </p>
        </div>
      </div>

      <!-- Inside back cover -->
      <div class="page page-blank" data-density="hard">
        <div class="page-inner" />
      </div>

      <!-- Back cover (hard) -->
      <div
        class="page page-cover page-cover-back"
        data-density="hard"
        >
        <div class="cover-inner">
          <div class="cover-frame cover-frame-back">
            <p class="fm text-[10px] uppercase tracking-[0.32em] cover-eyebrow">
              The End
            </p>
            <h1 class="mt-auto fd text-5xl leading-[1.05] cover-title">
              The<br><em class="cover-title-em">Almanac.</em>
            </h1>
            <div class="mt-auto pt-10">
              <p class="fd italic text-[13px] cover-tagline">
                Author &middot; Baihaqie Yusri
              </p>
              <p class="mt-3 fm text-[9px] uppercase tracking-[0.28em] cover-imprint">
                &copy; {{ new Date().getFullYear() }} &middot; House of Parfum
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="book-controls">
      <button
        type="button"
        class="book-btn book-btn-turn fm"
        :style="{ '--turn-accent': world.accent }"
        @click="prev"
      >
        &larr; Previous
      </button>
      <span class="book-counter fm">
        {{ currentPage + 1 }} / {{ totalPages || '…' }}
      </span>
      <button
        type="button"
        class="book-btn book-btn-turn fm"
        :style="{ '--turn-accent': world.accent }"
        @click="next"
      >
        Next &rarr;
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onBeforeUnmount, ref, nextTick } from 'vue'
import type { FaqChapter } from '~/data/perfume-faq'
import { familyOfTheHour } from '~/utils/wear'

const props = defineProps<{ chapters: FaqChapter[] }>()
const chapters = computed(() => props.chapters)

// Tint interactive / active chrome with the day's scent accent.
const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())
// The Almanac cover has its own fixed rose-pink identity (set in scoped CSS,
// see .page-cover); inner pages still use `world` accents.
const bookEl = ref<HTMLElement | null>(null)
const currentPage = ref(0)
const totalPages = ref(0)
const isPortrait = ref(false)
let pageFlip: any = null

// First chapter opens at page 4: cover(0) → inside(1) → half-title(2) → TOC(3) → ch1 opener(4)
const FRONT_MATTER_PAGES = 4
const TOC_PAGE = 3

const chapterPage = computed(() => {
  const map = new Map<string, number>()
  let idx = FRONT_MATTER_PAGES
  for (const ch of chapters.value) {
    map.set(ch.id, idx)
    idx += 1 + ch.entries.length
  }
  return map
})

// True only when a real two-page spread is visible:
//  - book has loaded
//  - not on either hard cover
//  - landscape mode (portrait shows one page at a time, so no spine to draw)
const isSpread = computed(() => {
  if (!totalPages.value) return false
  if (isPortrait.value) return false
  return currentPage.value > 0 && currentPage.value < totalPages.value - 1
})

// 'front' / 'back' when only a single cover is showing; null on spreads.
// Drives the centering transform on the book (cover by default sits in one half).
// Returns null in portrait mode — there StPageFlip already shows a single page that
// fills the canvas, so the translate is not needed and in fact pushes it off-centre.
const coverState = computed<'front' | 'back' | null>(() => {
  if (isPortrait.value) return null
  if (currentPage.value === 0) return 'front'
  if (totalPages.value > 0 && currentPage.value === totalPages.value - 1) return 'back'
  return null
})

function next() {
  pageFlip?.flipNext()
}
function prev() {
  pageFlip?.flipPrev()
}
function goToChapter(id: string) {
  const target = chapterPage.value.get(id)
  if (target == null || !pageFlip) return
  pageFlip.flip(target, 'top')
}
function goToTOC() {
  pageFlip?.flip(TOC_PAGE, 'top')
}
function goToCover() {
  pageFlip?.flip(0, 'top')
}

onMounted(async () => {
  // page-flip ships no .d.ts
  // @ts-expect-error
  const { PageFlip } = await import('page-flip')
  await nextTick()
  if (!bookEl.value) return

  pageFlip = new PageFlip(bookEl.value, {
    width: 520,
    height: 720,
    size: 'stretch',
    minWidth: 320,
    maxWidth: 640,
    minHeight: 480,
    maxHeight: 880,
    showCover: true,
    maxShadowOpacity: 0.4,
    mobileScrollSupport: false,
    useMouseEvents: true,
    drawShadow: true,
    flippingTime: 700,
  })

  const pages = bookEl.value.querySelectorAll('.page')
  pageFlip.loadFromHTML(pages)
  totalPages.value = pageFlip.getPageCount()
  isPortrait.value = pageFlip.getOrientation?.() === 'portrait'

  pageFlip.on('flip', (e: any) => {
    currentPage.value = e.data
  })
  pageFlip.on('changeOrientation', (e: any) => {
    isPortrait.value = e.data === 'portrait'
  })
})

onBeforeUnmount(() => {
  pageFlip?.destroy?.()
})
</script>

<style scoped>
/* ───────────  Book frame  ─────────── */
.almanac-book-wrap {
  width: 100%;
  padding: 20px 0 8px;
  position: relative;
}

/* Top-left toolbar (Home / Contents) */
.book-toolbar {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 12px;
  margin-bottom: 18px;
  min-height: 22px; /* reserve space so layout doesn't jump when buttons appear */
}

.almanac-book {
  margin: 0 auto;
  position: relative;
  overflow: hidden; /* clip mid-flip pages so they don't render below the book */
  /* Lifts the whole card surface off the canvas with soft, neutral depth. */
  filter:
    drop-shadow(0 22px 34px oklch(0.18 0.014 285 / 0.16))
    drop-shadow(0 6px 12px oklch(0.18 0.014 285 / 0.10));
  /* Cover centering: book bbox holds two pages; a single cover sits in one half,
     so we shift the whole book to bring that half to the centre of the wrap. */
  transition: transform 0.5s ease;
}
.almanac-book.is-cover-front {
  transform: translateX(-25%);
}
.almanac-book.is-cover-back {
  transform: translateX(25%);
}

/* Vertical seam between the two open pages — only visible on a spread */
.almanac-book::after {
  content: '';
  position: absolute;
  top: 6%;
  bottom: 6%;
  left: 50%;
  width: 1px;
  background: linear-gradient(
    to bottom,
    transparent 0%,
    color-mix(in oklab, var(--color-rule) 60%, transparent) 12%,
    color-mix(in oklab, var(--color-ink-mute) 55%, transparent) 50%,
    color-mix(in oklab, var(--color-rule) 60%, transparent) 88%,
    transparent 100%
  );
  transform: translateX(-0.5px);
  pointer-events: none;
  z-index: 50;
  opacity: 0;
  transition: opacity 0.35s ease;
}
.almanac-book.is-spread::after {
  opacity: 1;
}

/* ───────────  Inside pages  ─────────── */
.almanac-book :deep(.page) {
  /* Clean card surface — flips light/after-dark with the token. */
  background-color: var(--color-surface);
  color: var(--color-ink);
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-hero);
  overflow: hidden;
  position: relative;
}

/* Subtle gutter shadow on the inner edge of each page */
.almanac-book :deep(.stf__item.--left .page)::after,
.almanac-book :deep(.stf__item.--right .page)::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 28px;
  pointer-events: none;
  z-index: 1;
}
.almanac-book :deep(.stf__item.--left .page)::after {
  right: 0;
  background: linear-gradient(to left, color-mix(in oklab, var(--color-ink) 7%, transparent), transparent);
}
.almanac-book :deep(.stf__item.--right .page)::after {
  left: 0;
  background: linear-gradient(to right, color-mix(in oklab, var(--color-ink) 7%, transparent), transparent);
}

.almanac-book :deep(.page-inner),
.almanac-book :deep(.cover-inner) {
  position: relative;
  z-index: 2; /* keep content above grain + gutter */
}

.almanac-book :deep(.page-inner) {
  display: flex;
  flex-direction: column;
  padding: 56px 44px 72px;
  height: 100%;
}

.almanac-book :deep(.cover-inner) {
  display: flex;
  flex-direction: column;
  padding: 0;
  height: 100%;
}

.almanac-book :deep(.page-folio) {
  position: absolute;
  bottom: 28px;
  left: 0;
  right: 0;
  text-align: center;
  font-size: 9px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--color-ink-mute);
  z-index: 2;
}

/* ───────────  Covers (scent-gradient surface; bloom set inline)  ─────────── */
.almanac-book :deep(.page-cover) {
  /* Fixed rose-pink cover — identical in both canvas modes, white text. Set here
     with !important because the base `.page` background-color and the flip
     library otherwise win over an inline background on the same element. */
  --cover-ink: oklch(0.99 0.006 350);
  background:
    radial-gradient(120% 90% at 22% 10%, oklch(0.92 0.09 350 / 0.55) 0%, transparent 55%),
    linear-gradient(150deg, oklch(0.80 0.15 350) 0%, oklch(0.61 0.17 342) 100%) !important;
  color: var(--cover-ink);
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-hero);
}

/* Translucent inset frame, replacing the foil binding */
.almanac-book :deep(.cover-frame) {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
  margin: 18px;
  padding: 44px 32px;
  border: 1px solid color-mix(in oklab, var(--cover-ink) 26%, transparent);
  border-radius: var(--radius-panel);
}
.almanac-book :deep(.cover-frame-back) {
  align-items: center;
  text-align: center;
}

/* Cover typography — tones derived from --cover-ink (world.onGrad) so they read
   on the scent gradient in both canvas modes. */
.almanac-book :deep(.cover-eyebrow) {
  color: color-mix(in oklab, var(--cover-ink) 80%, transparent);
}
.almanac-book :deep(.cover-title) {
  color: var(--cover-ink);
}
.almanac-book :deep(.cover-title-em) {
  color: color-mix(in oklab, var(--cover-ink) 92%, transparent);
  font-style: italic;
}
.almanac-book :deep(.cover-tagline) {
  color: color-mix(in oklab, var(--cover-ink) 84%, transparent);
}
.almanac-book :deep(.cover-imprint) {
  color: color-mix(in oklab, var(--cover-ink) 70%, transparent);
}

/* ───────────  TOC interactivity  ─────────── */
.almanac-book :deep(.toc-link) {
  cursor: pointer;
  padding-left: 8px;
  padding-right: 8px;
  border-radius: var(--radius-field);
  transition: background-color 0.15s ease;
}
.almanac-book :deep(.toc-link:hover) {
  background: var(--color-surface-2);
}
.almanac-book :deep(.toc-title) {
  transition: color 0.15s ease;
}
.almanac-book :deep(.toc-link:hover .toc-title) {
  color: var(--color-accent-deep);
}

/* ───────────  Pagination strip under the book  ─────────── */
.book-controls {
  margin-top: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 24px;
}

/* Plain text-link style, shared by Home (icon) + Contents + pagination */
.book-btn {
  background: transparent;
  border: none;
  color: var(--color-ink);
  padding: 4px 6px;
  border-radius: var(--radius-chip);
  font-size: 10px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: color 0.15s ease, background-color 0.15s ease, opacity 0.35s ease;
}
.book-btn-icon {
  padding: 4px;
}
.book-btn:hover:not(:disabled) {
  color: var(--color-accent-deep);
}
.book-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* Page-turn arrows pick up the day's scent accent on hover (accent-soft pill). */
.book-btn-turn:hover:not(:disabled) {
  color: var(--turn-accent, var(--color-accent-deep));
  background: var(--color-accent-soft);
}

/* Slash divider between Home and Contents — tinted inline with the scent accent */
.book-toolbar-divider {
  font-size: 12px;
  line-height: 1;
  opacity: 0;
  transition: opacity 0.35s ease;
  user-select: none;
}
.book-toolbar-divider.is-shown {
  opacity: 1;
}

/* Nav triggers: hidden on the front cover, faded in once you've opened the book */
.book-btn-nav {
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.35s ease, color 0.15s ease, background-color 0.15s ease;
}
.book-btn-nav.is-shown {
  opacity: 1;
  pointer-events: auto;
}


.book-counter {
  color: var(--color-ink-mute);
  min-width: 70px;
  text-align: center;
  font-size: 10px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
}
</style>
