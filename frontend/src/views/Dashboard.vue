<template>
  <div>
    <!-- 错误提示 -->
    <v-alert
      v-if="errorMsg"
      type="error"
      density="compact"
      class="mb-4"
      closable
      @click:close="errorMsg = ''"
    >
      {{ errorMsg }}
    </v-alert>

    <!-- 统计卡片 -->
    <v-row>
      <v-col cols="12" sm="6" md="3">
        <v-card color="primary" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">设备总数</div>
            <div class="text-h3 font-weight-bold mt-2">{{ stats?.device_count ?? '--' }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="success" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">近24h活跃设备</div>
            <div class="text-h3 font-weight-bold mt-2">{{ stats?.active_24h ?? '--' }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="warning" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">今日数据</div>
            <div class="text-h3 font-weight-bold mt-2">{{ stats?.today_count ?? '--' }}</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="error" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">数据总量</div>
            <div class="text-h3 font-bold mt-2">{{ stats?.total_count ?? '--' }}</div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- 欢迎卡片 -->
    <v-card class="mt-6">
      <v-card-title>欢迎使用 IOT 物联网平台</v-card-title>
      <v-card-text>
        <p>这是一个基于 Vue 3 + Vuetify 3 构建的物联网管理平台。</p>
        <p class="mt-2">左侧菜单可以导航到各个功能模块：</p>
        <ul class="mt-2 ml-4">
          <li><strong>仪表盘</strong> - 查看平台整体数据概览</li>
          <li><strong>设备数据</strong> - 管理设备上报的数据（增删改查）</li>
        </ul>
        <p class="mt-4 text-medium-emphasis">
          说明：「近24h活跃设备」指最近 24 小时内有上报数据的设备数，是真实在线的近似指标；
          等设备上下线机制完善后可替换为真正的在线状态。
        </p>
      </v-card-text>
    </v-card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { fetchDeviceStats } from '@/api/deviceData'
import type { DeviceStats } from '@/api/deviceData'

const stats = ref<DeviceStats | null>(null)
const errorMsg = ref('')

async function loadStats() {
  errorMsg.value = ''
  try {
    const res = await fetchDeviceStats()
    stats.value = res.data.data
  } catch (e) {
    errorMsg.value = (e as Error).message || '统计加载失败'
  }
}

onMounted(loadStats)
</script>
