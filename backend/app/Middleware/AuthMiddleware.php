<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ResponseInterface as HyperfResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class AuthMiddleware implements MiddlewareInterface
{
    protected array $whitelist = [
        '/',
        '/index/hello',
        '/auth/login',
        '/device/report',
    ];

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (in_array($path, $this->whitelist, true)) {
            return $handler->handle($request);
        }

        $token = $this->extractToken($request);
        if ($token === '') {
            return $this->unauthorized('未登录');
        }

        try {
            $secret = (string) $this->container->get(ConfigInterface::class)->get('jwt.secret');
            $payload = JWT::decode($token, new Key($secret, 'HS256'));
            Context::set('auth_user', $payload);
        } catch (Throwable) {
            return $this->unauthorized('登录已过期，请重新登录');
        }

        return $handler->handle($request);
    }

    private function extractToken(ServerRequestInterface $request): string
    {
        $header = $request->getHeaderLine('Authorization');
        if ($header !== '' && preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    private function unauthorized(string $msg): ResponseInterface
    {
        /** @var HyperfResponse $response */
        $response = $this->container->get(HyperfResponse::class);

        return $response->withStatus(401)->json([
            'code' => ErrorCode::UNAUTHORIZED,
            'msg' => $msg,
            'data' => null,
        ]);
    }
}
