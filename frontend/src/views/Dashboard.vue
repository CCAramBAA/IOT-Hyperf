<template>
  <div>
    <!-- 统计卡片 -->
    <v-row>
      <v-col cols="12" sm="6" md="3">
        <v-card color="primary" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">设备总数</div>
            <div class="text-h3 font-weight-bold mt-2">128</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="success" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">在线设备</div>
            <div class="text-h3 font-weight-bold mt-2">96</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="warning" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">今日数据</div>
            <div class="text-h3 font-weight-bold mt-2">1,234</div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="3">
        <v-card color="error" variant="elevated" height="100">
          <v-card-text class="text-white">
            <div class="text-h6">告警数量</div>
            <div class="text-h3 font-bold mt-2">5</div>
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
      </v-card-text>
    </v-card>
  </div>
</template>

<script setup lang="ts">
</script>
