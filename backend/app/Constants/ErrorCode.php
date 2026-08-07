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

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("success")
     */
    public const SUCCESS = 200;

    /**
     * @Message("参数错误")
     */
    public const PARAM_ERROR = 400;

    /**
     * @Message("数据不存在")
     */
    public const NOT_FOUND = 404;

    /**
     * @Message("未登录或登录已过期")
     */
    public const UNAUTHORIZED = 401;

    /**
     * @Message("服务器内部错误")
     */
    public const SERVER_ERROR = 500;
}
