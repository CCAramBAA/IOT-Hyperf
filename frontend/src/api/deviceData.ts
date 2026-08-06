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

// 列表查询，可选按设备ID筛选
export function fetchDeviceData(deviceId?: string) {
  return request.get<ApiResponse<DeviceDataItem[]>>('/device/data', {
    params: deviceId ? { device_id: deviceId } : undefined,
  })
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
