<script setup lang="ts">
import { familyOfTheHour } from '~/utils/wear'

definePageMeta({ layout: 'app', middleware: 'auth' })
useHead({ title: 'Your shelf' })

const wardrobe = useWardrobeStore()
const logStore = useLogStore()
const { worldFor } = useScentWorld()
const houseWorld = worldFor(() => familyOfTheHour())

function openBottle(id: string) { navigateTo(`/user/wardrobe/${id}`) }
</script>

<template>
  <div>
    <header class="mb-6 flex items-end justify-between gap-4">
      <div>
        <div class="kicker">Your vanity</div>
        <h1 class="fd mt-1" style="font-size: clamp(30px, 6vw, 44px); line-height: 1.05;">Your shelf</h1>
        <p v-if="wardrobe.count" class="fm mt-2" style="font-size: 12px; color: var(--color-ink-mute);">
          {{ wardrobe.count }} {{ wardrobe.count === 1 ? 'bottle' : 'bottles' }}
        </p>
      </div>
      <NuxtLink
        to="/user/wardrobe/add"
        class="hidden items-center gap-2 rounded-full px-5 py-2.5 sm:inline-flex"
        :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
      >
        <Icon name="lucide:plus" size="16" /><span class="fb" style="font-weight: 700;">Add a bottle</span>
      </NuxtLink>
    </header>

    <!-- populated shelf -->
    <section v-if="wardrobe.count">
      <p class="fb mb-4 hidden italic lg:block" style="font-size: 12px; color: var(--color-ink-mute);">Hover a bottle to log a wear. On a phone, swipe it left.</p>
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
        <ShelfCard
          v-for="item in wardrobe.items"
          :key="item.id"
          :item="item"
          @open="openBottle($event.id)"
          @log="logStore.start($event)"
        />
      </div>
    </section>

    <!-- empty state -->
    <section v-else class="mx-auto mt-16 max-w-md text-center">
      <div
        class="mx-auto flex h-52 w-52 items-center justify-center rounded-full border"
        style="border-color: var(--color-rule); background: var(--color-surface);"
      >
        <ScentFlacon :world="houseWorld" :size="96" />
      </div>
      <h2 class="fd mt-10" style="font-size: 30px;">Your shelf is bare.</h2>
      <p class="fb mt-3 italic" style="font-size: 15px; color: var(--color-ink-soft);">Begin with the bottle you reached for today.</p>
      <NuxtLink
        to="/user/wardrobe/add"
        class="mt-8 inline-flex items-center gap-2 rounded-full px-6 py-3"
        :style="{ background: houseWorld.gradient, color: houseWorld.onGrad }"
      >
        <span class="fb" style="font-weight: 700;">Add your first bottle</span>
        <Icon name="lucide:arrow-right" size="16" />
      </NuxtLink>
    </section>
  </div>
</template>
