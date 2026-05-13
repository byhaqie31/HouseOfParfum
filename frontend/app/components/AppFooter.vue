<template>
  <footer
    class="bg-paper-deep text-ink-soft border-t border-rule relative
           after:absolute after:-top-px after:right-0 after:w-20 after:h-px after:bg-accent after:content-['']"
  >
    <!-- Main Footer -->
    <div class="max-w-300 mx-auto px-6 py-16">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <h3 class="font-display text-2xl font-normal text-ink tracking-[-0.005em] mb-4">
            House of Parfum
          </h3>
          <p class="text-sm leading-relaxed text-ink-soft">
            A companion for the fragrance devoted.
          </p>
        </div>

        <!-- Shop -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Shop</h4>
          <ul class="space-y-3">
            <li>
              <NuxtLink to="/perfume" class="text-sm text-ink-soft hover:text-ink transition-colors">
                All Fragrances
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/" class="text-sm text-ink-soft hover:text-ink transition-colors">
                New Arrivals
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Account -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Account</h4>
          <ul class="space-y-3">
            <li>
              <NuxtLink to="/profile" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Profile
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/today" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Today
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/" class="text-sm text-ink-soft hover:text-ink transition-colors">
                Sign In
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-[0.2em] mb-5">Contact</h4>
          <ul class="space-y-3">
            <li class="flex items-center gap-2">
              <Icon name="lucide:map-pin" size="14" class="text-ink-mute shrink-0" />
              <span class="text-sm text-ink-soft">Shah Alam, Malaysia</span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:mail" size="14" class="text-ink-mute shrink-0" />
              <a href="mailto:product.dev1455@gmail.com" class="text-sm text-ink-soft hover:text-ink transition-colors">
                product.dev1455@gmail.com
              </a>
            </li>
          </ul>

          <!-- Social Icons -->
          <div class="flex items-center gap-4 mt-7">
            <a href="#" aria-label="Instagram" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:instagram" size="16" />
            </a>
            <a href="#" aria-label="TikTok" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:music-2" size="16" />
            </a>
            <a href="#" aria-label="X" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:twitter" size="16" />
            </a>
            <a href="#" aria-label="Facebook" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:facebook" size="16" />
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-rule">
      <div class="max-w-300 mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-mute">
          &copy; {{ new Date().getFullYear() }} AxelNova &middot; All rights reserved
        </p>
        <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-mute">
          Made by Baihaqie Yusri
        </p>
      </div>
    </div>
  </footer>
</template>
