import { toValue, type MaybeRefOrGetter } from 'vue'
import { scentWorld, hashSeed, type ScentWorld } from '~/utils/scent'

/**
 * Reactive bridge to the scent-colour system: produces a ScentWorld that
 * tracks the current canvas mode (light / after dark).
 *
 *   const world = worldFor(() => item.family, () => item.id + item.name)
 *   <div :style="{ background: world.gradient }">
 *
 * `seedSource` may be a number (e.g. 0.5 for a standalone family swatch) or a
 * string (e.g. id + name), which is hashed deterministically per the handoff.
 */
export function useScentWorld() {
  const canvas = useCanvasStore()
  const isDark = computed(() => canvas.isDark)

  function worldFor(
    family: MaybeRefOrGetter<string | null | undefined>,
    seedSource: MaybeRefOrGetter<string | number> = 0.5,
  ) {
    return computed<ScentWorld>(() => {
      const fam = toValue(family) || 'woody'
      const s = toValue(seedSource)
      const seed = typeof s === 'number' ? s : hashSeed(s)
      return scentWorld(fam, seed, isDark.value)
    })
  }

  return { isDark, worldFor }
}
