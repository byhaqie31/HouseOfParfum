<template>
  <div
    class="spray-bottle relative inline-block"
    :style="{ width: `${size}px`, height: `${(size * 80) / 56}px` }"
    aria-hidden="true"
  >
    <!-- Simple round perfume flacon: spherical body with logo label, slim cap.
         No side nozzle — spray emerges directly from the top of the cap. -->
    <svg
      :width="size"
      :height="(size * 80) / 56"
      viewBox="0 0 56 80"
      xmlns="http://www.w3.org/2000/svg"
      class="block"
    >
      <!-- Round bottle body — soft outer champagne with a matching deeper hairline -->
      <circle
        cx="26"
        cy="50"
        r="24"
        fill="var(--color-accent-soft)"
        stroke="var(--color-accent-deep)"
        stroke-width="0.6"
      />

      <!-- Brand mark sits directly on the body — no badge, sized to fill the
           round face of the bottle. The favicon embeds a coloured PNG, so a
           CSS `brightness(0)` filter crushes its non-transparent pixels to
           pure black for the silhouette look. -->
      <image
        href="/favicon/favicon.svg"
        x="6"
        y="30"
        width="40"
        height="40"
        class="brand-mark"
      />

      <!-- Neck (short connector below cap) -->
      <rect x="20" y="18" width="12" height="6" fill="var(--color-ink)" />

      <!-- Cap -->
      <rect x="16" y="8" width="20" height="10" fill="var(--color-ink)" />
      <!-- Small pump button on top of the cap, where the spray emerges -->
      <rect x="23" y="6" width="6" height="2" fill="var(--color-ink)" />
    </svg>

    <!-- Spray stream: bubbles + flowers emerge from the nozzle tip and drift
         right toward the greeting. Same particle config as the earlier design. -->
    <div class="spray-stream pointer-events-none">
      <span class="spray-p spray-p--bubble spray-p--1" />
      <span class="spray-p spray-p--bubble spray-p--2" />
      <span class="spray-p spray-p--bubble spray-p--3" />
      <Icon name="lucide:flower-2" size="14" class="spray-p spray-p--flower spray-p--4" />
      <Icon name="lucide:flower-2" size="13" class="spray-p spray-p--flower spray-p--5" />
      <Icon name="lucide:sparkles" size="12" class="spray-p spray-p--flower spray-p--6" />
    </div>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{ size?: number }>(), { size: 96 })
</script>

<style scoped>
/* Crush the favicon's colour pixels to pure black while preserving transparency. */
.brand-mark {
  filter: brightness(0);
}

/* Anchored at the pump button on top of the cap (viewBox: ~(26, 7) in 56×80).
   That's ~46% from left, ~9% from top of the wrapper. Particles puff up from
   the cap and drift right toward the greeting. */
.spray-stream {
  position: absolute;
  top: 9%;
  left: 46%;
  width: 220px;
  height: 24px;
}

.spray-p {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
  will-change: transform, opacity;
}

.spray-p--bubble {
  width: 5px;
  height: 5px;
  border-radius: 9999px;
  background-color: var(--color-accent);
}

.spray-p--flower {
  color: var(--color-accent);
}

.spray-p--1 { animation: spray-fly-a 3.6s ease-out 0s    infinite; }
.spray-p--2 { animation: spray-fly-b 4s   ease-out 0.6s  infinite; }
.spray-p--3 { animation: spray-fly-c 3.4s ease-out 1.5s  infinite; }
.spray-p--4 { animation: spray-fly-d 4.4s ease-out 0.25s infinite; }
.spray-p--5 { animation: spray-fly-e 4s   ease-out 2s    infinite; }
.spray-p--6 { animation: spray-fly-f 3.8s ease-out 2.7s  infinite; }

@keyframes spray-fly-a {
  0%   { opacity: 0;    transform: translate(0,       0)    scale(0.4); }
  15%  { opacity: 0.9; }
  60%  { opacity: 0.55; transform: translate(110px,  4px)   scale(1); }
  100% { opacity: 0;    transform: translate(200px,  8px)   scale(1.2); }
}
@keyframes spray-fly-b {
  0%   { opacity: 0;    transform: translate(0,       6px)  scale(0.4); }
  18%  { opacity: 0.85; }
  60%  { opacity: 0.5;  transform: translate(108px, 14px)   scale(1); }
  100% { opacity: 0;    transform: translate(200px, 20px)   scale(1.1); }
}
@keyframes spray-fly-c {
  0%   { opacity: 0;    transform: translate(0,      -2px)  scale(0.4); }
  15%  { opacity: 0.9; }
  60%  { opacity: 0.55; transform: translate(116px,  2px)   scale(1); }
  100% { opacity: 0;    transform: translate(200px,  6px)   scale(1.2); }
}
@keyframes spray-fly-d {
  0%   { opacity: 0;    transform: translate(0,       2px)  scale(0.4) rotate(0); }
  18%  { opacity: 0.85; }
  60%  { opacity: 0.55; transform: translate(108px,  10px)  scale(1)    rotate(80deg); }
  100% { opacity: 0;    transform: translate(195px,  14px)  scale(1.15) rotate(160deg); }
}
@keyframes spray-fly-e {
  0%   { opacity: 0;    transform: translate(0,      10px)  scale(0.4) rotate(0); }
  20%  { opacity: 0.8; }
  60%  { opacity: 0.5;  transform: translate(106px,  18px)  scale(1)    rotate(-70deg); }
  100% { opacity: 0;    transform: translate(200px,  22px)  scale(1.1)  rotate(-140deg); }
}
@keyframes spray-fly-f {
  0%   { opacity: 0;    transform: translate(0,      -4px)  scale(0.4) rotate(0); }
  16%  { opacity: 0.85; }
  60%  { opacity: 0.5;  transform: translate(112px,  -2px)  scale(1)    rotate(40deg); }
  100% { opacity: 0;    transform: translate(200px,  -6px)  scale(1.15) rotate(110deg); }
}

@media (prefers-reduced-motion: reduce) {
  .spray-p {
    animation: none;
    opacity: 0;
  }
}
</style>
