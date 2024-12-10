<script setup>
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main'
import { Head, usePage, Link, router, useForm } from '@inertiajs/vue3'
import {
  mdiAccountMultiple,
  mdiCartOutline,
  mdiChartTimelineVariant,
  mdiMonitorCellphone,
  mdiReload,
  mdiGithub,
  mdiChartPie,
  mdiBriefcaseAccount,
  mdiOfficeBuildingCog,
  mdiTableAccount,
  mdiEmailBox,
  mdiPhone,
  mdiMapMarker,
  mdiBookMarker,
} from '@mdi/js'
import * as chartConfig from '@/Components/Charts/chart.config.js'
import LineChart from '@/Components/Charts/LineChart.vue'
import SectionMain from '@/Components/SectionMain.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import CardBox from '@/Components/CardBox.vue'
import TableSampleClients from '@/Components/TableSampleClients.vue'
import NotificationBar from '@/Components/NotificationBar.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import BaseLevel from '@/Components/BaseLevel.vue'

import CardBoxTransaction from '@/Components/CardBoxTransaction.vue'
import CardBoxClient from '@/Components/CardBoxClient.vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import SectionBannerStarOnGitHub from '@/Components/SectionBannerStarOnGitHub.vue'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import FloatingActionButton from '@/Components/FloatingActionButton.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import FormLoaderDark from '@/Loaders/form_loader_dark.gif'
import FormLoaderLight from '@/Loaders/form_loader_light.gif'
import FlashMessages from '@/Components/FlashMessages.vue'





const props = defineProps({
  auth: {
    type: Object,
  },
  business: {
    type: Object,
  },




});

const mainStore = useMainStore();
const page = usePage();
const user = ref(props.auth.user);
const showMoreOptionsModal = ref(false);
const login_btn_hovered = ref(false);


const selectOptions = [
  { id: 1, label: 'Business development' },
  { id: 2, label: 'Marketing' },
  { id: 3, label: 'Sales' }
]

console.log(user.value);

onMounted(() => {

})



</script>

<template>
  <LayoutAuthenticated>
    <Head :title="`${business.name} business details`" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiOfficeBuildingCog" :title="`${business.name}`" main>
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

      <div class="min-h-screen">
        <div class="my-2 text-sm font-semibold ml-3">
          <span class="mx-1"><Link class="text-primary-500" :href="route('admin_or_merchant.businesses.index')">All Businesses</Link> >> </span>
          <span class="mx-1">{{ business.name }}</span>

        </div>
        <div class="sm:mx-4 mx-2 mt-[50px]">
          <CardBox class="mb-6 sm:w-7/12 mx-auto" >
            <h3 class="text-2xl mt-[10px]  mb-[20px] font-bold">Choose Option</h3>
            <ul class="divide-y-2 divide-gray-400 mt-[30px] px-2 font-semibold">

              <li @click="viewAllPendingPatientsPayments" class="listview-list">
                <span class="">View products under this business</span>
              </li>

              <li @click="collectPaymentsForReferrals" class="listview-list">
                <span class="">Manage this business</span>
              </li>
            </ul>
          </CardBox>
        </div>
      </div>

      <CardBoxModal v-model="showMoreOptionsModal" button="danger" buttonLabel="Close" :title="`More Options`">
        <ul class="divide-y-2 divide-gray-400 mt-[30px] px-2">

          <li @click="viewAllPendingPatientsPayments" class="listview-list">
            <span class="">Edit this business</span>
          </li>

          <li @click="collectPaymentsForReferrals" class="listview-list">
            <span class="">Delete This business</span>
          </li>
        </ul>
      </CardBoxModal>

    </SectionMain>

    <FloatingActionButton @click="router.visit(route('admin_or_merchant.businesses.edit', business.id))" :styles="'background: 9124a3;'" :title="'Edit this business'">

      <font-awesome-icon icon="fa-solid fa-pen" class="text-2xl text-white" />
    </FloatingActionButton>
  </LayoutAuthenticated>
</template>
