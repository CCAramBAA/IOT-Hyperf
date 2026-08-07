-- =====================================================
-- IOT 物联网平台 - 数据库初始化脚本
-- 作用：创建 iot_platform 数据库及 device_data 设备上报数据表
-- 执行方式：MySQL 容器首次启动时，docker-compose 会自动执行本目录
--           （./database 挂载到容器内 /docker-entrypoint-initdb.d）
-- 注意：该目录下的脚本只在 MySQL 数据卷【首次创建】时执行一次；
--       若数据卷已存在（例如之前启动过容器），需要先
--       docker-compose down -v 清除旧卷，再重新 docker-compose up -d
-- =====================================================

-- 建库（docker-compose 的 MYSQL_DATABASE 环境变量也会创建，
-- 这里保留 CREATE DATABASE，保证脱离 docker-compose 单独导入也可用）
CREATE DATABASE IF NOT EXISTS iot_platform
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE iot_platform;

-- 设备上报数据表
CREATE TABLE IF NOT EXISTS device_data (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  device_id   VARCHAR(64)     NOT NULL                COMMENT '设备编号，如 dev001',
  temp        DECIMAL(5,2)    NULL DEFAULT NULL       COMMENT '温度（摄氏度）',
  humidity    DECIMAL(5,2)    NULL DEFAULT NULL       COMMENT '湿度（百分比）',
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上报时间',
  PRIMARY KEY (id),
  KEY idx_device_id_created_at (device_id, created_at) COMMENT '按设备+时间查询索引'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci
  COMMENT = '设备上报数据表';

-- 平台用户表（管理端登录）
CREATE TABLE IF NOT EXISTS users (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  username      VARCHAR(64)     NOT NULL COMMENT '用户名',
  password_hash CHAR(64)        NOT NULL COMMENT '密码哈希：SHA256(密码+盐)',
  password_salt VARCHAR(32)     NOT NULL COMMENT '密码盐',
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (id),
  UNIQUE KEY uk_username (username)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci
  COMMENT = '平台用户表';

-- 默认管理员：admin / admin123（仅首次初始化时插入，上线前请修改密码或删除该账号）
INSERT IGNORE INTO users (username, password_hash, password_salt)
VALUES ('admin', SHA2(CONCAT('admin123', 'iot-salt-2026'), 256), 'iot-salt-2026');

-- =====================================================
-- 可选：演示数据（开发阶段需要页面有初始数据时，取消注释即可）
-- =====================================================
-- INSERT INTO device_data (device_id, temp, humidity) VALUES
--   ('dev001', 25.50, 60.00),
--   ('dev001', 25.80, 58.50),
--   ('dev002', 26.10, 55.00);
