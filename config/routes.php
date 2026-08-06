<?php

use Hyperf\HttpServer\Router\Router;

Router::get('/index/hello', [App\Controller\IndexController::class, 'hello']);
// 新增设备上报post接口
Router::post('/device/report', [App\Controller\IndexController::class, 'deviceReport']);

Router::get('/', function () {
    return ['method' => 'GET', 'message' => 'Hello Hyperf.'];
});