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

    <ul v-if="open" class="menu" role="listbox">
      <li v-for="opt in options" :key="opt.value">
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
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onBeforeUnmount } from 'vue'

interface SelectOption {
  value: string
  label: string
  count?: number
}

const props = defineProps<{
  modelValue: string
  options: SelectOption[]
  placeholder?: string
}>()

const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const open = ref(false)
const root = ref<HTMLElement | null>(null)

const selectedOption = computed(
  () => props.options.find((o) => o.value === props.modelValue) ?? null,
)

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
  } else {
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
  display: flex;
  align-items: center;
  gap: 10px;
  background: var(--color-paper-deep);
  border: 1px solid var(--color-rule);
  padding: 10px 14px;
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
  top: calc(100% - 1px);
  left: 0;
  right: 0;
  background: var(--color-paper);
  border: 1px solid var(--color-ink-soft);
  max-height: 360px;
  overflow-y: auto;
  z-index: 30;
  margin: 0;
  padding: 0;
  list-style: none;
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
.editorial-select .menu li:last-child .option {
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
</style>
