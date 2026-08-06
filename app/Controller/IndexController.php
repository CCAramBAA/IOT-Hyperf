<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\DbConnection\Db;

class IndexController extends AbstractController
{
    public function hello()
    {
        return [
            "code" => 200,
            "msg" => "我写的第一个hyperf接口",
            "data" => [
                "name" => "iot项目",
                "time" => date("Y-m-d H:i:s")
            ]
        ];
    }

    // 设备上报POST接口
    public function deviceReport()
    {
        // 获取POST的JSON请求体
        $postData = $this->request->getParsedBody();

        // 插入数据库
        Db::table('device_data')->insert([
            'device_id' => $postData['device_id'] ?? '',
            'temp'      => $postData['temp'] ?? null,
            'humidity'  => $postData['humidity'] ?? null,
        ]);

        return [
            "code" => 200,
            "msg" => "收到设备上报数据",
            "receive_data" => $postData
        ];
    }
}