<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue';
import { useForm, usePage, Head, Link, router } from '@inertiajs/vue3';
import { useMainStore } from "@/stores/main";
import { computed, useSlots, ref, reactive, watch, onMounted } from "vue";

import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseLevel from "@/Components/BaseLevel.vue";
import BaseDivider from "@/Components/BaseDivider.vue";






import {
  faHandPointer,
  faUsers,
  faCreditCard,
  faScrewdriverWrench,
} from "@fortawesome/free-solid-svg-icons";

import {
  
  mdiBell,
  mdiCurrencyUsd,
  mdiClose,
  mdiClipboardText,
  mdiAccountCash,
  mdiImageMove,
  mdiClipboard,
  mdiRefresh,
  mdiViewListOutline,
  mdiGithub,

} from "@mdi/js";
import axios from 'axios';

const props = defineProps({
  notifications: {
    type: Object,
    default: {},
  }
})

onMounted(() => {
  
});


const iconBackground = ref("bg-gradient-success");

const lengthOptions = ref([
  10,
  20,
  50,
  100
]);



</script>

<template>
  

  <LayoutAuthenticated>
    <div class="py-4 container-fluid">
      <div class="row min-h-screen">
        <Head :title="`Your Notifications`" />
        <SectionMain class="">
          <SectionTitleLineWithButton :icon="mdiBell" :title="`Notifications`" main>
            <!-- <BaseButton
            href="https://github.com/justboil/admin-one-vue-tailwind"
            target="_blank"
            :icon="mdiGithub"
            label="Star on GitHub"
            color="contrast"
            rounded-full
            small
          /> -->
          </SectionTitleLineWithButton>

          <div class="">
            <div v-if="notifications.data.length > 0" class="">

              <div @click="router.visit(route('notification.show', noti.id))" v-for="(noti, index) in notifications.data" :class="noti.read_at == null ? 'bg-slate-400 ' : 'bg-white dark:bg-slate-600'" class="py-2 my-3 px-5 rounded-xl cursor-pointer shadow-lg" :key="index">
                <h5 class="text-lg font-semibold">{{ noti.data.subject }}</h5>

                <p class="text-sm font-bold mb-0">{{ noti.social_time }}</p>
              </div>

              <div class="p-3 lg:px-6 border-t border-gray-100 ">
                <BaseLevel>
                  <BaseButtons>
                    <BaseButton v-for="page in notifications.links" :key="page" :active="page.active" :label="page.label"
                      :color="page.active ? 'lightDark' : 'whiteDark'" small :href="page.url"
                      :disabled="page.url === null" />
                  </BaseButtons>
                  <small>Page {{ notifications.current_page }} of {{ notifications.last_page }}</small>
                  <small>{{ notifications.total }} total notifications</small><br>
                </BaseLevel>
              </div>
            </div>
          </div>
         
        </SectionMain>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
