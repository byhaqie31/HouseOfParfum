<template>
  <footer class="bg-gray-950 text-gray-400">
    <!-- Main Footer -->
    <div class="max-w-300 mx-auto px-6 py-16">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <h3 class="text-white text-sm font-semibold mb-4">House of Parfum</h3>
          <p class="text-xs leading-relaxed text-gray-500">
            Curated fragrances for the modern connoisseur. Discover your signature scent.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-white text-xs font-medium uppercase tracking-widest mb-4">Shop</h4>
          <ul class="space-y-2.5">
            <li>
              <NuxtLink to="/perfume" class="text-xs text-gray-500 hover:text-white transition-colors">
                All Fragrances
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/" class="text-xs text-gray-500 hover:text-white transition-colors">
                New Arrivals
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/cart" class="text-xs text-gray-500 hover:text-white transition-colors">
                Cart
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Account -->
        <div>
          <h4 class="text-white text-xs font-medium uppercase tracking-widest mb-4">Account</h4>
          <ul class="space-y-2.5">
            <li>
              <NuxtLink to="/profile" class="text-xs text-gray-500 hover:text-white transition-colors">
                Profile
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/order" class="text-xs text-gray-500 hover:text-white transition-colors">
                Orders
              </NuxtLink>
            </li>
            <li>
              <NuxtLink to="/login" class="text-xs text-gray-500 hover:text-white transition-colors">
                Sign In
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-white text-xs font-medium uppercase tracking-widest mb-4">Contact</h4>
          <ul class="space-y-2.5">
            <li class="flex items-center gap-2">
              <!-- Location icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>
              <span class="text-xs text-gray-500">Shah Alam, Malaysia</span>
            </li>
            <li class="flex items-center gap-2">
              <!-- Email icon -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
              </svg>
              <a href="mailto:product.dev1455@gmail.com" class="text-xs text-gray-500 hover:text-white transition-colors">
                product.dev1455@gmail.com
              </a>
            </li>
          </ul>

          <!-- Social Icons -->
          <div class="flex items-center gap-4 mt-6">
            <!-- Instagram -->
            <a href="#" class="text-gray-600 hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
              </svg>
            </a>
            <!-- TikTok -->
            <a href="#" class="text-gray-600 hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48V13a8.28 8.28 0 005.58 2.16v-3.44a4.85 4.85 0 01-3.77-1.26v-3.77z"/>
              </svg>
            </a>
            <!-- X / Twitter -->
            <a href="#" class="text-gray-600 hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </a>
            <!-- Facebook -->
            <a href="#" class="text-gray-600 hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-800">
      <div class="max-w-300 mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-gray-600">
          &copy; {{ new Date().getFullYear() }} AxelNova. All rights reserved.
        </p>
        <p class="text-xs text-gray-600">
          Made by Baihaqie Yusri
        </p>
      </div>
    </div>
  </footer>
</template>
