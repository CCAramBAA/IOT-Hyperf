import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAppStore = defineStore('app', () => {
  const drawer = ref(true)
  const title = ref('IOT 物联网平台')

  function toggleDrawer() {
    drawer.value = !drawer.value
  }

  return {
    drawer,
    title,
    toggleDrawer,
  }
})
