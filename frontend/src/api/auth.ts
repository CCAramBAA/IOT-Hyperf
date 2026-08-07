import request from './request'
import type { ApiResponse } from './request'

export interface LoginResult {
  token: string
  username: string
}

// 登录，成功后返回 JWT
export function login(username: string, password: string) {
  return request.post<ApiResponse<LoginResult>>('/auth/login', { username, password })
}
