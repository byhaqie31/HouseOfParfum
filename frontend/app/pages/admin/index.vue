<template>
  <div class="space-y-8">
    <!-- Stats -->
    <div class="grid grid-cols-3 gap-6">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="border border-rule p-6"
      >
        <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute mb-3">
          {{ stat.label }}
        </p>
        <p class="font-display text-4xl text-ink">
          {{ stat.value ?? '—' }}
        </p>
      </div>
    </div>

    <!-- Quick links -->
    <div class="border border-rule divide-y divide-rule">
      <div class="px-6 py-4">
        <p class="font-mono text-[10px] uppercase tracking-widest text-ink-mute">Quick actions</p>
      </div>
      <NuxtLink
        v-for="link in quickLinks"
        :key="link.to"
        :to="link.to"
        class="flex items-center justify-between px-6 py-4 hover:bg-paper-deep transition-colors group"
      >
        <div class="flex items-center gap-3">
          <Icon :name="link.icon" class="h-4 w-4 text-ink-mute" />
          <span class="text-sm text-ink">{{ link.label }}</span>
        </div>
        <Icon name="lucide:arrow-right" class="h-3.5 w-3.5 text-ink-mute group-hover:text-ink transition-colors" />
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()

const data = ref<{ users: number; wardrobe_items: number; journal_entries: number } | null>(null)

const stats = computed(() => [
  { label: 'Total users', value: data.value?.users },
  { label: 'Wardrobe items', value: data.value?.wardrobe_items },
  { label: 'Journal entries', value: data.value?.journal_entries },
])

const quickLinks = [
  { to: '/admin/users', label: 'Manage users', icon: 'lucide:users' },
  { to: '/admin/perfumes', label: 'Manage perfumes', icon: 'lucide:spray-can' },
  { to: '/admin/almanac', label: 'Manage almanac', icon: 'lucide:book-open' },
]

onMounted(async () => {
  data.value = await api.get('/admin/stats')
})
</script>
