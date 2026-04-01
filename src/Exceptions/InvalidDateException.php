<?php

declare(strict_types=1);

namespace Chronos\Exceptions;

use Exception;

/**
 * 无效日期异常
 */
class InvalidDateException extends Exception
{
    /**
     * 创建异常实例
     */
    public function __construct(string $message = 'Invalid date provided', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
