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

    <!-- 工具栏 -->
    <v-card class="mb-4">
      <v-card-title class="d-flex align-center justify-space-between">
        <span>设备数据管理</span>
        <v-btn color="primary" prepend-icon="mdi-plus" @click="openAddDialog">
          新增数据
        </v-btn>
      </v-card-title>
      <v-card-text>
        <div class="d-flex align-center ga-2">
          <v-text-field
            v-model="search"
            prepend-inner-icon="mdi-magnify"
            label="按设备ID查询"
            variant="outlined"
            density="compact"
            hide-details
            class="max-w-64"
            @keyup.enter="loadData"
          ></v-text-field>
          <v-btn color="primary" variant="tonal" @click="loadData">查询</v-btn>
          <v-btn variant="text" @click="resetSearch">重置</v-btn>
        </div>
      </v-card-text>
    </v-card>

    <!-- 数据表格 -->
    <v-card>
      <v-data-table
        :headers="headers"
        :items="items"
        :loading="loading"
        :items-per-page="10"
        class="elevation-1"
      >
        <template #item.actions="{ item }">
          <v-btn icon="mdi-pencil" size="small" variant="text" @click="openEditDialog(item)"></v-btn>
          <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="deleteItem(item)"></v-btn>
        </template>
        <template #no-data>
          <span>暂无数据，点击右上角「新增数据」添加</span>
        </template>
      </v-data-table>
    </v-card>

    <!-- 新增/编辑对话框 -->
    <v-dialog v-model="dialogVisible" max-width="500px">
      <v-card>
        <v-card-title>{{ isEdit ? '编辑数据' : '新增数据' }}</v-card-title>
        <v-card-text>
          <v-form v-model="formValid">
            <v-text-field
              v-model="formData.device_id"
              label="设备ID"
              variant="outlined"
              :rules="[(v: string) => !!v || '设备ID不能为空']"
              class="mb-4"
            ></v-text-field>
            <v-text-field
              v-model.number="formData.temp"
              label="温度 (°C)"
              type="number"
              variant="outlined"
              class="mb-4"
            ></v-text-field>
            <v-text-field
              v-model.number="formData.humidity"
              label="湿度 (%)"
              type="number"
              variant="outlined"
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="dialogVisible = false">取消</v-btn>
          <v-btn color="primary" :disabled="!formValid || saving" @click="saveItem">
            {{ isEdit ? '保存' : '新增' }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- 删除确认对话框 -->
    <v-dialog v-model="deleteDialogVisible" max-width="400px">
      <v-card>
        <v-card-title>确认删除</v-card-title>
        <v-card-text>确定要删除这条数据吗？此操作不可恢复。</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="deleteDialogVisible = false">取消</v-btn>
          <v-btn color="error" :disabled="deleting" @click="confirmDelete">删除</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import {
  fetchDeviceData,
  createDeviceData,
  updateDeviceData,
  deleteDeviceData,
} from '@/api/deviceData'
import type { DeviceDataItem } from '@/api/deviceData'

// 表头
const headers = [
  { title: 'ID', key: 'id', width: '80px' },
  { title: '设备ID', key: 'device_id' },
  { title: '温度 (°C)', key: 'temp' },
  { title: '湿度 (%)', key: 'humidity' },
  { title: '上报时间', key: 'created_at' },
  { title: '操作', key: 'actions', width: '120px', sortable: false },
]

const items = ref<DeviceDataItem[]>([])
const loading = ref(false)
const saving = ref(false)
const deleting = ref(false)
const errorMsg = ref('')

const search = ref('')
const dialogVisible = ref(false)
const deleteDialogVisible = ref(false)
const isEdit = ref(false)
const formValid = ref(false)
const currentItem = ref<DeviceDataItem | null>(null)

const formData = ref({
  device_id: '',
  temp: null as number | null,
  humidity: null as number | null,
})

// 从后端加载列表
async function loadData() {
  loading.value = true
  errorMsg.value = ''
  try {
    const res = await fetchDeviceData(search.value.trim() || undefined)
    items.value = res.data.data
  } catch (e) {
    errorMsg.value = (e as Error).message || '加载数据失败'
  } finally {
    loading.value = false
  }
}

function resetSearch() {
  search.value = ''
  loadData()
}

onMounted(loadData)

// 新增
function openAddDialog() {
  isEdit.value = false
  formData.value = {
    device_id: '',
    temp: null,
    humidity: null,
  }
  dialogVisible.value = true
}

// 编辑
function openEditDialog(item: DeviceDataItem) {
  isEdit.value = true
  currentItem.value = item
  formData.value = {
    device_id: item.device_id,
    temp: item.temp,
    humidity: item.humidity,
  }
  dialogVisible.value = true
}

// 保存（新增/修改都走后端接口）
async function saveItem() {
  if (!formValid.value) return
  saving.value = true
  errorMsg.value = ''
  try {
    if (isEdit.value && currentItem.value) {
      await updateDeviceData(currentItem.value.id, { ...formData.value })
    } else {
      await createDeviceData({ ...formData.value })
    }
    dialogVisible.value = false
    await loadData()
  } catch (e) {
    errorMsg.value = (e as Error).message || '保存失败'
  } finally {
    saving.value = false
  }
}

// 删除
function deleteItem(item: DeviceDataItem) {
  currentItem.value = item
  deleteDialogVisible.value = true
}

async function confirmDelete() {
  if (!currentItem.value) return
  deleting.value = true
  errorMsg.value = ''
  try {
    await deleteDeviceData(currentItem.value.id)
    deleteDialogVisible.value = false
    await loadData()
  } catch (e) {
    errorMsg.value = (e as Error).message || '删除失败'
  } finally {
    deleting.value = false
  }
}
</script>
