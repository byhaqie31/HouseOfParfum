<template>
  <div class="space-y-6">
    <!-- Table -->
    <div class="border border-rule">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-rule">
            <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Name</th>
            <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Email</th>
            <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Role</th>
            <th class="px-6 py-3 text-left font-mono text-[10px] uppercase tracking-widest text-ink-mute">Joined</th>
            <th class="px-6 py-3" />
          </tr>
        </thead>
        <tbody class="divide-y divide-rule">
          <tr v-if="loading">
            <td colspan="5" class="px-6 py-12 text-center font-mono text-[11px] text-ink-mute">Loading…</td>
          </tr>
          <tr v-else-if="!users.length">
            <td colspan="5" class="px-6 py-12 text-center font-mono text-[11px] text-ink-mute">No users found.</td>
          </tr>
          <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-paper-deep transition-colors"
          >
            <td class="px-6 py-4 text-ink">{{ user.name }}</td>
            <td class="px-6 py-4 text-ink-soft font-mono text-xs">{{ user.email }}</td>
            <td class="px-6 py-4">
              <span
                class="font-mono text-[10px] uppercase tracking-widest px-2 py-0.5"
                :class="user.role === 'admin' ? 'bg-accent-soft text-ink' : 'bg-paper-deep text-ink-mute'"
              >
                {{ user.role === 'admin' ? 'Admin' : 'User' }}
              </span>
            </td>
            <td class="px-6 py-4 font-mono text-xs text-ink-mute">{{ formatDate(user.created_at) }}</td>
            <td class="px-6 py-4 text-right">
              <button
                v-if="user.email !== currentUserEmail"
                class="text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors"
                :disabled="toggling === user.id"
                @click="toggleRole(user)"
              >
                {{ user.role === 'admin' ? 'Revoke admin' : 'Make admin' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="meta" class="flex items-center justify-between">
      <p class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">
        {{ meta.from }}–{{ meta.to }} of {{ meta.total }}
      </p>
      <div class="flex items-center gap-2">
        <button
          class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-30"
          :disabled="!meta.prev_page_url"
          @click="loadPage(page - 1)"
        >
          ← Prev
        </button>
        <button
          class="font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-30"
          :disabled="!meta.next_page_url"
          @click="loadPage(page + 1)"
        >
          Next →
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: 'admin' })

const api = useApi()
const auth = useAuthStore()

const currentUserEmail = computed(() => auth.user?.email)

interface AdminUser {
  id: number
  name: string
  email: string
  role: string
  created_at: string
}

interface Meta {
  from: number
  to: number
  total: number
  prev_page_url: string | null
  next_page_url: string | null
}

const users = ref<AdminUser[]>([])
const meta = ref<Meta | null>(null)
const loading = ref(true)
const toggling = ref<number | null>(null)
const page = ref(1)

const formatDate = (iso: string) =>
  new Date(iso).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })

const loadPage = async (p: number) => {
  loading.value = true
  page.value = p
  const res = await api.get(`/admin/users?page=${p}`)
  users.value = res.data
  meta.value = res
  loading.value = false
}

const toggleRole = async (user: AdminUser) => {
  toggling.value = user.id
  const newRole = user.role === 'admin' ? '0' : 'admin'
  const updated = await api.patch(`/admin/users/${user.id}`, { role: newRole })
  user.role = updated.role
  toggling.value = null
}

onMounted(() => loadPage(1))
</script>
