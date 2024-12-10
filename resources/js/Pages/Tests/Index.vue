<script setup>
import { computed, ref, onMounted, onUnmounted  } from 'vue'
import { useMainStore } from '@/stores/main'
import { useMouse } from '@/composables/mouse.js'
import { useFetch } from '@/composables/fetch.js'
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

});


const mainStore = useMainStore();
const page = usePage();
const user = ref(props.auth.user);
const current_page = ref(0);

const {x, y} = useMouse();
const { data, error } = useFetch(route('package_json'));
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

  'merchant_username': null,
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
  loadBusinesses()
})


const loadBusinesses = async (url = null) => {

  if (url != null) {
    var urlParams = new URLSearchParams(url);
    console.log(urlParams)
    var page = urlParams.get('page');
    businesses_props.value.page = page
    console.log(page)
  }

  let queryRoute = route('admin.businesses.load_all');

  var { response, error } = await useAxios(queryRoute, 'Loading Businesses.....', businesses_props.value);

  console.log(response.value)
  if(response.value != null){
    businesses.value = response.data;
    openPage(1);
  }




};

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
        <p>Mouse position is at: {{ x }}, {{ y }}</p>

        <div v-if="error">Oops! Error encountered: {{ error.message }}</div>
        <div v-else-if="data">
            Data loaded:
            <pre>{{ data }}</pre>
        </div>
        <div v-else>Loading...</div>

      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
