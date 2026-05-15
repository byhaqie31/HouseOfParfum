<template>
  <Teleport to="body">
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none">
      <TransitionGroup
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-1"
      >
        <div
          v-for="t in toast.toasts.value"
          :key="t.id"
          class="pointer-events-auto flex items-center gap-3 px-5 py-3 min-w-[220px] max-w-[340px]"
          :class="t.type === 'success' ? 'bg-ink text-paper' : 'bg-paper border border-ink text-ink'"
        >
          <span
            class="shrink-0 font-mono text-[11px]"
            :class="t.type === 'success' ? 'text-accent' : 'text-accent-deep'"
          >/</span>
          <p class="font-mono text-[10px] uppercase tracking-[0.16em] leading-snug">
            {{ t.message }}
          </p>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
const toast = useToast()
</script>
