<?php

use Hyperf\HttpServer\Router\Router;
use App\Controller\AuthController;
use App\Controller\DeviceDataController;
use App\Controller\IndexController;

Router::get('/index/hello', [IndexController::class, 'hello']);
// 设备上报接口（设备端专用，只写不读）
Router::post('/device/report', [IndexController::class, 'deviceReport']);

// 登录接口
Router::post('/auth/login', [AuthController::class, 'login']);

// 设备数据 CRUD 接口
Router::get('/device/data', [DeviceDataController::class, 'index']);
Router::post('/device/data', [DeviceDataController::class, 'store']);
Router::put('/device/data/{id}', [DeviceDataController::class, 'update']);
Router::delete('/device/data/{id}', [DeviceDataController::class, 'delete']);
// 平台统计接口（仪表盘）
Router::get('/device/stats', [DeviceDataController::class, 'stats']);

Router::get('/', function () {
    return ['method' => 'GET', 'message' => 'Hello Hyperf.'];
});
