<template>
  <footer class="bg-paper-deep text-ink-soft border-t border-rule">
    <!-- Main Footer -->
    <div class="max-w-300 mx-auto px-6 py-16">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <h3 class="font-serif text-lg text-ink mb-4">House of Parfum</h3>
          <p class="text-xs leading-relaxed text-ink-mute">
            Curated fragrances for the modern connoisseur. Discover your signature scent.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-widest mb-4">Shop</h4>
          <ul class="space-y-2.5">
            <li>
              <NuxtLink to="/perfume" class="text-xs text-ink-soft hover:text-ink transition-colors">
                All Fragrances
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/" class="text-xs text-ink-soft hover:text-ink transition-colors">
                New Arrivals
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/cart" class="text-xs text-ink-soft hover:text-ink transition-colors">
                Cart
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Account -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-widest mb-4">Account</h4>
          <ul class="space-y-2.5">
            <li>
              <NuxtLink to="/profile" class="text-xs text-ink-soft hover:text-ink transition-colors">
                Profile
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/order" class="text-xs text-ink-soft hover:text-ink transition-colors">
                Orders
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/login" class="text-xs text-ink-soft hover:text-ink transition-colors">
                Sign In
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-ink text-xs font-medium uppercase tracking-widest mb-4">Contact</h4>
          <ul class="space-y-2.5">
            <li class="flex items-center gap-2">
              <Icon name="lucide:map-pin" class="h-3.5 w-3.5 text-ink-mute shrink-0" />
              <span class="text-xs text-ink-soft">Shah Alam, Malaysia</span>
            </li>
            <li class="flex items-center gap-2">
              <Icon name="lucide:mail" class="h-3.5 w-3.5 text-ink-mute shrink-0" />
              <a href="mailto:product.dev1455@gmail.com" class="text-xs text-ink-soft hover:text-ink transition-colors">
                product.dev1455@gmail.com
              </a>
            </li>
          </ul>

          <!-- Social Icons -->
          <div class="flex items-center gap-4 mt-6">
            <a href="#" aria-label="Instagram" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:instagram" class="h-4 w-4" />
            </a>
            <a href="#" aria-label="TikTok" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:music-2" class="h-4 w-4" />
            </a>
            <a href="#" aria-label="X" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:twitter" class="h-4 w-4" />
            </a>
            <a href="#" aria-label="Facebook" class="text-ink-mute hover:text-ink transition-colors">
              <Icon name="lucide:facebook" class="h-4 w-4" />
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-rule">
      <div class="max-w-300 mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="font-mono text-[11px] text-ink-mute">
          &copy; {{ new Date().getFullYear() }} AxelNova. All rights reserved.
        </p>
        <p class="font-mono text-[11px] text-ink-mute">
          Made by Baihaqie Yusri
        </p>
      </div>
    </div>
  </footer>
</template>
