# IOT 前端管理平台

基于 Vue 3 + Vuetify 3 构建的物联网管理后台。

## 技术栈

- Vue 3 + TypeScript
- Vuetify 3
- Vue Router
- Pinia
- Vite
- Axios

## 快速开始

```bash
# 安装依赖
npm install

# 启动开发服务器
npm run dev

# 构建生产版本
npm run build
```

## 项目结构

```
src/
├── assets/          # 静态资源
├── components/      # 公共组件
├── layouts/         # 布局组件
├── plugins/         # 插件配置
├── router/          # 路由配置
├── stores/          # Pinia 状态管理
├── styles/          # 全局样式
├── views/           # 页面组件
├── App.vue          # 根组件
└── main.ts          # 入口文件
```

## 功能模块

- [x] 仪表盘 - 数据概览
- [x] 设备数据 - CRUD 管理
- [ ] 设备管理
- [ ] 告警管理
- [ ] 系统设置
