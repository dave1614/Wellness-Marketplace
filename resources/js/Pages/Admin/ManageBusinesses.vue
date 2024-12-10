<script setup>
import { computed, ref, onMounted } from 'vue'
import { useMainStore } from '@/stores/main'
import { useAxios } from '@/composables/axios.js'
import { Head, usePage, Link, router } from '@inertiajs/vue3'
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
  mdiClose,
  mdiTrashCan,
  mdiPencil
} from '@mdi/js'
import * as chartConfig from '@/Components/Charts/chart.config.js'
import LineChart from '@/Components/Charts/LineChart.vue'
import SectionMain from '@/Components/SectionMain.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import CardBox from '@/Components/CardBox.vue'
import TableSampleClients from '@/Components/TableSampleClients.vue'
import NotificationBar from '@/Components/NotificationBar.vue'
import BaseButton from '@/Components/BaseButton.vue'
import CardBoxTransaction from '@/Components/CardBoxTransaction.vue'
import CardBoxClient from '@/Components/CardBoxClient.vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import SectionBannerStarOnGitHub from '@/Components/SectionBannerStarOnGitHub.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'



const props = defineProps({
  auth: {
    type: Object,
  },
  merchant_username: {
    type:  String
  }

});


const mainStore = useMainStore();
const page = usePage();
const user = ref(props.auth.user);
const current_page = ref(0);
const lengthOptions = ref([
  10,
  20,
  50,
  100
]);

console.log(user.value);

const businesses = ref({});

const businesses_props = ref({
  'page': 1,
  'length': 10,

  'merchant_username': props.merchant_username,
  'merchant_fullname': null,

  'name': null,
  'email': null,
  'phone': null,
  'state': null,
  'city': null,

  'date': null,
  'start_date': null,
  'end_date': null,
})


const openPage = (page) => {
  current_page.value = page
}

onMounted(() => {
  loadBusinesses();
})

const clearFilterFormSubTests = () => {
  businesses_props.value.page = 1;
  businesses_props.value.length = 10;

  businesses_props.value.merchant_username = null;
  businesses_props.value.merchant_fullname = null;

  businesses_props.value.name = null;
  businesses_props.value.email = null;
  businesses_props.value.phone = null;
  businesses_props.value.state = null;
  businesses_props.value.city = null;

  businesses_props.value.date = null;
  businesses_props.value.start_date = null;
  businesses_props.value.end_date = null;
}

// const loadBusinesses = async (url = null) => {
//   try {

//     console.log(url)
//     if (url != null) {
//       var urlParams = new URLSearchParams(url);
//       console.log(urlParams)
//       var page = urlParams.get('page');
//       businesses_props.value.page = page
//       console.log(page)
//     }

//     // return

//     Swal.fire({
//       title: 'Please wait',
//       html: 'Loading Businesses.....',
//       icon: 'info',
//       showConfirmButton: false,
//       allowEscapeKey: false,
//       allowOutsideClick: false,
//     });


//     let queryRoute = route('admin.businesses.load_all');


//     const response = await axios.post(queryRoute, null, { params: businesses_props.value });

//     console.log(response)
//     Swal.close()

//     businesses.value = response.data;
//     openPage(1);

//   } catch (error) {

//     Swal.close()
//     console.log(error);

//     if (error.response) {
//       // Request made but the server responded with an error
//       var status = error.response.status;
//       if (status == 419) {
//         document.location.reload()
//       }

//     } else if (error.request) {
//       // Request made but no response is received from the server.
//     } else {
//       // Error occured while setting up the request
//     }

//     Swal.fire({
//       title: 'Ooops!',
//       html: 'Something went wrong',
//       icon: 'error',


//     });
//   }


// };

const loadBusinesses = async (url = null) => {

  if (url != null) {
    var urlParams = new URLSearchParams(url);
    var page = urlParams.get('page');
    businesses_props.value.page = page
  }

  let queryRoute = route('admin.businesses.load_all');

  const { response, error, loading  } = await useAxios(queryRoute, 'Loading Businesses.....', businesses_props.value);

  if (response?.value?.data) {
    businesses.value = response.value.data;
    openPage(1);
  }

};

</script>

<template>
  <LayoutAuthenticated>
    <Head title="Manage Businesses" />
    <SectionMain class="">
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

        <div v-if="current_page == 1" class="mt-[30px]">
          <CardBox >
            <h4 class="my-3 font-bold text-2xl" v-html="`All Registered Businesses`"></h4>

            <CardBox isForm @submit.prevent="loadBusinesses" class="">
              <div class="sm:grid sm:grid-cols-12 sm:gap-6">
                <FormField class="sm:col-span-4" label="Length">
                  <FormControl v-model="businesses_props.length" :options="lengthOptions" />
                </FormField>



                <FormField class="sm:col-span-4" label="Merchant Username">
                  <FormControl v-model="businesses_props.merchant_username" type="text" />
                </FormField>

                <FormField class="sm:col-span-4" label="Merchant Name">
                  <FormControl v-model="businesses_props.merchant_fullname" type="text" />
                </FormField>


                <FormField class="sm:col-span-4" label="Business Name">
                  <FormControl v-model="businesses_props.name" type="text" />
                </FormField>

                <FormField class="sm:col-span-4" label="Business Email">
                  <FormControl v-model="businesses_props.email" type="text" />
                </FormField>

                <FormField class="sm:col-span-4" label="Business Phone">
                  <FormControl v-model="businesses_props.phone" type="number" />
                </FormField>

                <FormField class="sm:col-span-4" label="State">
                  <FormControl v-model="businesses_props.state" type="text" />
                </FormField>

                <FormField class="sm:col-span-4" label="City">
                  <FormControl v-model="businesses_props.city" type="text" />
                </FormField>

                <FormField class="sm:col-span-4" label="Date Created">
                  <FormControl v-model="businesses_props.date" type="date" />
                </FormField>

                <FormField class="sm:col-span-4" label="Start Date">
                  <FormControl v-model="businesses_props.start_date" type="date" />
                </FormField>

                <FormField class="sm:col-span-4" label="End Date">
                  <FormControl v-model="businesses_props.end_date" type="date" />
                </FormField>



              </div>
              <BaseButtons class="mt-5 mb-2">
                <BaseButton type="submit" color="info" label="Filter" class="px-5 mb-0" />

                <BaseButton @click="clearFilterFormSubTests" type="reset" color="info" outline label="Clear" :icon="mdiClose"
                  class="px-5 mb-0" />
              </BaseButtons>
              <BaseDivider />
            </CardBox>

            <CardBox class="mb-4" has-table>

              <div v-if="businesses.data.length > 0" class="">
                <div class="table-responsive px-6">
                  <table class="table align-items-center justify-content-center mb-0 table-striped">
                    <thead class="text-xs table-dark">
                      <tr>
                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                            #
                        </th>


                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Merchant Username
                        </th>
                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Merchant Name
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Business Name
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Business Email
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Business Phone
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          State
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          City
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Date Created
                        </th>

                        <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                          Actions
                        </th>


                      </tr>
                    </thead>


                    <tbody class="text-sm">
                      <tr  v-for="(row, index) in businesses.data" :key="row.id">
                        <td v-html="`${(index + 1) + ((businesses.current_page - 1) * businesses_props.length)}.`"></td>


                        <td>
                          <span class="lowercase">{{ row.merchant.user_name }}</span>
                        </td>

                        <td>
                          <span class="capitalize">{{ row.merchant.name }}</span>
                        </td>


                        <td>
                          <Link :href="route('admin_or_merchant.businesses.show', row.id)" class="capitalize">{{ row.name }}</Link>
                        </td>

                        <td>
                          <span class="lowercase">{{ row.email }}</span>
                        </td>

                        <td>
                          <span class="lowercase">{{ row.phone }}</span>
                        </td>

                        <td>
                          <span class="capitalize">{{ row.state.name }}</span>
                        </td>

                        <td>
                          <span class="capitalize">{{ row.city.name }}</span>
                        </td>


                        <td>
                          <span class="capitalize">{{ mainStore.formatDate(row.created_at) }}</span>
                        </td>

                        <td class="before:hidden lg:w-1 whitespace-nowrap">
                          <BaseButtons type="justify-start lg:justify-end" no-wrap>
                            <BaseButton title="Edit this business" color="info" :icon="mdiPencil" small
                              :href="route('admin_or_merchant.businesses.edit', row.id)" />
                            <BaseButton title="Delete this business" color="danger" :icon="mdiTrashCan" small @click="isModalDangerActive = true" />
                          </BaseButtons>
                        </td>

                      </tr>

                    </tbody>
                  </table>
                </div>
                <div class="p-3 lg:px-6 border-t border-gray-100 ">
                  <BaseLevel>
                    <BaseButtons>
                      <BaseButton v-for="page in businesses.links" :key="page" :active="page.active" :label="page.label"
                        :color="page.active ? 'lightDark' : 'whiteDark'" small @click="loadBusinesses(page.url)"
                        :disabled="page.url === null" />
                    </BaseButtons>
                    <small>Page {{ businesses.current_page }} of {{ businesses.last_page }}</small>
                    <small>{{ businesses.total }} total records</small><br>
                  </BaseLevel>
                </div>
              </div>

            </CardBox>



          </CardBox>


        </div>
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
