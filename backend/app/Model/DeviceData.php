<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $device_id
 * @property float $temp
 * @property float $humidity
 * @property string $created_at
 */
class DeviceData extends Model
{
    protected ?string $table = 'device_data';

    protected array $fillable = ['device_id', 'temp', 'humidity'];

    protected array $casts = [
        'id' => 'integer',
        'temp' => 'float',
        'humidity' => 'float',
    ];

    /**
     * created_at 由数据库 DEFAULT CURRENT_TIMESTAMP 自动写入，
     * 表里没有 updated_at 字段，因此关闭框架自动时间戳。
     */
    public bool $timestamps = false;
}
