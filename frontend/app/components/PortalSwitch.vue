<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

// A segmented App ⇄ Admin context switch, shown in both shells' sidebars (admins
// only). The active half is a raised thumb tinted with the day's accent; tapping
// the other half navigates to that context's home. Replaces the old plain
// "Admin portal" / "View the app" text links.
const props = defineProps<{ current: 'app' | 'admin' }>()

const { worldFor } = useScentWorld()
const world = worldFor(() => familyOfTheHour())

const segments = [
  { key: 'app', label: 'App', icon: 'lucide:sparkles', to: '/user/today' },
  { key: 'admin', label: 'Admin', icon: 'lucide:layout-dashboard', to: '/admin' },
] as const
</script>

<template>
  <div
    class="relative grid grid-cols-2 gap-1 rounded-full p-1"
    style="background: var(--color-surface-2); border: 1px solid var(--color-rule);"
    role="tablist"
    aria-label="Switch portal"
  >
    <NuxtLink
      v-for="s in segments"
      :key="s.key"
      :to="s.to"
      class="portal-seg flex items-center justify-center gap-1.5 rounded-full py-1.5 fm uppercase"
      :class="{ 'portal-seg--on': current === s.key }"
      style="font-size: 10px; letter-spacing: 0.1em;"
      :style="current === s.key
        ? { background: 'var(--color-surface)', color: world.accent, boxShadow: '0 1px 5px -1px rgba(0,0,0,0.2)' }
        : undefined"
      :aria-current="current === s.key ? 'page' : undefined"
    >
      <Icon :name="s.icon" size="14" />
      {{ s.label }}
    </NuxtLink>
  </div>
</template>

<style scoped>
.portal-seg {
  color: var(--color-ink-mute);
  transition: color 0.18s ease, background-color 0.18s ease, box-shadow 0.18s ease;
}
.portal-seg:not(.portal-seg--on):hover { color: var(--color-ink); }
</style>
