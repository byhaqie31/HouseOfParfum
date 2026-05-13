<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
useHead({
  titleTemplate: title => (title ? `${title} · House of Parfum` : 'House of Parfum'),
  meta: [
    { name: 'description', content: 'A personal companion for the fragrances you wear, and the ones you remember.' },
    { name: 'theme-color', content: '#f9f7f0' },
  ],
  link: [
    { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
    { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
    {
      rel: 'stylesheet',
      href: 'https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;0,6..96,600;1,6..96,400;1,6..96,500&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap',
    },

    // Favicons (bundle in /public/favicon/)
    { rel: 'icon', type: 'image/svg+xml', href: '/favicon/favicon.svg' },
    { rel: 'icon', type: 'image/png', sizes: '96x96', href: '/favicon/favicon-96x96.png' },
    { rel: 'shortcut icon', href: '/favicon/favicon.ico' },
    { rel: 'apple-touch-icon', sizes: '180x180', href: '/favicon/apple-touch-icon.png' },
    { rel: 'manifest', href: '/favicon/site.webmanifest' },
  ],
})
</script>
