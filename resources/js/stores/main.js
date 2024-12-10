import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'
import axios from 'axios'

export const useMainStore = defineStore('main', () => {

  const userName = ref('John Doe')
  const userEmail = ref('doe.doe.doe@example.com')
  const toast = ref("");

  const userAvatar = computed(
    () =>
      `https://api.dicebear.com/7.x/avataaars/svg?seed=${userEmail.value.replace(
        /[^a-z0-9]+/gi,
        '-'
      )}`
  )

  const isFieldFocusRegistered = ref(false)

  const clients = ref([])
  const history = ref([])

  watch(toast, toastFunction);

  function formatNumbers(num) {
    return num < 10 ? "0" + num : num;
  }

  function formatDate(date, format = true) {
    // console.log(date)
    if (date == null) { return "" }
    date = new Date(date);

    // console.log(mnth);
    let day = formatNumbers(date.getDate());
    // let month = formatNumbers(date.getMonth() + 1);
    let month = date.toLocaleString('default', { month: 'short' });
    let year = date.getFullYear()

    let hour = formatNumbers(date.getHours() - 1);
    let minute = formatNumbers(date.getMinutes());
    let seconds = formatNumbers(date.getSeconds());
    let hour_minute = date.toLocaleString('default', { hour: 'numeric', minute: 'numeric', hour12: true })
    // console.log(date)
    return format ? day + ' ' + month + ' ' + year + ' ' + hour_minute : day + ' ' + month + ' ' + year;
  }

  function toastFunction (){
    if(toast.value != ""){
      setTimeout(() => {
        toast.value = "";
      }, 5000);
    }
  }

  function setUser(payload) {
    if (payload.name) {
      userName.value = payload.name
    }
    if (payload.email) {
      userEmail.value = payload.email
    }
  }

  function fetchSampleClients() {
    axios
      .get(`data-sources/clients.json?v=3`)
      .then((result) => {
        clients.value = result?.data?.data
      })
      .catch((error) => {
        alert(error.message)
      })
  }

  function fetchSampleHistory() {
    axios
      .get(`data-sources/history.json`)
      .then((result) => {
        history.value = result?.data?.data
      })
      .catch((error) => {
        alert(error.message)
      })
  }

  return {
    toast,
    userName,
    userEmail,
    userAvatar,
    isFieldFocusRegistered,
    clients,
    history,
    setUser,
    fetchSampleClients,
    fetchSampleHistory,
    formatDate,
    formatNumbers
  }
})
