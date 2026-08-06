<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        if ($throwable instanceof BusinessException) {
            return $this->jsonResponse($response, $throwable->getCode(), $throwable->getMessage(), 200);
        }

        return $this->jsonResponse($response, ErrorCode::SERVER_ERROR, '服务器内部错误', 500);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    private function jsonResponse(ResponseInterface $response, int $code, string $msg, int $status): ResponseInterface
    {
        $body = json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => null,
        ], JSON_UNESCAPED_UNICODE);

        return $response->withHeader('Server', 'Hyperf')
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status)
            ->withBody(new SwooleStream($body));
    }
}
