<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_salt
 * @property string $created_at
 */
class User extends Model
{
    protected ?string $table = 'users';

    protected array $fillable = ['username', 'password_hash', 'password_salt'];

    protected array $hidden = ['password_hash', 'password_salt'];

    protected array $casts = [
        'id' => 'integer',
    ];

    public bool $timestamps = false;
}
