<template>
  <div ref="root" class="editorial-select" :class="{ 'is-open': open }">
    <button
      type="button"
      class="trigger"
      :aria-expanded="open"
      :aria-haspopup="'listbox'"
      @click="toggle"
    >
      <span class="trigger-label">
        {{ selectedOption?.label ?? placeholder ?? 'Select…' }}
      </span>
      <span v-if="selectedOption?.count != null" class="trigger-count">
        ({{ selectedOption.count }})
      </span>
      <Icon name="lucide:chevron-down" size="14" class="trigger-icon" />
    </button>

    <div v-if="open" class="menu">
      <input
        v-if="searchable"
        ref="searchInput"
        v-model="query"
        type="text"
        class="menu-search"
        :placeholder="searchPlaceholder ?? 'Search…'"
        autocomplete="off"
      >
      <ul class="menu-list" role="listbox">
        <li v-for="opt in filteredOptions" :key="opt.value">
          <button
            type="button"
            class="option"
            :class="{ 'is-selected': opt.value === modelValue }"
            role="option"
            :aria-selected="opt.value === modelValue"
            @click="select(opt)"
          >
            <span class="option-label">{{ opt.label }}</span>
            <span v-if="opt.count != null" class="option-count">{{ opt.count }}</span>
          </button>
        </li>
        <li v-if="filteredOptions.length === 0" class="option-empty">
          Nothing matches that.
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick, onBeforeUnmount } from 'vue'

interface SelectOption {
  value: string
  label: string
  count?: number
}

const props = defineProps<{
  modelValue: string
  options: SelectOption[]
  placeholder?: string
  /** When true, the menu gains a search box that filters options by label. */
  searchable?: boolean
  searchPlaceholder?: string
}>()

const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const open = ref(false)
const query = ref('')
const root = ref<HTMLElement | null>(null)
const searchInput = ref<HTMLInputElement | null>(null)

const selectedOption = computed(
  () => props.options.find((o) => o.value === props.modelValue) ?? null,
)

const filteredOptions = computed(() => {
  if (!props.searchable) return props.options
  const q = query.value.trim().toLowerCase()
  if (!q) return props.options
  return props.options.filter((o) => o.label.toLowerCase().includes(q))
})

function toggle() {
  open.value = !open.value
}
function close() {
  open.value = false
}
function select(opt: SelectOption) {
  emit('update:modelValue', opt.value)
  close()
}

function onDocClick(e: MouseEvent) {
  if (root.value && !root.value.contains(e.target as Node)) close()
}
function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape') close()
}

watch(open, (val) => {
  if (typeof window === 'undefined') return
  if (val) {
    window.addEventListener('click', onDocClick)
    window.addEventListener('keydown', onKey)
    if (props.searchable) nextTick(() => searchInput.value?.focus())
  } else {
    query.value = ''
    window.removeEventListener('click', onDocClick)
    window.removeEventListener('keydown', onKey)
  }
})

onBeforeUnmount(() => {
  if (typeof window === 'undefined') return
  window.removeEventListener('click', onDocClick)
  window.removeEventListener('keydown', onKey)
})
</script>

<style scoped>
.editorial-select {
  position: relative;
  width: 100%;
}

.trigger {
  width: 100%;
  height: 46px;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--color-surface);
  border: 1px solid var(--color-rule);
  border-radius: var(--radius-field);
  padding: 0 14px;
  text-align: left;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--color-ink);
  cursor: pointer;
  transition: border-color 0.15s ease;
}
.trigger:hover,
.editorial-select.is-open .trigger {
  border-color: var(--color-ink-soft);
}
.trigger-label {
  flex: 1;
  min-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.trigger-count {
  color: var(--color-ink-mute);
  font-size: 10px;
}
.trigger-icon {
  color: var(--color-ink-soft);
  transition: transform 0.15s ease;
  flex-shrink: 0;
}
.editorial-select.is-open .trigger-icon {
  transform: rotate(180deg);
}

.menu {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  right: 0;
  background: var(--color-surface);
  border: 1px solid var(--color-ink-soft);
  border-radius: var(--radius-field);
  overflow: hidden;
  z-index: 30;
}

.menu-search {
  width: 100%;
  box-sizing: border-box;
  padding: 9px 14px;
  background: var(--color-paper-deep);
  border: none;
  border-bottom: 1px solid var(--color-rule);
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 11px;
  color: var(--color-ink);
  outline: none;
}
.menu-search::placeholder {
  color: var(--color-ink-mute);
}

.menu-list {
  margin: 0;
  padding: 0;
  list-style: none;
  max-height: 320px;
  overflow-y: auto;
}

.option {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 14px;
  background: transparent;
  border: none;
  border-bottom: 1px solid var(--color-rule);
  text-align: left;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--color-ink);
  cursor: pointer;
  transition: background-color 0.15s ease, color 0.15s ease;
}
.menu-list li:last-child .option {
  border-bottom: none;
}
.option:hover {
  background: var(--color-paper-deep);
}
.option.is-selected {
  background: var(--color-accent-soft);
  color: var(--color-accent-deep);
  font-weight: 500;
}
.option-count {
  color: var(--color-ink-mute);
  font-size: 10px;
}
.option.is-selected .option-count {
  color: var(--color-accent-deep);
}
.option-empty {
  padding: 12px 14px;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
  font-size: 10px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--color-ink-mute);
}
</style>
