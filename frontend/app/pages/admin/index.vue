<template>
  <div class="space-y-10">

    <!-- Stats 2×2 -->
    <div class="grid grid-cols-2 gap-4">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="relative border border-rule bg-paper-deep p-6 overflow-hidden"
      >
        <!-- Accent hairline -->
        <div class="absolute top-0 left-0 w-8 h-px bg-accent" />

        <p class="font-mono text-[9px] uppercase tracking-[0.2em] text-ink-mute mb-4">
          {{ stat.label }}
        </p>
        <p class="font-display text-5xl text-ink leading-none">
          {{ stat.value !== null ? stat.value.toLocaleString() : '—' }}
        </p>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()

interface Stats {
  users: number
  perfumes: number
  wardrobe_items: number
  journal_entries: number
}

const data = ref<Stats | null>(null)

const stats = computed(() => [
  { label: 'Users',           value: data.value?.users          ?? null },
  { label: 'Perfume registry', value: data.value?.perfumes       ?? null },
  { label: 'Wardrobe items',  value: data.value?.wardrobe_items  ?? null },
  { label: 'Journal entries', value: data.value?.journal_entries ?? null },
])

onMounted(async () => {
  data.value = await api.get('/admin/stats')
})
</script>
