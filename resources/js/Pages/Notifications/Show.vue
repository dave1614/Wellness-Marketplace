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
// import 'bootstrap/dist/js/bootstrap.min.js';




import {
  faHandPointer,
  faUsers,
  faCreditCard,
  faScrewdriverWrench,
} from "@fortawesome/free-solid-svg-icons";

import {
  
  mdiEmailNewsletter,
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
  notification: {
    type: Object,
    default: {},
  }
})

onMounted(() => {
  
});


const iconBackground = ref("bg-gradient-success");
const show_drop = ref(false);
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
        <Head :title="`Notification from ${notification.data.from} with subject '${notification.data.subject}'`" />
        <SectionMain class="">
          <SectionTitleLineWithButton :icon="mdiEmailNewsletter" :title="`${notification.data.subject}`" main>
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

          <CardBox>
            <h4 class="text-xl text-gray-500 font-semibold">From: {{ notification.data.from }}</h4>
           

            <div class="btn-group">
              <button type="button" class="bg-green-400 px-6 py-3  shadow-lg text-white font-bold rounded-l-md text-xs">To: ME</button>
              <button @click="show_drop = !show_drop" type="button" class="mt-[1px] bg-green-400 px-6 py-2 shadow-lg text-white font-bold rounded-r-md dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <font-awesome-icon icon="fa-solid fa-caret-down text-xs" />
              </button>
              <ul class="dropdown-menu " :class="show_drop ? 'block' : 'hidden'">
                <li><a class="dropdown-item" href="#"><b>from:</b> {{ notification.data.from }}</a></li>
                <li><a class="dropdown-item" href="#"><b>reply-to:</b>{{ notification.data.from }}</a></li>
                <li><a class="dropdown-item" href="#"><b>to:</b> {{ notification.data.to }}</a></li>
                <!-- <li><hr class="dropdown-divider"></li> -->
                <li><a class="dropdown-item " href="#"><b>date:</b> {{ notification.date }}</a></li>
                <li><a class="dropdown-item" href="#"><b>time:</b> {{ notification.time }}</a></li>
                <li><a class="dropdown-item" href="#"><b>status:</b> read</a></li>
                <li><a class="dropdown-item" href="#"><b>read at:</b> {{ notification.read_at_str }}</a></li>
              </ul>
            </div>

            <div class="my-2">
              <p class="text-sm">
                <p class="text-lg font-semibold">{{ notification.data.greeting }}, </p>

                <div class=" my-2" v-html="notification.data.first_message">
                  
                </div>

                <div class="my-3 mb-2">
                  <div v-for="(btn, index) in notification.data.action_button" class="" :key="index">
                    <BaseButton target="_blank" :href="btn[1]" type="button" :color="index == 0 ? 'bg-green-600' : index == 1 ? 'bg-blue-500' : 'bg-orange-500'" :label="btn[0]" class=" text-white" />
                  </div>
                </div>

                <p class="mt-5">
                  {{ notification.data.closing_message }}
                </p>
              </p>
            </div>
          </CardBox>
         
        </SectionMain>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
