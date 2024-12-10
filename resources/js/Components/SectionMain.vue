<script setup>
import { containerMaxW } from '@/config.js'
import { useMainStore } from '@/stores/main.js'

const mainStore = useMainStore();
</script>

<template>
  <section class="p-6" :class="containerMaxW">
    <slot />
    <div :class="mainStore.toast != '' ? 'block' : 'hidden'" class="toast">
      <p class="text-sm text-center">{{ mainStore.toast }}</p>
    </div>
  </section>
</template>
