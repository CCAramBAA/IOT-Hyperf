<template>
  <v-app>
    <!-- 顶部导航栏 -->
    <v-app-bar color="primary" density="comfortable">
      <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
      <v-app-bar-title>{{ currentTitle }}</v-app-bar-title>
      <v-spacer></v-spacer>
      <v-btn icon="mdi-bell-outline" variant="text"></v-btn>
      <v-btn icon="mdi-account-circle" variant="text"></v-btn>
    </v-app-bar>

    <!-- 侧边栏 -->
    <v-navigation-drawer v-model="drawer" color="grey-lighten-5">
      <v-list density="compact" nav>
        <v-list-item
          v-for="item in menuItems"
          :key="item.path"
          :to="item.path"
          :prepend-icon="item.icon"
          :title="item.title"
          router
        ></v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- 主内容区 -->
    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>

    <!-- 底部 -->
    <v-footer app class="text-center" height="40">
      <span>IOT 物联网平台 &copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAppStore } from '@/stores/app'

const appStore = useAppStore()
const route = useRoute()

const drawer = computed({
  get: () => appStore.drawer,
  set: (val: boolean) => (appStore.drawer = val),
})

const currentTitle = computed(() => {
  return (route.meta?.title as string) || 'IOT 物联网平台'
})

function toggleDrawer() {
  appStore.toggleDrawer()
}

const menuItems = [
  {
    path: '/dashboard',
    title: '仪表盘',
    icon: 'mdi-view-dashboard',
  },
  {
    path: '/device-data',
    title: '设备数据',
    icon: 'mdi-database',
  },
]
</script>
