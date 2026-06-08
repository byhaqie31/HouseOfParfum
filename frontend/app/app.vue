<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
  <AppToast />
</template>

<script setup lang="ts">
useHead({
  titleTemplate: (title: string | undefined) =>
    title ? `${title} · House of Parfum` : 'House of Parfum',
  meta: [
    { name: 'description', content: 'A personal companion for the fragrances you wear, and the ones you remember.' },
    { name: 'theme-color', content: '#f7f5ef' },
  ],
  link: [
    { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
    { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap',
    },

    // Favicons (bundle in /public/favicon/)
    { rel: 'icon', type: 'image/svg+xml', href: '/favicon/favicon.svg' },
    { rel: 'icon', type: 'image/png', sizes: '96x96', href: '/favicon/favicon-96x96.png' },
    { rel: 'shortcut icon', href: '/favicon/favicon.ico' },
    { rel: 'apple-touch-icon', sizes: '180x180', href: '/favicon/apple-touch-icon.png' },
    { rel: 'manifest', href: '/favicon/site.webmanifest' },
  ],
  script: [
    {
      // No-flash canvas mode: apply the persisted mode to <html> before paint
      // so dark-mode users never see a light flash on load.
      key: 'canvas-mode',
      innerHTML:
        "(function(){try{var m=localStorage.getItem('hop.mode');if(m==='dark'||m==='light'){document.documentElement.dataset.mode=m;}}catch(e){}})();",
      tagPosition: 'head',
    },
  ],
})
</script>
