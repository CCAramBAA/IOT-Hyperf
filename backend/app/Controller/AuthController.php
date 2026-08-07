<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;

class AuthController extends AbstractController
{
    #[Inject]
    protected ConfigInterface $config;

    /**
     * 登录，成功后返回 JWT。
     */
    public function login(): array
    {
        $data = $this->validate($this->request->all(), [
            'username' => 'required|string|max:64',
            'password' => 'required|string',
        ], [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
        ]);

        $user = User::query()->where('username', $data['username'])->first();
        if (! $user || ! hash_equals($user->password_hash, hash('sha256', $data['password'] . $user->password_salt))) {
            throw new BusinessException(ErrorCode::UNAUTHORIZED, '用户名或密码错误');
        }

        $now = time();
        $token = JWT::encode([
            'iss' => 'iot-platform',
            'uid' => $user->id,
            'username' => $user->username,
            'iat' => $now,
            'exp' => $now + (int) $this->config->get('jwt.ttl', 604800),
        ], new Key((string) $this->config->get('jwt.secret'), 'HS256'), 'HS256');

        return $this->success([
            'token' => $token,
            'username' => $user->username,
        ], '登录成功');
    }
}
