import request from './request'
import type { ApiResponse } from './request'

export interface DeviceDataItem {
  id: number
  device_id: string
  temp: number | null
  humidity: number | null
  created_at: string
}

export interface DeviceDataPayload {
  device_id?: string
  temp?: number | null
  humidity?: number | null
}

export interface DeviceStats {
  device_count: number
  active_24h: number
  today_count: number
  total_count: number
  latest_time: string | null
}

export interface DeviceDataPage {
  list: DeviceDataItem[]
  total: number
  page: number
  page_size: number
}

// 列表查询：支持按设备ID筛选 + 分页
export function fetchDeviceData(deviceId?: string, page = 1, pageSize = 10) {
  return request.get<ApiResponse<DeviceDataPage>>('/device/data', {
    params: {
      page,
      page_size: pageSize,
      ...(deviceId ? { device_id: deviceId } : {}),
    },
  })
}

// 平台统计（仪表盘）
export function fetchDeviceStats() {
  return request.get<ApiResponse<DeviceStats>>('/device/stats')
}

// 新增
export function createDeviceData(payload: DeviceDataPayload) {
  return request.post<ApiResponse<DeviceDataItem>>('/device/data', payload)
}

// 修改
export function updateDeviceData(id: number, payload: DeviceDataPayload) {
  return request.put<ApiResponse<DeviceDataItem>>(`/device/data/${id}`, payload)
}

// 删除
export function deleteDeviceData(id: number) {
  return request.delete<ApiResponse<null>>(`/device/data/${id}`)
}
