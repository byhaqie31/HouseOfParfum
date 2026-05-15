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
        class="book-toolbar-divider"
        :class="{ 'is-shown': currentPage > 0 }"
        aria-hidden="true"
      >/</span>
      <button
        type="button"
        class="book-btn book-btn-nav"
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
      <div class="page page-cover page-cover-front" data-density="hard">
        <div class="cover-inner">
          <div class="cover-frame">
            <p class="font-mono text-[10px] uppercase tracking-[0.32em] cover-eyebrow">
              A reference volume
            </p>
            <h1 class="mt-10 font-display text-5xl tracking-[-0.005em] leading-[1.05] cover-title">
              The<br><em class="cover-title-em">Almanac.</em>
            </h1>
            <p class="mt-10 font-display italic text-[15px] leading-[1.6] max-w-[28ch] cover-tagline">
              A field guide to the perfumed world.
            </p>
            <p class="mt-auto font-mono text-[9px] uppercase tracking-[0.28em] cover-imprint">
              House of Parfum
            </p>
          </div>
        </div>
      </div>

      <!-- Inside front cover -->
      <div class="page page-blank" data-density="hard">
        <div class="page-inner justify-center items-center text-center">
          <p class="font-display italic text-[15px] text-ink-soft leading-[1.7] max-w-[32ch]">
            What perfume is, how it is made, why it lives so differently
            on one wearer than on another.
          </p>
          <div class="mt-6 w-12 h-px bg-accent" />
        </div>
      </div>

      <!-- Title spread (left) -->
      <div class="page">
        <div class="page-inner justify-center items-center text-center">
          <p class="font-mono text-[9px] uppercase tracking-[0.32em] text-ink-mute">
            Volume One
          </p>
          <p class="mt-4 font-display italic text-[14px] text-ink-soft">
            of the perfumed world
          </p>
        </div>
        <div class="page-folio">i</div>
      </div>

      <!-- TOC (right) -->
      <div class="page">
        <div class="page-inner">
          <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute mb-4">
            <span class="text-accent-deep mr-2">/</span>Contents
          </p>
          <ol class="border-t border-rule">
            <li
              v-for="ch in chapters"
              :key="ch.id"
              class="border-b border-rule"
            >
              <button
                type="button"
                class="toc-link group flex items-baseline gap-3 py-2 w-full text-left transition-colors"
                @mousedown.stop
                @click.stop="goToChapter(ch.id)"
              >
                <span class="font-mono text-[10px] uppercase tracking-[0.18em] text-accent-deep w-6 shrink-0">
                  {{ ch.number }}
                </span>
                <span class="font-display text-[14px] text-ink group-hover:text-accent-deep tracking-[-0.005em] flex-1 leading-tight transition-colors">
                  {{ ch.title }}
                </span>
                <span class="font-mono text-[9px] uppercase tracking-[0.14em] text-ink-mute shrink-0">
                  {{ String(chapterPage.get(ch.id) ?? 0).padStart(3, '0') }}
                </span>
              </button>
            </li>
          </ol>
        </div>
        <div class="page-folio">ii</div>
      </div>

      <!-- Chapters -->
      <template v-for="ch in chapters" :key="ch.id">
        <!-- Chapter opener -->
        <div class="page">
          <div class="page-inner justify-center">
            <div class="w-16 h-px bg-accent mb-8" />
            <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-accent-deep">
              Chapter {{ ch.number }}
            </p>
            <h2 class="mt-3 font-display text-3xl text-ink tracking-[-0.005em] leading-[1.05]">
              {{ ch.title }}
            </h2>
            <p class="mt-6 font-display italic text-[14px] text-ink-soft leading-[1.6]">
              {{ ch.intro }}
            </p>
          </div>
          <div class="page-folio">{{ ch.title }}</div>
        </div>

        <!-- Entries (1 per page) -->
        <div
          v-for="(entry, i) in ch.entries"
          :key="`${ch.id}-${i}`"
          class="page"
        >
          <div class="page-inner">
            <p class="font-mono text-[9px] uppercase tracking-[0.18em] text-accent-deep">
              {{ ch.number }} · {{ String(i + 1).padStart(2, '0') }}
            </p>
            <h3 class="mt-3 font-display text-[18px] text-ink leading-tight tracking-[-0.005em]">
              {{ entry.q }}
            </h3>
            <div class="mt-3 w-8 h-px bg-rule" />
            <p class="mt-4 text-[13px] text-ink-soft leading-[1.7]">
              {{ entry.a }}
            </p>
          </div>
          <div class="page-folio">{{ ch.title }}</div>
        </div>
      </template>

      <!-- Colophon -->
      <div class="page">
        <div class="page-inner justify-center items-center text-center">
          <div class="w-12 h-px bg-accent mb-8" />
          <p class="font-mono text-[10px] uppercase tracking-[0.24em] text-ink-mute mb-6">
            Colophon
          </p>
          <p class="font-display italic text-[14px] text-ink-soft leading-[1.6] max-w-[30ch]">
            The Almanac grows with the shelf. Entries are added as the questions arrive.
          </p>
        </div>
      </div>

      <!-- Inside back cover -->
      <div class="page page-blank" data-density="hard">
        <div class="page-inner" />
      </div>

      <!-- Back cover (hard) -->
      <div class="page page-cover page-cover-back" data-density="hard">
        <div class="cover-inner">
          <div class="cover-frame cover-frame-back">
            <p class="font-mono text-[10px] uppercase tracking-[0.32em] cover-eyebrow">
              The End
            </p>
            <h1 class="mt-auto font-display text-5xl tracking-[-0.005em] leading-[1.05] cover-title">
              The<br><em class="cover-title-em">Almanac.</em>
            </h1>
            <div class="mt-auto pt-10">
              <p class="font-display italic text-[13px] cover-tagline">
                Author &middot; Baihaqie Yusri
              </p>
              <p class="mt-3 font-mono text-[9px] uppercase tracking-[0.28em] cover-imprint">
                &copy; {{ new Date().getFullYear() }} &middot; House of Parfum
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <div class="book-controls">
      <button type="button" class="book-btn" @click="prev">
        &larr; Previous
      </button>
      <span class="book-counter">
        {{ currentPage + 1 }} / {{ totalPages || '…' }}
      </span>
      <button type="button" class="book-btn" @click="next">
        Next &rarr;
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, onBeforeUnmount, ref, nextTick } from 'vue'
import type { FaqChapter } from '~/data/perfume-faq'

const props = defineProps<{ chapters: FaqChapter[] }>()
const chapters = computed(() => props.chapters)
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
  /* lifts the whole volume off the page bg */
  filter:
    drop-shadow(0 22px 32px oklch(0.18 0.014 55 / 0.22))
    drop-shadow(0 6px 10px oklch(0.18 0.014 55 / 0.14));
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
    oklch(0.18 0.014 55 / 0.28) 12%,
    oklch(0.18 0.014 55 / 0.42) 50%,
    oklch(0.18 0.014 55 / 0.28) 88%,
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
  /* warmer + slightly deeper than --color-paper (which is the site bg) */
  background-color: oklch(0.935 0.024 78);
  color: var(--color-ink, #1c1a17);
  overflow: hidden;
  position: relative;
}

/* Paper grain — SVG feTurbulence noise, blended over the page tint */
.almanac-book :deep(.page)::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='220' height='220'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0.12  0 0 0 0 0.10  0 0 0 0 0.08  0 0 0 0.55 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
  background-size: 220px 220px;
  opacity: 0.08;
  mix-blend-mode: multiply;
  pointer-events: none;
  z-index: 0;
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
  background: linear-gradient(to left, oklch(0.18 0.014 55 / 0.12), transparent);
}
.almanac-book :deep(.stf__item.--right .page)::after {
  left: 0;
  background: linear-gradient(to right, oklch(0.18 0.014 55 / 0.12), transparent);
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
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 9px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--color-ink-mute, #8a8275);
  z-index: 2;
}

/* ───────────  Covers (hard, dark binding)  ─────────── */
.almanac-book :deep(.page-cover) {
  background-color: oklch(0.23 0.028 50); /* deep tobacco */
  background-image:
    radial-gradient(at 28% 26%, oklch(0.32 0.032 52 / 0.55), transparent 60%),
    radial-gradient(at 78% 80%, oklch(0.16 0.018 48 / 0.55), transparent 65%);
  color: oklch(0.92 0.05 80);
  border: none;
  box-shadow: inset 0 0 0 1px oklch(0.34 0.028 50);
}

/* leather-grain noise, a touch coarser than page grain */
.almanac-book :deep(.page-cover)::before {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='180' height='180'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.72' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0.05  0 0 0 0 0.04  0 0 0 0 0.03  0 0 0 0.85 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
  background-size: 180px 180px;
  opacity: 0.18;
  mix-blend-mode: overlay;
}

/* Amber-foil inset frame on the front cover */
.almanac-book :deep(.cover-frame) {
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
  margin: 18px;
  padding: 44px 32px;
  border: 1px solid oklch(0.72 0.12 80 / 0.42);
  box-shadow: inset 0 0 0 6px oklch(0.23 0.028 50);
}
.almanac-book :deep(.cover-frame-back) {
  align-items: center;
  text-align: center;
}

/* Cover typography */
.almanac-book :deep(.cover-eyebrow) {
  color: oklch(0.72 0.12 80); /* amber */
}
.almanac-book :deep(.cover-title) {
  color: oklch(0.94 0.04 82); /* warm cream */
}
.almanac-book :deep(.cover-title-em) {
  color: oklch(0.78 0.10 80); /* honey */
  font-style: italic;
}
.almanac-book :deep(.cover-tagline) {
  color: oklch(0.84 0.04 78);
}
.almanac-book :deep(.cover-imprint) {
  color: oklch(0.66 0.08 80);
}

/* ───────────  TOC interactivity  ─────────── */
.almanac-book :deep(.toc-link) {
  cursor: pointer;
  padding-left: 4px;
  padding-right: 4px;
  transition: background-color 0.15s ease;
}
.almanac-book :deep(.toc-link:hover) {
  background: oklch(0.91 0.03 78);
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
  color: var(--color-ink, #1c1a17);
  padding: 0;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 10px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: color 0.15s ease, opacity 0.35s ease;
}
.book-btn-icon {
  padding: 2px;
}
.book-btn:hover:not(:disabled) {
  color: var(--color-accent-deep, oklch(0.54 0.12 78));
}
.book-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* Slash divider between Home and Contents */
.book-toolbar-divider {
  color: var(--color-accent-deep, oklch(0.54 0.12 78));
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
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
  transition: opacity 0.35s ease, color 0.15s ease, border-color 0.15s ease;
}
.book-btn-nav.is-shown {
  opacity: 1;
  pointer-events: auto;
}


.book-counter {
  color: var(--color-ink-mute, #8a8275);
  min-width: 70px;
  text-align: center;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 10px;
  letter-spacing: 0.24em;
  text-transform: uppercase;
}
</style>
