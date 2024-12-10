<script setup>
import { computed } from 'vue'
import { useDarkModeStore } from '@/stores/darkMode.js'
import { useMainStore } from '@/stores/main.js'
import { gradientBgPurplePink, gradientBgDark, gradientBgPinkRed } from '@/colors.js'

const props = defineProps({
  bg: {
    type: String,
    required: true,
    validator: (value) => ['purplePink', 'pinkRed'].includes(value)
  }
})

const mainStore = useMainStore();

const colorClass = computed(() => {
  if (useDarkModeStore().isEnabled) {
    return gradientBgDark
  }

  switch (props.bg) {
    case 'purplePink':
      return gradientBgPurplePink
    case 'pinkRed':
      return gradientBgPinkRed
  }

  return ''
})
</script>

<template>
  <div class="flex min-h-screen items-center justify-center relative" :class="colorClass">
    <slot card-class="w-11/12 md:w-7/12 lg:w-6/12 xl:w-4/12 shadow-2xl" />
    <div :class="mainStore.toast != '' ? 'block' : 'hidden'" class="toast">
      <p class="text-sm text-center">{{ mainStore.toast }}</p>
    </div>
  </div>
</template>
