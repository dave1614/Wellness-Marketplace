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

import { useMainStore } from '@/stores/main.js'

const props = defineProps({
  props_states: {
    type: Array,
  },
  props_cities: {
    type: Array,
  },
  state: {
    type: Number
  },
  city: {
    type: Number
  },
});

const mainStore = useMainStore();

const page = usePage()

const states = ref(props.props_states);
const cities = ref(props.props_cities);
const slide = ref(1);


const login_btn_hovered = ref(false);

const form = useForm({
  step: 1,
  name: null,
  user_name: null,
  email: null,
  // country: 1,
  phone: null,
  password: null,
  password_confirmation: null,
  business_name: null,
  business_email: null,
  business_phone: null,
  state: props.state,
  city: props.city,
})


const openSlide = (slide_num) => {
  slide.value = slide_num;
}

const submit = (step) => {
  if (!form.processing) {
    if(slide.value == 3){
      mainStore.toast = 'Submitting all your details...';
    }
    form.step = step;
    form.post(route('process_merchant_registration'), {
      preserveScroll: true,
      onSuccess: (page) => {

        var response = page.props.flash.data;
        if (response.success) {
          if(slide.value == 1){
            mainStore.toast = 'Proceeding to slide 2...';
            openSlide(2);
          }else if(slide.value == 2){
            mainStore.toast = 'Proceeding to slide 3...';
            openSlide(3);
          }
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

const loadNewCities = async () => {

  try {
    var state = form.state;
    Swal.fire({
      title: 'Please wait',
      html: `Loading.....`,
      icon: 'info',
      showConfirmButton: false,
      allowEscapeKey: false,
      allowOutsideClick: false,
    });
    
    let queryRoute = route('get_cities_for_state', state);

    const response = await axios.post(queryRoute);
    console.log(response)
    Swal.close()
    
    if (response.data.cities.length > 0 && response.data.city != 0) {
      cities.value = response.data.cities;
      form.city = response.data.city;

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


};

const slideLeft = () => {
  if(slide.value > 1){
    openSlide(slide.value - 1);
  }
}

const slideRight = () => {
  if(slide.value < 3){
    openSlide(slide.value + 1);
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

            <div class=" rounded-xl px-5 py-6 sm:col-span-6 text-secondary-700 bg-primary-100 sm:rounded-r-xl relative">
              
              <div @click="slideLeft" :class="slide == 1 ? 'cursor-not-allowed opacity-50' : ''" class=" absolute bg-secondary-200 left-0 p-2 top-[50%] sm:mx-5 rounded-full pb-[2px] cursor-pointer z-[300]">
                <font-awesome-icon class="text-lg sm:text-xl text-primary-50" icon="fa-solid fa-arrow-left" />
              </div>

              <div @click="slideRight" :class="slide == 3 ? 'cursor-not-allowed opacity-50' : ''" class=" absolute bg-secondary-200 right-0 p-2 top-[50%] sm:mx-5 rounded-full pb-[2px] cursor-pointer z-[300]">
                <font-awesome-icon class="text-lg sm:text-xl text-primary-50" icon="fa-solid fa-arrow-right" />
              </div>

              <div class="logo-icon mt-2 mb-3 text-center">
                <span class="font-bold text-2xl uppercase font-montserrat">Wellness</span>
              </div>


              <div class="pt-1 mt-6 sm:px-[65px]">

                

                <flash-messages />
                <form class="animate__animated animate__fadeInRight" @submit.prevent="submit(1)" :class="slide == 1 ? 'block' : 'hidden'">
                  <p class="float-right font-bold text-xs my-2">1/3</p>

                  <h5 class="text-center text-xl font-bold my-4">Personal Details</h5>
                  
                  <div class="">

                    <LoginInput v-model="form.name" :error="form.errors.name" type="text"
                      label="Full Name" id="name" class="" />

                    <LoginInput v-model="form.user_name" :error="form.errors.user_name" type="text"
                      label="Username" id="user_name" class="" />

                    <LoginInput v-model="form.email" :error="form.errors.email" type="email"
                      label="Email Address" id="email" class="" />
                    
                    <LoginInput v-model="form.phone" :error="form.errors.phone" type="number"
                      label="Phone Number" id="phone" class="" />

                  </div>

                  <!-- <div class="grid grid-cols-12 gap-6 mt-2 text-primary-600 text-sm">
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

                  </div> -->

                  <button name="login-btn" :class="form.processing ? 'opacity-80 cursor-not-allowed' : ''"
                    @mouseleave="login_btn_hovered = false" @mouseover="login_btn_hovered = true" type="submit"
                    class="login-button mt-6">
                    PROCEED
                    <img v-if="form.processing" class="inline-block w-7 h-6 float-right"
                      :src="login_btn_hovered ? FormLoaderDark : FormLoaderLight" alt="">
                  </button>

                  <!-- <div class="grid grid-cols-11 gap-2 mt-7">
                    <div class="col-span-2"></div>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <span class="col-span-1 text-xs text-secondary-200 text-center">or</span>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <div class="col-span-2"></div>
                  </div>

                  <div class="my-6 flex justify-center cursor-pointer hover:border-2 py-2 hover:border-primary-50 rounded-lg">
                    <img :src="GoogleIcon" alt="" class="w-6 inline-block">
                    <span class="text-sm inline-block ml-2 mt-1">Sign up with Google</span>
                  </div>


                  <p class="text-sm text-center my-3 font-semibold">Already registered? <Link :href="route('login')" class="login-checkbox-label mt-1 underline text-primary-600">Sign in</Link></p> -->



                </form>

                <form class="animate__animated animate__fadeInRight" @submit.prevent="submit(2)" :class="slide == 2 ? 'block' : 'hidden'">
                  <p class="float-right font-bold text-xs my-2">2/3</p>

                  <h5 class="text-center text-xl font-bold my-4">Merchant Details</h5>
                  
                  <div class="">

                    <LoginInput v-model="form.business_name" :error="form.errors.business_name" type="text"
                      label="Business Name" id="business_name" class="" />

                    <LoginInput v-model="form.business_email" :error="form.errors.business_email" type="email"
                      label="Business Email Address" id="business_email" class="" />
                    
                    <LoginInput v-model="form.business_phone" :error="form.errors.business_phone" type="number"
                      label="Business Phone Number" id="business_phone" class="" />

                    
                    <div class="mb-2 transition-all ease-linear duration-200">
                      <label class="login-form-label" for="state">Business State:</label>
                      <select @change="loadNewCities" class="p-3 py-2" id="state" v-model="form.state"
                        :class="form.errors.state ? 'login-input-error' : 'login-input'">
                        <option v-for="state in states" :value="state.id" :key="state.id" v-html="`${state.name}`"></option>
                      </select>
                      <div v-if="form.errors.state" class="login-form-error">{{ form.errors.state }}</div>
                    </div>
          
          
                    <div class="mb-2 transition-all ease-linear duration-200">
                      <label class="login-form-label" for="state">Business City:</label>
                      <div class="relative">
                        <select class="p-3 py-2" id="city" v-model="form.city"
                          :class="form.errors.city ? 'login-input-error' : 'login-input'">
                          <option v-for="city in cities" :value="city.id" :key="city.id" v-html="`${city.name}`"></option>
                        </select>

                      </div>
                      <div v-if="form.errors.city" class="login-form-error">{{ form.errors.city }}</div>
                    </div>

                  </div>

                  <!-- <div class="grid grid-cols-12 gap-6 mt-2 text-primary-600 text-sm">
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

                  </div> -->

                  <button name="login-btn" :class="form.processing ? 'opacity-80 cursor-not-allowed' : ''"
                    @mouseleave="login_btn_hovered = false" @mouseover="login_btn_hovered = true" type="submit"
                    class="login-button mt-6">
                    PROCEED
                    <img v-if="form.processing" class="inline-block w-7 h-6 float-right"
                      :src="login_btn_hovered ? FormLoaderDark : FormLoaderLight" alt="">
                  </button>

                  <!-- <div class="grid grid-cols-11 gap-2 mt-7">
                    <div class="col-span-2"></div>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <span class="col-span-1 text-xs text-secondary-200 text-center">or</span>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <div class="col-span-2"></div>
                  </div>

                  <div class="my-6 flex justify-center cursor-pointer hover:border-2 py-2 hover:border-primary-50 rounded-lg">
                    <img :src="GoogleIcon" alt="" class="w-6 inline-block">
                    <span class="text-sm inline-block ml-2 mt-1">Sign up with Google</span>
                  </div>


                  <p class="text-sm text-center my-3 font-semibold">Already registered? <Link :href="route('login')" class="login-checkbox-label mt-1 underline text-primary-600">Sign in</Link></p> -->



                </form>

                <form class="animate__animated animate__fadeInRight" @submit.prevent="submit(3)" :class="slide == 3 ? 'block' : 'hidden'">
                  <p class="float-right font-bold text-xs my-2">3/3</p>

                  <h5 class="text-center text-xl font-bold my-4">Authentication Details</h5>
                  
                  <div class="">

                    <LoginInput v-model="form.password" name="password" :error="form.errors.password" type="password"
                      label="Password" id="password" class="" />

                    <LoginInput v-model="form.password_confirmation" name="password_confirmation" :error="form.errors.password_confirmation" type="password"
                      label="Password Confirmation" id="password_confirmation" class="" />


                  </div>

                  <!-- <div class="grid grid-cols-12 gap-6 mt-2 text-primary-600 text-sm">
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

                  </div> -->

                  <button name="login-btn" :class="form.processing ? 'opacity-80 cursor-not-allowed' : ''"
                    @mouseleave="login_btn_hovered = false" @mouseover="login_btn_hovered = true" type="submit"
                    class="login-button mt-6">
                    SUBMIT
                    <img v-if="form.processing" class="inline-block w-7 h-6 float-right"
                      :src="login_btn_hovered ? FormLoaderDark : FormLoaderLight" alt="">
                  </button>

                  <!-- <div class="grid grid-cols-11 gap-2 mt-7">
                    <div class="col-span-2"></div>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <span class="col-span-1 text-xs text-secondary-200 text-center">or</span>
                    <div class="col-span-3 bg-secondary-100 h-[1px] mt-[8px]"></div>
                    <div class="col-span-2"></div>
                  </div>

                  <div class="my-6 flex justify-center cursor-pointer hover:border-2 py-2 hover:border-primary-50 rounded-lg">
                    <img :src="GoogleIcon" alt="" class="w-6 inline-block">
                    <span class="text-sm inline-block ml-2 mt-1">Sign up with Google</span>
                  </div>


                  <p class="text-sm text-center my-3 font-semibold">Already registered? <Link :href="route('login')" class="login-checkbox-label mt-1 underline text-primary-600">Sign in</Link></p> -->



                </form>

                <div class="flex justify-end mt-4">
                  <div @click="openSlide(1)" :class="slide == 1 ? 'bg-secondary-200' : 'bg-gray-300'" class="h-[3px]  inline-block w-2 mx-1 transition-all ease-linear duration-700 cursor-pointer"></div>
                  <div @click="openSlide(2)" :class="slide == 2 ? 'bg-secondary-200' : 'bg-gray-300'" class="h-[3px] bg-gray-300 inline-block w-2 mx-1 transition-all ease-linear duration-700 cursor-pointer"></div>
                  <div @click="openSlide(3)" :class="slide == 3 ? 'bg-secondary-200' : 'bg-gray-300'" class="h-[3px] bg-gray-300 inline-block w-2 mx-1 transition-all ease-linear duration-700 cursor-pointer"></div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>

    </SectionFullScreen>
  </LayoutGuest>
</template>
