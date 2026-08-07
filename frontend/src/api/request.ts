import axios from 'axios'
import type { AxiosError } from 'axios'

export interface ApiResponse<T = unknown> {
  code: number
  msg: string
  data: T
}

const TOKEN_KEY = 'iot_token'

export function getToken(): string | null {
  return localStorage.getItem(TOKEN_KEY)
}

export function setToken(token: string) {
  localStorage.setItem(TOKEN_KEY, token)
}

export function clearToken() {
  localStorage.removeItem(TOKEN_KEY)
}

// Vite dev 代理：/api -> http://localhost:9501，并去掉 /api 前缀
const request = axios.create({
  baseURL: '/api',
  timeout: 10000,
})

// 请求头自动带上登录令牌
request.interceptors.request.use((config) => {
  const token = getToken()
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// 统一处理后端 { code, msg, data } 格式：code !== 200 时抛出异常
request.interceptors.response.use(
  (response) => {
    const body = response.data as ApiResponse
    if (body.code !== 200) {
      return Promise.reject(new Error(body.msg || '请求失败'))
    }
    return response
  },
  (error: AxiosError) => {
    const body = error.response?.data as ApiResponse | undefined
    // 登录失效：清除令牌并回到登录页
    if (error.response?.status === 401 || body?.code === 401) {
      clearToken()
      if (window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }
    return Promise.reject(new Error(body?.msg || error.message || '网络错误'))
  },
)

export default request
