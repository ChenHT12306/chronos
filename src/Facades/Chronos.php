<?php

declare(strict_types=1);

namespace Chronos\Facades;

use Chronos\Chronos as BaseChronos;
use Illuminate\Support\Facades\Facade;

/**
 * Chronos 门脸
 *
 * @method static Chronos now(string $tz = null) 获取当前时间
 * @method static Chronos today(string $tz = null) 获取今天的日期
 * @method static Chronos tomorrow(string $tz = null) 获取明天的日期
 * @method static Chronos yesterday(string $tz = null) 获取昨天的日期
 * @method static Chronos create(int $year, int $month, int $day, int $hour = 0, int $minute = 0, int $second = 0, $tz = null) 创建日期
 * @method static Chronos createFromFormat(string $format, string $time, $tz = null) 从格式创建
 * @method static Chronos parse(string $time, $tz = null) 解析日期字符串
 */
class Chronos extends Facade
{
    /**
     * 获取注册名称
     */
    protected static function getFacadeAccessor(): string
    {
        return 'chronos';
    }

    /**
     * 获取真实类
     */
    public static function getFacadeRoot(): string
    {
        return BaseChronos::class;
    }
}
