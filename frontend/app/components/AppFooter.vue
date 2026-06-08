<template>
  <footer
    class="relative border-t"
    style="background: var(--color-surface-2); color: var(--color-ink-soft); border-color: var(--color-rule);"
  >
    <!-- Main Footer — marketing surface only (hidden when logged in) -->
    <div
      v-if="!auth.isLoggedIn"
      class="mx-auto max-w-300 border-b px-6 py-16"
      style="border-color: var(--color-rule);"
    >
      <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Brand — enlarged to anchor the row -->
        <div>
          <div class="mb-5 flex items-center gap-4">
            <span
              class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-card"
              :style="{ background: world.soft }"
            >
              <img
                src="/favicon/favicon.svg"
                alt=""
                aria-hidden="true"
                class="h-9 w-9 shrink-0"
              >
            </span>
            <h3 class="fd" style="font-size: 24px; letter-spacing: -0.005em; line-height: 1.15; color: var(--color-ink);">
              House of Parfum
            </h3>
          </div>
          <p class="fb max-w-xs" style="font-size: 14px; line-height: 1.65; color: var(--color-ink-soft);">
            A companion for the fragrance devoted.
          </p>
        </div>

        <!-- Account -->
        <div>
          <h4 class="fm mb-5 uppercase" style="font-size: 11px; letter-spacing: 0.18em; color: var(--color-ink);">Account</h4>
          <ul class="space-y-3">
            <li>
              <NuxtLink to="/auth/register" class="fb footer-link" style="font-size: 14px;">
                Create Account
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/auth/login" class="fb footer-link" style="font-size: 14px;">
                Sign In
              </NuxtLink>
            </li>
            <li>
              <a href="#" class="fb footer-link" style="font-size: 14px;">
                Reviews
              </a>
            </li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h4 class="fm mb-5 uppercase" style="font-size: 11px; letter-spacing: 0.18em; color: var(--color-ink);">Legal</h4>
          <ul class="space-y-3">
            <li>
              <a href="#" class="fb footer-link" style="font-size: 14px;">
                Privacy Policy
              </a>
            </li>
            <li>
              <a href="#" class="fb footer-link" style="font-size: 14px;">
                Terms &amp; Conditions
              </a>
            </li>
            <li>
              <a href="#" class="fb footer-link" style="font-size: 14px;">
                Cookie Policy
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="fm mb-5 uppercase" style="font-size: 11px; letter-spacing: 0.18em; color: var(--color-ink);">Contact</h4>
          <ul class="space-y-3">
            <li class="flex items-start gap-2">
              <Icon name="lucide:store" size="14" class="mt-0.5 shrink-0" style="color: var(--color-ink-mute);" />
              <span class="fb leading-snug" style="font-size: 14px; color: var(--color-ink-soft);">
                Axel Nova Ventures
                <br>
                <span class="fm" style="font-size: 10px; letter-spacing: 0.04em; color: var(--color-ink-mute);">
                  202603119899 (CA0420977-U)
                </span>
              </span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:map-pin" size="14" class="shrink-0" style="color: var(--color-ink-mute);" />
              <span class="fb" style="font-size: 14px; color: var(--color-ink-soft);">Shah Alam, Malaysia</span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:mail" size="14" class="shrink-0" style="color: var(--color-ink-mute);" />
              <a href="mailto:baihaqie@axelnova.tech" class="fb footer-link" style="font-size: 14px;">
                baihaqie@axelnova.tech
              </a>
            </li>
          </ul>

          <!-- Social Icons — disabled, show "Coming soon" tooltip on hover -->
          <div class="mt-7 hidden items-center gap-4">
            <span
              v-for="social in socials"
              :key="social.label"
              class="group relative inline-flex cursor-not-allowed"
              style="color: var(--color-ink-mute);"
              :aria-label="`${social.label} (coming soon)`"
            >
              <Icon :name="social.icon" size="16" />
              <span
                class="fm pointer-events-none absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-field px-2 py-1 uppercase opacity-0 transition-opacity group-hover:opacity-100"
                style="font-size: 8px; letter-spacing: 0.18em; background: var(--color-ink); color: var(--color-canvas);"
              >
                Coming soon
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar — always visible -->
    <div class="mx-auto flex max-w-300 flex-col items-center justify-between gap-3 px-6 py-5 sm:flex-row">
      <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink-mute);">
        &copy; {{ new Date().getFullYear() }}
        <a
          href="https://axelnovaventures.com"
          target="_blank"
          rel="noopener noreferrer"
          class="footer-link"
        >
          Axel Nova Ventures
        </a>
        &middot; All rights reserved
      </p>
      <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.14em; color: var(--color-ink-mute);">
        Made with love by
        <a
          href="https://baihaqie.com"
          target="_blank"
          rel="noopener noreferrer"
          class="footer-link"
        >
          Baihaqie Yusri
        </a>
      </p>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

const auth = useAuthStore()
const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())

const socials = [
  { label: 'Instagram', icon: 'lucide:instagram' },
  { label: 'TikTok',    icon: 'lucide:music-2'  },
  { label: 'X',         icon: 'lucide:twitter'  },
  { label: 'Facebook',  icon: 'lucide:facebook' },
] as const
</script>

<style scoped>
.footer-link {
  color: var(--color-ink-soft);
  transition: color 0.15s ease;
}
.footer-link:hover {
  color: var(--color-ink);
}
</style>
