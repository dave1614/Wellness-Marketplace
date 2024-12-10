import '../css/main.css'

import { createPinia } from 'pinia'
import axios from 'axios';
// import { useDarkModeStore } from '@/stores/darkMode.js'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import swal from 'sweetalert2';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret, faFloppyDisk, faDollarSign, faNairaSign, faMoneyBill1Wave, faEnvelope, faPhone, faArrowLeft, faUser, faLock, faHospital, faLocationPin, faBars, faBriefcase, faHouse, faUserTie, faArrowRight, faPlus, faUsers, faBell, faFileExport, faArrowDown, faCheck, faPenToSquare, faCheckToSlot, faPrint, faEdit, faLocationDot, faEnvelopesBulk, faCaretDown, faTrashCan, faEllipsis, faPen } from '@fortawesome/free-solid-svg-icons'

import { faFacebookF, faTwitter, faYoutube, faLinkedinIn, faFacebook, faSquareXTwitter, faInstagram } from '@fortawesome/free-brands-svg-icons'

/* add icons to the library */
library.add(faUserSecret, faFloppyDisk, faDollarSign, faNairaSign, faMoneyBill1Wave, faFacebookF, faTwitter, faYoutube, faLinkedinIn, faEnvelope, faPhone, faArrowLeft, faUser, faLock, faHospital, faLocationPin, faBars, faBriefcase, faHouse, faUserTie, faArrowRight, faPlus, faUsers, faBell, faFileExport, faArrowDown, faCheck, faPenToSquare, faCheckToSlot, faPrint, faEdit, faLocationDot, faFacebook, faSquareXTwitter, faInstagram, faEnvelopesBulk, faCaretDown, faTrashCan, faEllipsis, faPen)

window.Swal = swal;
window.axios = axios;

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

const pinia = createPinia()

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(pinia)
      .use(ZiggyVue, Ziggy)
      .component('font-awesome-icon', FontAwesomeIcon)
      .mount(el)
  },
  progress: {
    color: '#4B5563'
  }
})

// const darkModeStore = useDarkModeStore(pinia)

// if (
//   (!localStorage['darkMode'] && window.matchMedia('(prefers-color-scheme: dark)').matches) ||
//   localStorage['darkMode'] === '1'
// ) {
//   darkModeStore.set(true)
// }
