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

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    protected function success(mixed $data = null, string $msg = 'success'): array
    {
        return [
            'code' => ErrorCode::SUCCESS,
            'msg' => $msg,
            'data' => $data,
        ];
    }

    protected function validate(array $data, array $rules, array $messages = []): array
    {
        $validator = $this->validationFactory->make($data, $rules, $messages);
        if ($validator->fails()) {
            throw new BusinessException(ErrorCode::PARAM_ERROR, implode('；', $validator->errors()->all()));
        }

        return $validator->validated();
    }
}
