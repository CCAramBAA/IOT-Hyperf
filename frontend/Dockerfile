# 前端构建阶段
FROM node:20-alpine AS build
WORKDIR /app

# 先装依赖，利用 Docker 层缓存
COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# 生产运行阶段：nginx 托管构建产物
FROM nginx:1.25-alpine
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
