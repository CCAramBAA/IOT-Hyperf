import axios from 'axios'
import type { AxiosError } from 'axios'

export interface ApiResponse<T = unknown> {
  code: number
  msg: string
  data: T
}

// Vite dev 代理：/api -> http://localhost:9501，并去掉 /api 前缀
const request = axios.create({
  baseURL: '/api',
  timeout: 10000,
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
    return Promise.reject(new Error(body?.msg || error.message || '网络错误'))
  },
)

export default request
