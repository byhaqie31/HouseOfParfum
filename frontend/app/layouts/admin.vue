<template>
  <div class="min-h-screen bg-paper flex">
    <!-- Sidebar -->
    <aside class="w-56 shrink-0 border-r border-rule flex flex-col">
      <div class="px-6 py-6 border-b border-rule">
        <NuxtLink to="/admin" class="font-display text-base text-ink tracking-tight leading-tight">
          House of Parfum<br>
          <span class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">Admin</span>
        </NuxtLink>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-0.5">
        <NuxtLink
          v-for="item in nav"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-2.5 px-3 py-2 text-xs uppercase tracking-widest text-ink-soft hover:text-ink hover:bg-paper-deep transition-colors rounded-sm"
          active-class="text-ink bg-paper-deep"
        >
          <Icon :name="item.icon" class="h-3.5 w-3.5 shrink-0" />
          {{ item.label }}
        </NuxtLink>
      </nav>

      <div class="px-6 py-4 border-t border-rule">
        <button
          class="text-[10px] uppercase tracking-widest text-ink-mute hover:text-ink transition-colors"
          @click="handleLogout"
        >
          Sign out
        </button>
      </div>
    </aside>

    <!-- Content -->
    <div class="flex-1 min-w-0 flex flex-col">
      <header class="h-14 border-b border-rule flex items-center px-8">
        <h1 class="font-mono text-[11px] uppercase tracking-widest text-ink-soft">
          {{ pageTitle }}
        </h1>
      </header>

      <main class="flex-1 px-8 py-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const nav = [
  { to: '/admin', label: 'Dashboard', icon: 'lucide:layout-dashboard' },
  { to: '/admin/users', label: 'Users', icon: 'lucide:users' },
  { to: '/admin/perfumes', label: 'Perfumes', icon: 'lucide:spray-can' },
  { to: '/admin/almanac', label: 'Almanac', icon: 'lucide:book-open' },
]

const pageTitles: Record<string, string> = {
  '/admin': 'Dashboard',
  '/admin/users': 'Users',
  '/admin/perfumes': 'Perfumes',
  '/admin/almanac': 'Almanac',
}

const pageTitle = computed(() => pageTitles[route.path] ?? 'Admin')

const handleLogout = async () => {
  const api = useApi()
  try { await api.post('/logout', {}) } catch {}
  auth.logout()
  await router.push('/auth/login')
}
</script>
