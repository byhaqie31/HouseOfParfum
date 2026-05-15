export type ToastItem = {
  id: number
  message: string
  type: 'success' | 'error'
}

const DURATION = 3500

export const useToast = () => {
  const toasts = useState<ToastItem[]>('hop:toasts', () => [])

  const show = (message: string, type: ToastItem['type']) => {
    if (!import.meta.client) return
    const id = Date.now()
    toasts.value = [...toasts.value, { id, message, type }]
    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, DURATION)
  }

  return {
    toasts,
    success: (message: string) => show(message, 'success'),
    error: (message: string) => show(message, 'error'),
  }
}
