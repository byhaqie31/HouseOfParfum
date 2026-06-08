<script setup lang="ts">
import { scentWorld, hashSeed, FAMILY_ORDER } from '~/utils/scent'

definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()
const auth = useAuthStore()
const canvas = useCanvasStore()
const isDark = computed(() => canvas.isDark)

const currentUserEmail = computed(() => auth.user?.email)

interface AdminUser {
  id: number
  name: string
  email: string
  role: string
  created_at: string
}
interface Meta { from: number; to: number; total: number; prev_page_url: string | null; next_page_url: string | null }

const users = ref<AdminUser[]>([])
const meta = ref<Meta | null>(null)
const loading = ref(true)
const toggling = ref<number | null>(null)
const page = ref(1)

const formatDate = (iso: string) =>
  new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })

const initialsOf = (name: string) =>
  name.trim().split(/\s+/).slice(0, 2).map((p) => p[0]?.toUpperCase() ?? '').join('') || '·'

// Give every member a deterministic little colour world — the scent system,
// applied to people: same member always reads the same hue.
function worldOf(u: AdminUser) {
  const seed = hashSeed(u.name + u.email)
  const family = FAMILY_ORDER[Math.floor(seed * FAMILY_ORDER.length)] ?? 'woody'
  return scentWorld(family, seed, isDark.value)
}

async function load(p = 1) {
  loading.value = true
  page.value = p
  const res = await api.get(`/admin/users?page=${p}`)
  users.value = res.data
  meta.value = res
  loading.value = false
}

async function toggleRole(user: AdminUser) {
  toggling.value = user.id
  try {
    const newRole = user.role === 'admin' ? '0' : 'admin'
    const updated = await api.patch(`/admin/users/${user.id}`, { role: newRole })
    user.role = updated.role
  }
  finally {
    toggling.value = null
  }
}

onMounted(() => load(1))
</script>

<template>
  <div>
    <AdminPageHeader title="Members" sub="Everyone in the house." />

    <div
      v-if="loading"
      class="rounded-card border px-6 py-16 text-center fm uppercase"
      style="border-color: var(--color-rule); background: var(--color-surface); font-size: 11px; letter-spacing: 0.14em; color: var(--color-ink-mute);"
    >Loading…</div>

    <template v-else>
      <!-- Desktop table -->
      <div class="hidden overflow-hidden rounded-card border md:block" style="border-color: var(--color-rule); background: var(--color-surface);">
        <table class="w-full border-collapse">
          <thead>
            <tr style="border-bottom: 1px solid var(--color-rule);">
              <th class="mem-th" style="width: 42%;">Member</th>
              <th class="mem-th">Role</th>
              <th class="mem-th">Joined</th>
              <th class="mem-th" style="text-align: right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(u, i) in users" :key="u.id" :style="{ borderTop: i ? '1px solid var(--color-rule)' : 'none' }">
              <td class="px-4 py-3.5">
                <div class="flex items-center gap-3">
                  <span
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                    :style="{ background: worldOf(u).gradient, color: worldOf(u).onGrad }"
                  ><span class="fd" style="font-size: 12px;">{{ initialsOf(u.name) }}</span></span>
                  <div class="min-w-0">
                    <div class="fd truncate" style="font-size: 15px; line-height: 1.15; color: var(--color-ink);">{{ u.name }}</div>
                    <div class="fb truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ u.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3.5">
                <span
                  class="fm uppercase"
                  :style="u.role === 'admin'
                    ? { fontSize: '9.5px', letterSpacing: '0.08em', color: 'var(--color-accent-deep)', background: 'var(--color-accent-soft)', padding: '4px 9px', borderRadius: '999px' }
                    : { fontSize: '9.5px', letterSpacing: '0.08em', color: 'var(--color-ink-mute)', background: 'var(--color-surface-2)', padding: '4px 9px', borderRadius: '999px' }"
                >{{ u.role === 'admin' ? 'Admin' : 'Member' }}</span>
              </td>
              <td class="mem-td">{{ formatDate(u.created_at) }}</td>
              <td class="px-4 py-3.5 text-right">
                <button
                  v-if="u.email !== currentUserEmail"
                  type="button"
                  class="fm uppercase disabled:opacity-40"
                  style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-ink-soft);"
                  :disabled="toggling === u.id"
                  @click="toggleRole(u)"
                >{{ u.role === 'admin' ? 'Revoke admin' : 'Make admin' }}</button>
                <span v-else class="fm uppercase" style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-ink-mute);">You</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile cards -->
      <div class="flex flex-col gap-3 md:hidden">
        <div
          v-for="u in users"
          :key="u.id"
          class="rounded-card border p-4"
          style="border-color: var(--color-rule); background: var(--color-surface);"
        >
          <div class="flex items-center gap-3">
            <span
              class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
              :style="{ background: worldOf(u).gradient, color: worldOf(u).onGrad }"
            ><span class="fd" style="font-size: 12px;">{{ initialsOf(u.name) }}</span></span>
            <div class="min-w-0 flex-1">
              <div class="fd truncate" style="font-size: 15px; line-height: 1.15; color: var(--color-ink);">{{ u.name }}</div>
              <div class="fb truncate" style="font-size: 11px; color: var(--color-ink-mute);">{{ u.email }}</div>
            </div>
            <span
              class="fm uppercase"
              :style="u.role === 'admin'
                ? { fontSize: '9.5px', color: 'var(--color-accent-deep)', background: 'var(--color-accent-soft)', padding: '4px 9px', borderRadius: '999px' }
                : { fontSize: '9.5px', color: 'var(--color-ink-mute)', background: 'var(--color-surface-2)', padding: '4px 9px', borderRadius: '999px' }"
            >{{ u.role === 'admin' ? 'Admin' : 'Member' }}</span>
          </div>
          <div class="mt-3 flex items-center justify-between">
            <span class="fm uppercase" style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-ink-mute);">{{ formatDate(u.created_at) }}</span>
            <button
              v-if="u.email !== currentUserEmail"
              type="button"
              class="fm uppercase disabled:opacity-40"
              style="font-size: 10px; letter-spacing: 0.1em; color: var(--color-ink-soft);"
              :disabled="toggling === u.id"
              @click="toggleRole(u)"
            >{{ u.role === 'admin' ? 'Revoke admin' : 'Make admin' }}</button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="meta && users.length" class="mt-5 flex items-center justify-between">
        <p class="fm uppercase" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-mute);">{{ meta.from }}–{{ meta.to }} of {{ meta.total }}</p>
        <div class="flex items-center gap-4">
          <button type="button" class="fm uppercase disabled:opacity-30" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-soft);" :disabled="!meta.prev_page_url" @click="load(page - 1)">← Prev</button>
          <button type="button" class="fm uppercase disabled:opacity-30" style="font-size: 10px; letter-spacing: 0.12em; color: var(--color-ink-soft);" :disabled="!meta.next_page_url" @click="load(page + 1)">Next →</button>
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>
.mem-th {
  padding: 11px 16px;
  text-align: left;
  font-family: var(--font-data);
  font-weight: 700;
  font-size: 9.5px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--color-ink-mute);
}
.mem-td {
  padding: 14px 16px;
  font-family: var(--font-data);
  font-weight: 700;
  font-variant-numeric: tabular-nums;
  font-size: 12.5px;
  color: var(--color-ink-mute);
}
</style>
