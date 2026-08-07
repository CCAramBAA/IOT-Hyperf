<template>
  <v-app>
    <v-main class="d-flex align-center justify-center" style="min-height: 100vh; background: #f5f5f5">
      <v-card class="pa-6" width="420">
        <v-card-title class="text-h5 text-center">IOT 物联网平台</v-card-title>
        <v-card-subtitle class="text-center mb-4">管理端登录</v-card-subtitle>
        <v-card-text>
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
          <v-form v-model="formValid" @submit.prevent="handleLogin">
            <v-text-field
              v-model="username"
              label="用户名"
              variant="outlined"
              class="mb-4"
              :rules="[(v: string) => !!v || '请输入用户名']"
            ></v-text-field>
            <v-text-field
              v-model="password"
              label="密码"
              type="password"
              variant="outlined"
              class="mb-4"
              :rules="[(v: string) => !!v || '请输入密码']"
              @keyup.enter="handleLogin"
            ></v-text-field>
            <v-btn
              color="primary"
              block
              size="large"
              :disabled="!formValid || loading"
              @click="handleLogin"
            >
              登录
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login } from '@/api/auth'
import { setToken } from '@/api/request'

const router = useRouter()

const username = ref('')
const password = ref('')
const formValid = ref(false)
const loading = ref(false)
const errorMsg = ref('')

async function handleLogin() {
  if (!formValid.value) return
  loading.value = true
  errorMsg.value = ''
  try {
    const res = await login(username.value.trim(), password.value)
    setToken(res.data.data.token)
    router.push('/dashboard')
  } catch (e) {
    errorMsg.value = (e as Error).message || '登录失败'
  } finally {
    loading.value = false
  }
}
</script>
