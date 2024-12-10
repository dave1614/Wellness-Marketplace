<script setup>
import { useForm, usePage, Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { mdiAccount, mdiEmail, mdiFormTextboxPassword } from '@mdi/js'
import LayoutGuest from '@/Layouts/LayoutGuest.vue'
import SectionFullScreen from '@/Components/SectionFullScreen.vue'
import CardBox from '@/Components/CardBox.vue'
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import FormValidationErrors from '@/Components/FormValidationErrors.vue'
// import Logo from '@/Icons/onehealth_logo_icon.svg'
import Logo from '@/Icons/easybizu_logo.jpeg'
import GoogleIcon from '@/Icons/google_icon.svg'
import FacebookIcon from '@/Icons/facebook_icon.svg'
import AppleIcon from '@/Icons/apple_icon.svg'
import FacilityIcon from '@/Icons/facility_icon.svg'
import PatientIcon from '@/Icons/patient_icon.svg'
import LoginInput from '@/Components/LoginInput.vue'
import FormLoaderDark from '@/Loaders/form_loader_dark.gif'
import FormLoaderLight from '@/Loaders/form_loader_light.gif'
import FlashMessages from '@/Components/FlashMessages.vue'

import SecondLoginImage from '@/Images/second_login.jpg'

const props = defineProps({

});



const page = usePage()
const hasTermsAndPrivacyPolicyFeature = computed(() => page.props.jetstream?.hasTermsAndPrivacyPolicyFeature)



const login_btn_hovered = ref(false);

const form = useForm({
  login: '',
  password: '',
  remember_me: false,
})


const submit = () => {

  if (!form.processing) {

    form.post(route('login'), {
      preserveScroll: true,
    });
  }
}


</script>

<template>
  <LayoutGuest class="font-nunito">

    <Head title="Login to cosrosmos" />

    <SectionFullScreen v-slot="{ cardClass }" bg="" class="bg-gradient-to-r from-primary-300 to-primary-100">


      <div class="w-full mx-3 sm:w-10/12 my-5 rounded-[30px] shadow-lg bg-white">
        <div class="  rounded-xl">

          <div class="sm:grid sm:grid-cols-12 sm:gap-0">

            <!-- <div
              class="sm:col-span-6 sm:block hidden bg-login-left-background bg-cover bg-no-repeat bg-center rounded-[30px] min-h-[500px]"> -->
              <!-- <img :src="SecondLoginImage" alt="" class="w-full h-full rounded-[30px]"> -->
            <!-- </div> -->

            <div class="sm:col-span-6 sm:block hidden bg-gradient-to-tr from-primary-300 to-primary-200 rounded-l-xl ">

            </div>

            <div class="sm:px-[65px] rounded-xl px-5 py-6 sm:col-span-6 text-secondary-700 bg-primary-100 sm:rounded-r-xl">
              
              <div class="logo-icon mt-2 mb-3 text-center">
                <span class="text-secondary-200 font-bold text-2xl uppercase font-montserrat">Wellness</span>
              </div>


              <form @submit.prevent="submit" class="pt-1 mt-6">
                <flash-messages />
                <div class="">


                  <LoginInput v-model="form.login" :error="form.errors.login" type="text"
                    label="Username or email" id="login" class="" />


                  <LoginInput v-model="form.password" name="password" :error="form.errors.password" type="password"
                    label="Password" id="password" class="" />


                </div>

                <div class="grid grid-cols-12 gap-6 mt-2 text-primary-600 text-sm">
                  <div class=" px-1 col-span-6 ">
                    <input type="checkbox" name="remember_me" class="login-checkbox" id="terms"
                      v-model="form.remember_me" />
                    <label for="terms" class="login-checkbox-label inline-block mt-[5px] cursor-pointer">Remember me</label>


                  </div>
                  <div class=" mb-3 px-1 col-span-6">
                    <Link :href="route('password.request')"
                      class="login-checkbox-label mt-1 float-right underline">
                    Forgot Password?
                    </Link>
                  </div>

                </div>

                <button name="login-btn" :class="form.processing ? 'opacity-80 cursor-not-allowed' : ''"
                  @mouseleave="login_btn_hovered = false" @mouseover="login_btn_hovered = true" type="submit"
                  class="login-button mt-6">
                  Sign in
                  <img v-if="form.processing" class="inline-block w-7 h-6 float-right"
                    :src="login_btn_hovered ? FormLoaderDark : FormLoaderLight" alt="">
                </button>

                <div class="grid grid-cols-11 gap-2 mt-7">
                  <div class="col-span-2"></div>
                  <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                  <span class="col-span-1 text-xs text-secondary-200 text-center">or</span>
                  <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                  <div class="col-span-2"></div>
                </div>

                <div class="my-6 flex justify-center cursor-pointer hover:border-2 py-2 hover:border-primary-50 rounded-lg">
                  <img :src="GoogleIcon" alt="" class="w-6 inline-block">
                  <span class="text-sm inline-block ml-2 mt-1">Sign in with Google</span>
                </div>


                <p class="text-sm text-center my-3 font-semibold">Are you new? <Link @click="" href="#" class="login-checkbox-label mt-1 underline text-primary-600">Create an Account</Link></p>



              </form>
            </div>
          </div>


        </div>
      </div>

    </SectionFullScreen>
  </LayoutGuest>
</template>
