# IOT 物联网平台

一个基于 Hyperf + Vue 3 的物联网平台项目，当前包含 Web 管理端与后端 API（微信小程序端规划中）。

## 技术栈

| 模块 | 技术 |
|------|------|
| 后端 | PHP 8.1+ / Hyperf 3.1 / Swoole |
| 前端 | Vue 3 + Vuetify 3 + TypeScript + Vite |
| 数据库 | MySQL 8.0 |
| 缓存 | Redis 7 |
| 部署 | Docker + docker-compose（nginx 托管前端） |

## 项目结构

```
IOT-Hyperf/
├── backend/            # Hyperf 后端 API
│   ├── app/            # 控制器、模型、异常处理等
│   ├── config/         # 路由与组件配置
│   └── public/         # 后端内置的简易监控页
├── frontend/           # Vue 3 + Vuetify Web 管理端
├── database/           # 数据库初始化脚本（首次启动自动执行）
├── miniprogram/        # uni-app 微信小程序（规划中）
├── deploy/             # 部署脚本（预留）
├── docs/               # 项目文档（预留）
└── docker-compose.yml  # 一键启动全部服务
```

## 快速开始

### 方式一：Docker 一键启动（推荐）

要求：已安装 Docker 与 docker-compose。

```bash
# 构建并启动所有服务
docker-compose up -d --build

# 查看服务状态
docker-compose ps
```

启动后访问：

- Web 管理端：http://localhost:8080
- 后端 API：http://localhost:9501
- MySQL：localhost:3306（用户 `iot` / 密码 `iot123456`，root 密码 `root123456`）
- Redis：localhost:6379（密码 `redis123456`）

默认管理员账号：`admin` / `admin123`（首次启动自动创建，**上线前务必修改密码**）。

首次启动会自动执行 `database/init.sql` 创建 `iot_platform` 数据库和 `device_data` 表。若之前启动过 MySQL 且数据卷已存在，需要先清理旧卷再启动：

```bash
docker-compose down -v
docker-compose up -d --build
```

> `down -v` 会删除数据卷，仅在确认可以丢弃数据库数据时使用。

### 方式二：本地开发

后端（需要 PHP 8.1+ / Swoole 扩展）：

```bash
cd backend
composer install
cp .env.example .env   # 按需修改数据库/Redis 连接
php bin/hyperf.php start
```

前端（需要 Node.js 18+）：

```bash
cd frontend
npm install
npm run dev
```

前端开发服务器运行在 http://localhost:3000，已配置 `/api` 代理到 `http://localhost:9501`。

## API 说明

所有接口统一返回 `{ "code": 200, "msg": "success", "data": ... }`，业务失败时 `code` 为 400/404 等。

| 方法 | 路径 | 说明 |
|------|------|------|
| POST | `/auth/login` | 登录，传入 `{username, password}`，返回 JWT |
| GET | `/device/data` | 设备数据列表（支持 `?device_id=xxx&page=1&page_size=10`，返回 `{list,total,page,page_size}`） |
| POST | `/device/data` | 新增设备数据 |
| PUT | `/device/data/{id}` | 修改设备数据（支持部分字段） |
| DELETE | `/device/data/{id}` | 删除设备数据 |
| GET | `/device/stats` | 平台统计（设备数/24h活跃/今日数据/总量） |
| POST | `/device/report` | 设备上报接口（设备端专用） |

除 `/auth/login`、`/device/report` 外的接口都需要登录：请求头带 `Authorization: Bearer <token>`。JWT 密钥通过环境变量 `JWT_SECRET` 配置。

新增/修改参数：`device_id`（必填，≤64 字符）、`temp`（温度，数字）、`humidity`（湿度，数字）。

## 部署到 Linux

```bash
# 1. 克隆项目
git clone git@github.com:CCAramBAA/IOT-Hyperf.git
cd IOT-Hyperf

# 2. 构建并启动
docker-compose up -d --build
```

访问地址同上：Web 管理端 `http://服务器IP:8080`，后端 API `http://服务器IP:9501`。

## 开发进度

- [x] 项目骨架搭建
- [x] 数据库设计与初始化脚本
- [x] 后端设备数据 CRUD 接口
- [x] 前端管理端接入真实 API
- [x] 自动化构建与集成验收（GitHub Actions CI）
- [x] 仪表盘真实统计
- [x] 登录鉴权（JWT）
- [ ] 微信小程序端
