<template>
  <div class="min-h-screen bg-paper flex">

    <!-- Mobile backdrop -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 z-20 bg-ink/25 md:hidden"
        @click="sidebarOpen = false"
      />
    </Transition>

    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 z-30 w-60 bg-paper-deep border-r border-rule flex flex-col
             transition-transform duration-200 ease-in-out
             -translate-x-full md:translate-x-0"
      :class="{ 'translate-x-0': sidebarOpen }"
    >
      <!-- Brand -->
      <div class="h-14 px-5 flex items-center justify-between border-b border-rule shrink-0">
        <NuxtLink to="/admin" class="min-w-0">
          <p class="font-display text-[15px] text-ink tracking-tight truncate leading-tight">
            House of Parfum
          </p>
          <p class="font-mono text-[9px] uppercase tracking-[0.2em] text-ink-mute">
            Admin
          </p>
        </NuxtLink>

        <!-- Close button (mobile only) -->
        <button
          class="md:hidden text-ink-mute hover:text-ink transition-colors shrink-0 ml-2"
          aria-label="Close menu"
          @click="sidebarOpen = false"
        >
          <Icon name="lucide:x" class="h-4 w-4" />
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-0.5">
        <!-- Overview -->
        <NuxtLink
          to="/admin"
          class="admin-nav-link"
          exact-active-class="admin-nav-link--active"
          @click="sidebarOpen = false"
        >
          <Icon name="lucide:layout-dashboard" class="h-3.5 w-3.5 shrink-0" />
          Dashboard
        </NuxtLink>

        <!-- Manage section -->
        <p class="font-mono text-[9px] uppercase tracking-[0.2em] text-ink-mute px-3 pt-5 pb-1.5">
          Manage
        </p>

        <NuxtLink
          to="/admin/users"
          class="admin-nav-link"
          active-class="admin-nav-link--active"
          @click="sidebarOpen = false"
        >
          <Icon name="lucide:users" class="h-3.5 w-3.5 shrink-0" />
          Users
        </NuxtLink>

        <NuxtLink
          to="/admin/perfumes"
          class="admin-nav-link"
          active-class="admin-nav-link--active"
          @click="sidebarOpen = false"
        >
          <Icon name="lucide:spray-can" class="h-3.5 w-3.5 shrink-0" />
          Perfumes
        </NuxtLink>

        <NuxtLink
          to="/admin/almanac"
          class="admin-nav-link"
          active-class="admin-nav-link--active"
          @click="sidebarOpen = false"
        >
          <Icon name="lucide:book-open" class="h-3.5 w-3.5 shrink-0" />
          Almanac
        </NuxtLink>
      </nav>

      <!-- Admin identity + sign out -->
      <div class="px-4 py-4 border-t border-rule shrink-0">
        <div class="flex items-center gap-3 mb-4">
          <div class="h-8 w-8 rounded-full bg-accent-soft border border-accent flex items-center justify-center shrink-0">
            <span class="font-display text-[12px] text-accent-deep tracking-tight">
              {{ adminInitials }}
            </span>
          </div>
          <div class="min-w-0">
            <p class="font-display text-[13px] text-ink leading-tight truncate">
              {{ auth.user?.name || 'Admin' }}
            </p>
            <p class="font-mono text-[9px] text-ink-mute tracking-tight truncate">
              {{ auth.user?.email || '' }}
            </p>
          </div>
        </div>

        <button
          class="w-full text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
          @click="handleLogout"
        >
          Sign out
        </button>
      </div>
    </aside>

    <!-- Main content area (offset by sidebar on md+) -->
    <div class="flex-1 min-w-0 flex flex-col md:pl-60">

      <!-- Top bar -->
      <header class="sticky top-0 z-10 h-14 bg-paper/90 backdrop-blur-xl border-b border-rule flex items-center px-5 md:px-8 gap-4">
        <!-- Hamburger (mobile only) -->
        <button
          class="md:hidden text-ink-soft hover:text-ink transition-colors shrink-0"
          aria-label="Open menu"
          @click="sidebarOpen = true"
        >
          <Icon name="lucide:menu" class="h-5 w-5" />
        </button>

        <!-- Accent hairline (mirrors the main navbar style) -->
        <div class="absolute bottom-0 left-0 w-12 h-px bg-accent" />

        <p class="font-mono text-[11px] uppercase tracking-widest text-ink-soft">
          {{ pageTitle }}
        </p>

        <!-- Live preview -->
        <NuxtLink
          to="/user/today"
          class="ml-auto inline-flex items-center gap-1.5 font-mono text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
        >
          <Icon name="lucide:eye" class="h-3 w-3" />
          <span class="sm:hidden">Preview</span>
          <span class="hidden sm:inline">Show live preview</span>
        </NuxtLink>
      </header>

      <!-- Page content -->
      <main class="flex-1 px-5 py-6 md:px-8 md:py-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const sidebarOpen = ref(false)

const pageTitles: Record<string, string> = {
  '/admin': 'Dashboard',
  '/admin/users': 'Users',
  '/admin/perfumes': 'Perfumes',
  '/admin/almanac': 'Almanac',
}

const pageTitle = computed(
  () => pageTitles[route.path] ?? route.path.split('/').pop() ?? 'Admin',
)

const adminInitials = computed(() => {
  const name = auth.user?.name?.trim()
  if (!name) return 'A'
  return name
    .split(/\s+/)
    .slice(0, 2)
    .map((part: string) => part[0]?.toUpperCase() ?? '')
    .join('')
})

// Close sidebar when route changes (mobile nav tap)
watch(() => route.path, () => { sidebarOpen.value = false })

const handleLogout = async () => {
  const api = useApi()
  try { await api.post('/logout', {}) } catch {}
  auth.logout()
  await router.push('/auth/login')
}
</script>

<style scoped>
.admin-nav-link {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.5rem 0.75rem;
  border-radius: var(--radius-sm);
  border-left: 2px solid transparent;
  font-family: var(--font-mono);
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.16em;
  color: var(--color-ink-soft);
  transition: color 0.15s, background-color 0.15s, border-color 0.15s;
}

.admin-nav-link:hover {
  color: var(--color-ink);
  background-color: var(--color-paper);
}

.admin-nav-link--active {
  color: var(--color-ink);
  background-color: var(--color-paper);
  border-left-color: var(--color-accent);
}
</style>
