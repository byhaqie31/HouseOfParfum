<template>
  <div class="space-y-6">

    <!-- Loading / empty states -->
    <div v-if="loading" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
      Loading…
    </div>
    <div v-else-if="!users.length" class="border border-rule px-6 py-16 text-center font-mono text-[11px] text-ink-mute uppercase tracking-widest">
      No users found.
    </div>

    <template v-else>
      <!-- Desktop table (md+) -->
      <div class="hidden md:block border border-rule">
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
            <tr
              v-for="user in users"
              :key="user.id"
              class="hover:bg-paper-deep transition-colors"
            >
              <td class="px-6 py-4 text-ink">{{ user.name }}</td>
              <td class="px-6 py-4 text-ink-soft font-mono text-xs">{{ user.email }}</td>
              <td class="px-6 py-4">
                <RoleBadge :role="user.role" />
              </td>
              <td class="px-6 py-4 font-mono text-xs text-ink-mute">{{ formatDate(user.created_at) }}</td>
              <td class="px-6 py-4 text-right">
                <RoleToggle :user="user" :busy="toggling === user.id" :current-email="currentUserEmail" @toggle="toggleRole" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile cards (< md) -->
      <div class="md:hidden border border-rule divide-y divide-rule">
        <div
          v-for="user in users"
          :key="user.id"
          class="px-4 py-4 space-y-2"
        >
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <p class="text-sm text-ink font-medium truncate">{{ user.name }}</p>
              <p class="font-mono text-[10px] text-ink-mute truncate">{{ user.email }}</p>
            </div>
            <RoleBadge :role="user.role" class="shrink-0" />
          </div>
          <div class="flex items-center justify-between">
            <p class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">
              {{ formatDate(user.created_at) }}
            </p>
            <RoleToggle :user="user" :busy="toggling === user.id" :current-email="currentUserEmail" @toggle="toggleRole" />
          </div>
        </div>
      </div>
    </template>

    <!-- Pagination -->
    <div v-if="meta && !loading" class="flex items-center justify-between">
      <p class="font-mono text-[10px] text-ink-mute uppercase tracking-widest">
        {{ meta.from }}–{{ meta.to }} of {{ meta.total }}
      </p>
      <div class="flex items-center gap-3">
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

// Inline sub-components to avoid extra files
const RoleBadge = defineComponent({
  props: { role: String },
  setup(props) {
    return () => h('span', {
      class: [
        'font-mono text-[10px] uppercase tracking-widest px-2 py-0.5',
        props.role === 'admin' ? 'bg-accent-soft text-ink' : 'bg-paper-deep text-ink-mute',
      ].join(' '),
    }, props.role === 'admin' ? 'Admin' : 'User')
  },
})

const RoleToggle = defineComponent({
  props: {
    user: Object as () => AdminUser,
    busy: Boolean,
    currentEmail: String,
  },
  emits: ['toggle'],
  setup(props, { emit }) {
    return () => props.user?.email !== props.currentEmail
      ? h('button', {
          class: 'font-mono text-[10px] uppercase tracking-widest text-ink-soft hover:text-ink transition-colors disabled:opacity-40',
          disabled: props.busy,
          onClick: () => emit('toggle', props.user),
        }, props.user?.role === 'admin' ? 'Revoke admin' : 'Make admin')
      : null
  },
})

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
