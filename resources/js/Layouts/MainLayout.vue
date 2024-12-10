<script setup>
import { useForm, usePage, Head, Link, router } from '@inertiajs/vue3';
import { useMainStore } from "@/stores/main";
import { computed, useSlots, ref, reactive, watch } from "vue";

import Sidenav from "@/AppPartials/Sidenav/Index.vue";
import Configurator from "@/AppPartials/Configurator/Configurator.vue";
import Navbar from "@/AppPartials/Navbars/Navbar.vue";
import AppFooter from "@/AppPartials/Footers/Footer.vue";

const props = defineProps({
  profile: {
    type: Boolean,
    default: false
  }
});

const mainStore = useMainStore();

const navClasses = () => {

  return {
    "position-sticky blur shadow-blur mt-4 left-auto top-1 z-index-sticky": mainStore.isNavFixed,
    "position-absolute px-4 mx-0 w-100 z-index-2": mainStore
      .isAbsolute,
    "px-0 mx-4 mt-4": !mainStore.isAbsolute,
  };

}
</script>
<template>
  <Sidenav :custom_class="mainStore.mcolor" :class="[
    mainStore.isTransparent,
    mainStore.isRTL ? 'fixed-end' : 'fixed-start',
  ]" v-if="mainStore.showSidenav" />
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg"
    :style="mainStore.isRTL ? 'overflow-x: hidden' : ''">
    <!-- nav -->
    <navbar :style="profile ? 'z-index: 4000; margin-top: 20px;' : ''" :class="[navClasses]"
      :textWhite="mainStore.isAbsolute ? 'text-white opacity-8' : ''" v-if="mainStore.showNavbar" />
    <slot />
    <AppFooter v-show="mainStore.showFooter" />
    <!-- <Configurator :toggle="mainStore.toggleConfigurator()" :class="[
      mainStore.showConfig ? 'show' : '',
      mainStore.hideConfigButton ? 'd-none' : '',
    ]" /> -->
  </main>
</template>

