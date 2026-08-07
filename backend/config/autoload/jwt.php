<?php

declare(strict_types=1);

use function Hyperf\Support\env;

return [
    // JWT 签名密钥（生产环境务必通过环境变量覆盖）
    'secret' => env('JWT_SECRET', 'iot-platform-dev-secret-change-me'),
    // 令牌有效期（秒），默认 7 天
    'ttl' => (int) env('JWT_TTL', 604800),
];
