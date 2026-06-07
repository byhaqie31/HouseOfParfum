<script setup lang="ts">
const props = withDefaults(defineProps<{
  modelValue: boolean
  maxWidth?: number
  label?: string
}>(), {
  maxWidth: 620,
})

const emit = defineEmits<{ 'update:modelValue': [value: boolean] }>()

function close() {
  emit('update:modelValue', false)
}

// Lock body scroll while open; close on Escape.
watch(() => props.modelValue, (open) => {
  if (!import.meta.client) return
  document.body.style.overflow = open ? 'hidden' : ''
})
function onKey(e: KeyboardEvent) {
  if (e.key === 'Escape' && props.modelValue) close()
}
onMounted(() => window.addEventListener('keydown', onKey))
onBeforeUnmount(() => {
  window.removeEventListener('keydown', onKey)
  if (import.meta.client) document.body.style.overflow = ''
})
</script>

<template>
  <Teleport to="body">
    <Transition name="hop-modal">
      <div
        v-if="modelValue"
        class="fixed inset-0 z-[100] flex items-end justify-center p-0 sm:items-center sm:p-6"
        :aria-label="label"
        role="dialog"
        aria-modal="true"
        @click.self="close"
        :style="{
          background: 'color-mix(in oklab, var(--color-canvas) 30%, rgba(0,0,0,0.55))',
          backdropFilter: 'blur(6px)',
        }"
      >
        <div
          class="hop-modal-panel relative max-h-[92vh] w-full overflow-y-auto rounded-t-modal border sm:rounded-modal"
          :style="{
            maxWidth: `${maxWidth}px`,
            background: 'var(--color-canvas)',
            borderColor: 'var(--color-rule)',
          }"
        >
          <button
            type="button"
            class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-full border"
            style="border-color: var(--color-rule); background: var(--color-surface); color: var(--color-ink-soft);"
            aria-label="Close"
            @click="close"
          >
            <Icon name="lucide:x" size="18" />
          </button>
          <slot />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.hop-modal-enter-active,
.hop-modal-leave-active { transition: opacity 0.24s ease; }
.hop-modal-enter-from,
.hop-modal-leave-to { opacity: 0; }
.hop-modal-enter-active .hop-modal-panel { transition: transform 0.28s cubic-bezier(.2,.8,.2,1); }
.hop-modal-enter-from .hop-modal-panel { transform: translateY(16px); }

@media (prefers-reduced-motion: reduce) {
  .hop-modal-enter-active,
  .hop-modal-leave-active,
  .hop-modal-enter-active .hop-modal-panel { transition: none; }
}
</style>
