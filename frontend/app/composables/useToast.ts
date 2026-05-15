export const useToast = () => {
  const store = useToastStore()
  return {
    toasts: computed(() => store.items),
    success: (message: string) => store.success(message),
    error: (message: string) => store.error(message),
  }
}
