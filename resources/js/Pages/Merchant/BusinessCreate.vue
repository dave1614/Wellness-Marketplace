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
  mdiOfficeBuildingPlus,
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





const props = defineProps({
  auth: {
    type: Object,
  },
  props_states: {
    type: Array,
  },
  props_cities: {
    type: Array,
  },
  
  
});

const mainStore = useMainStore();
const page = usePage();
const user = ref(props.auth.user);
const showMoreOptionsModal = ref(false);
const login_btn_hovered = ref(false);
const states = ref(props.props_states);
const cities = ref(props.props_cities);

const selectOptions = [
  { id: 1, label: 'Business development' },
  { id: 2, label: 'Marketing' },
  { id: 3, label: 'Sales' }
]

console.log(user.value);

const form = useForm({
  name: null,
  email: null,
  state: states.value[0],
  city: cities.value[0],
  phone: null,
});


onMounted(() => {
  
})


const fetchCitiesByState = async () => {
  try {
    var state = form.state.id;
    Swal.fire({
      title: 'Please wait',
      html: `Loading.....`,
      icon: 'info',
      showConfirmButton: false,
      allowEscapeKey: false,
      allowOutsideClick: false,
    });
    
    let queryRoute = route('get_cities_for_state_in_app', state);

    const response = await axios.post(queryRoute);
    console.log(response)
    Swal.close()
    
    if (response.data.cities.length > 0) {
      cities.value = response.data.cities;
      form.city = cities.value[0];

    } else {
      Swal.fire({
        title: 'Ooops',
        html: 'No Records!',
        icon: 'warning',


      });
    }
  } catch (error) {

    Swal.close()
    console.log(error);
   
    if (error.response) {
      // Request made but the server responded with an error
      var status = error.response.status;
      if (status == 419) {
        document.location.reload()
      }

    } else if (error.request) {
      // Request made but no response is received from the server.
    } else {
      // Error occured while setting up the request
    }

    Swal.fire({
      title: 'Ooops!',
      html: 'Something went wrong',
      icon: 'error',


    });
  }
}


const submit = () => {
  if (!form.processing) {
    form.post(route('merchant.businesses.store'), {
      preserveScroll: true,
      onSuccess: (page) => {

        var response = page.props.flash.data;
        if (response.success) {
          Swal.fire({
            title: `Business Created Successfully`,
            html: `You Have Successfully Created A New Business Called <em class='text-primary-600'>${form.name}</em>`,
            icon: 'success',
            allowEscapeKey: false,
            allowOutsideClick: false,
          }).then((result) => {
            if (result.isConfirmed) {
              router.visit(route('admin_or_merchant.businesses.index'));
            }
          });
        }else {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong.',
          })
        }
      }, onError: (errors) => {
        var size = Object.keys(errors).length;
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: `There are ${size} form errors. Please fix them`,
        })
      },
    });
  }
}

</script>

<template>
  <LayoutAuthenticated>
    <Head title="Create New Business" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiOfficeBuildingPlus" title="Create New Business" main>
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
          <span class="mx-1">Create New Business</span>
        </div>
        <div class="sm:mx-4 mx-2 mt-[50px]">
          <CardBox class="mb-6 sm:w-7/12 mx-auto" @submit.prevent="submit" isForm>

            <h4 class="sm:text-2xl text-xl font-semibold text-center mb-[20px]">Enter your new business details</h4>
            <div class="">

              <FormField class="w-full my-2" label="Name">
                <FormControl class="w-full" v-model="form.name" type="text" :error="form.errors.name" :icon="mdiTableAccount " />
              
              </FormField>

              <FormField class="w-full my-2" label="Email Address">
                <FormControl class="w-full" v-model="form.email" type="email" :error="form.errors.email" :icon="mdiEmailBox" />
              
              </FormField>

              <FormField class="w-full my-2" label="State">
                <FormControl  @change="fetchCitiesByState" class="w-full" :options="states" v-model="form.state" :error="form.errors.state" :icon="mdiMapMarker" />
              
              </FormField>

              <FormField class="w-full my-2" label="City">
                <FormControl class="w-full" :options="cities" v-model="form.city" :error="form.errors.city" :icon="mdiBookMarker" />
              
              </FormField>

              <FormField class="w-full my-2" label="Phone Number">
                <FormControl class="w-full" v-model="form.phone" type="tel" :error="form.errors.phone" :icon="mdiPhone " />
              
              </FormField>

              
            </div>

            
            <button name="submit-btn" :class="form.processing ? 'opacity-80 cursor-not-allowed' : ''"
                    @mouseleave="login_btn_hovered = false" @mouseover="login_btn_hovered = true" type="submit"
                    class="submit-button mt-6">
              SUBMIT
              <img v-if="form.processing" class="inline-block w-7 h-6 float-right"
                :src="login_btn_hovered ? FormLoaderDark : FormLoaderLight" alt="">
            </button>
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

    <FloatingActionButton @click="router.visit(route('admin_or_merchant.businesses.index'))" :styles="'background: 9124a3;'" :title="'Go Back'">

      <font-awesome-icon icon="fa-solid fa-arrow-left" class="text-2xl text-white" />
    </FloatingActionButton>
  </LayoutAuthenticated>
</template>
