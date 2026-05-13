<template>
  <footer
    class="bg-paper-deep text-ink-soft border-t border-rule relative
           after:absolute after:-top-px after:right-0 after:w-20 after:h-px after:bg-accent after:content-['']"
  >
    <!-- Main Footer — marketing surface only (hidden when logged in) -->
    <div v-if="!auth.isLoggedIn" class="max-w-300 mx-auto px-6 py-16 border-b border-rule">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Brand — enlarged to anchor the row -->
        <div>
          <div class="flex items-center gap-4 mb-5">
            <img
              src="/favicon/favicon.svg"
              alt=""
              aria-hidden="true"
              class="h-14 w-14 shrink-0 brightness-0"
            >
            <h3 class="font-display text-2xl font-normal text-ink tracking-[-0.005em] leading-tight">
              House of Parfum
            </h3>
          </div>
          <p class="text-sm leading-relaxed text-ink-soft max-w-xs">
            A companion for the fragrance devoted.
          </p>
        </div>

        <!-- Account -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Account</h4>
          <ul class="space-y-3">
            <li>
              <NuxtLink to="/register" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Create Account
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Sign In
              </NuxtLink>
            </li>
            <li>
              <a href="#" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Reviews
              </a>
            </li>
          </ul>
        </div>

        <!-- Legal -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Legal</h4>
          <ul class="space-y-3">
            <li>
              <a href="#" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Privacy Policy
              </a>
            </li>
            <li>
              <a href="#" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Terms &amp; Conditions
              </a>
            </li>
            <li>
              <a href="#" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Cookie Policy
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Contact</h4>
          <ul class="space-y-3">
            <li class="flex items-start gap-2">
              <Icon name="lucide:store" size="14" class="text-ink-mute shrink-0 mt-0.5" />
              <span class="text-sm text-ink-soft leading-snug">
                Axel Nova Ventures
                <br>
                <span class="font-mono text-[10px] text-ink-mute tracking-[0.04em]">
                  202603119899 (CA0420977-U)
                </span>
              </span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:map-pin" size="14" class="text-ink-mute shrink-0" />
              <span class="text-sm text-ink-soft">Shah Alam, Malaysia</span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:mail" size="14" class="text-ink-mute shrink-0" />
              <a href="mailto:baihaqie@axelnova.tech" class="text-sm text-ink-soft hover:text-ink transition-colors">
                baihaqie@axelnova.tech
              </a>
            </li>
          </ul>

          <!-- Social Icons — disabled, show "Coming soon" tooltip on hover -->
          <div class="items-center gap-4 mt-7 hidden">
            <span
              v-for="social in socials"
              :key="social.label"
              class="relative group inline-flex cursor-not-allowed text-ink-mute"
              :aria-label="`${social.label} (coming soon)`"
            >
              <Icon :name="social.icon" size="16" />
              <span
                class="pointer-events-none absolute -top-7 left-1/2 -translate-x-1/2 whitespace-nowrap bg-ink text-paper px-2 py-1 font-mono text-[8px] uppercase tracking-[0.18em] opacity-0 group-hover:opacity-100 transition-opacity"
              >
                Coming soon
              </span>
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar — always visible -->
    <div class="max-w-300 mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-mute">
        &copy; {{ new Date().getFullYear() }}
        <a
          href="https://axelnovaventures.com"
          target="_blank"
          rel="noopener noreferrer"
          class="text-ink-soft hover:text-ink transition-colors"
        >
          Axel Nova Ventures
        </a>
        &middot; All rights reserved
      </p>
      <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-mute">
        Made with love by
        <a
          href="https://baihaqie.com"
          target="_blank"
          rel="noopener noreferrer"
          class="text-ink-soft hover:text-ink transition-colors"
        >
          Baihaqie Yusri
        </a>
      </p>
    </div>
  </footer>
</template>

<script setup lang="ts">
const auth = useAuthStore()

const socials = [
  { label: 'Instagram', icon: 'lucide:instagram' },
  { label: 'TikTok',    icon: 'lucide:music-2'  },
  { label: 'X',         icon: 'lucide:twitter'  },
  { label: 'Facebook',  icon: 'lucide:facebook' },
] as const
</script>
