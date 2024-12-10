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
  mdiOfficeBuildingMarker,
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



const props = defineProps({
  auth: {
    type: Object,
  },
  businesses: {
    type: Object,
    default: {},
  },
  search_value: {
    type: String,
  }
  
});

console.log(props.businesses)
const mainStore = useMainStore();
const page = usePage();
const user = ref(props.auth.user);
const showMoreOptionsModal = ref(false);

console.log(user.value);

const form = useForm({
  value: props.search_value,
});

onMounted(() => {
  
})

</script>

<template>
  <LayoutAuthenticated>
    <Head title="Manage Businesses" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiOfficeBuildingMarker" title="Manage Businesses" main>
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
        <div class="sm:mx-4 mx-2 mt-[50px]">
          <div v-if="businesses.data.length > 0" class="">
            <div class="mt-[10px] mb-[50px]">
              <form class="sm:w-8/12 sm:mx-auto" >
                <div class="grid grid-cols-12 gap-2">
                  <div class="col-span-8">
                    <input class="w-full rounded-full bg-gray-50 dark:bg-slate-500 dark:placeholder-slate-200" type="text" v-model="form.value" name="value" placeholder="Search....">
                  </div>

                  <div class="col-span-4">
                    <button class="rounded-full text-center bg-gradient-to-r from-primary-300 to-primary-400 text-white px-3 w-full py-2 font-semibold">
                      Submit
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="bg-white dark:bg-slate-700 rounded-lg shadow my-3 py-4 sm:px-6 px-3" v-for="(business, index) in businesses.data" :key="index">
              <div class="grid grid-cols-12 gap-6">
                <div class="sm:col-span-8 col-span-9">
                  <h4 class="text-lg font-semibold"><Link class="hover:underline" :href="route('admin_or_merchant.businesses.show', business.id)">{{ business.name }}</Link></h4>
                </div>
                <div class="sm:col-span-4 sm:block hidden">
                  <Link title="Edit this business" class="hover:underline" :href="route('admin_or_merchant.businesses.edit', business.id)">
                    <font-awesome-icon class="text-xl text-emerald-600" icon="fa-solid fa-pen-to-square" />
                  </Link>

                  <font-awesome-icon class="cursor-pointer text-xl text-red-600 mx-4" icon="fa-solid fa-trash-can"/>
                </div>

                <div class="sm:hidden col-span-3">
                  <font-awesome-icon title="Delete this business" @click="showMoreOptionsModal = true" class="cursor-pointer text-xl text-slate-600 dark:text-slate-400 ml-4" icon="fa-solid fa-ellipsis" />
                  
                </div>
              </div>
            </div>

            <div class="p-3 lg:px-6 border-t border-gray-100 mt-[35px]">
              <BaseLevel>
                <BaseButtons>
                  <BaseButton v-for="page in businesses.links" :key="page" :active="page.active" :label="page.label"
                    :color="page.active ? 'lightDark' : 'whiteDark'" small @click="router.visit(page.url)"
                    :disabled="page.url === null" />
                </BaseButtons>
                <small>Page {{ businesses.current_page }} of {{ businesses.last_page }}</small>
                <small>{{ businesses.total }} records</small><br>
              </BaseLevel>
            </div>
          </div>
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

    <FloatingActionButton @click="router.visit(route('merchant.businesses.create'))" :styles="'background: 9124a3;'" :title="'Add New Business'">

      <font-awesome-icon icon="fa-solid fa-plus" class="text-2xl text-white" />
    </FloatingActionButton>
  </LayoutAuthenticated>
</template>
