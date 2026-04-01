<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 日期工厂 Trait - 提供各种创建日期的方法
 */
trait Factory
{
    /**
     * 创建指定年份的 1 月 1 日
     */
    public static function createStartOfYear(int $year, ?string $timezone = null): static
    {
        return static::create($year, 1, 1, 0, 0, 0, $timezone);
    }

    /**
     * 创建指定年份的 12 月 31 日
     */
    public static function createEndOfYear(int $year, ?string $timezone = null): static
    {
        return static::create($year, 12, 31, 23, 59, 59, $timezone);
    }

    /**
     * 创建指定月份的 1 日
     */
    public static function createStartOfMonth(int $year, int $month, ?string $timezone = null): static
    {
        return static::create($year, $month, 1, 0, 0, 0, $timezone);
    }

    /**
     * 创建指定月份的月末
     */
    public static function createEndOfMonth(int $year, int $month, ?string $timezone = null): static
    {
        $date = static::create($year, $month, 1, $timezone);
        $days = $date->daysInMonth();
        return static::create($year, $month, $days, 23, 59, 59, $timezone);
    }

    /**
     * 创建指定日期的周一
     */
    public static function createStartOfWeek(int $year, int $month, int $day, ?string $timezone = null): static
    {
        $date = static::create($year, $month, $day, $timezone);
        $dayOfWeek = $date->dayOfWeek();
        return $date->subDays($dayOfWeek)->startOfDay();
    }

    /**
     * 创建指定日期的周日
     */
    public static function createEndOfWeek(int $year, int $month, int $day, ?string $timezone = null): static
    {
        $date = static::create($year, $month, $day, $timezone);
        $dayOfWeek = $date->dayOfWeek();
        return $date->addDays(6 - $dayOfWeek)->endOfDay();
    }

    /**
     * 从数组创建
     */
    public static function createFromArray(array $data): static
    {
        $year = $data['year'] ?? date('Y');
        $month = $data['month'] ?? 1;
        $day = $data['day'] ?? 1;
        $hour = $data['hour'] ?? 0;
        $minute = $data['minute'] ?? 0;
        $second = $data['second'] ?? 0;
        $timezone = $data['timezone'] ?? null;

        return static::create($year, $month, $day, $hour, $minute, $second, $timezone);
    }

    /**
     * 创建 Unix 时间戳开始的时间（00:00:00）
     */
    public static function createBeginningOfDay(?string $timezone = null): static
    {
        return static::today($timezone);
    }

    /**
     * 创建 Unix 时间戳结束的时间（23:59:59）
     */
    public static function createEndOfDay(?string $timezone = null): static
    {
        return static::today($timezone)->endOfDay();
    }

    /**
     * 创建 Unix 时间戳开始的小时
     */
    public static function createStartOfHour(?string $timezone = null): static
    {
        return static::now($timezone)->setMinute(0)->setSecond(0);
    }

    /**
     * 创建 Unix 时间戳结束的小时
     */
    public static function createEndOfHour(?string $timezone = null): static
    {
        return static::now($timezone)->setMinute(59)->setSecond(59);
    }

    /**
     * 创建 Unix 时间戳开始的分钟
     */
    public static function createStartOfMinute(?string $timezone = null): static
    {
        return static::now($timezone)->setSecond(0);
    }

    /**
     * 创建 Unix 时间戳结束的分钟
     */
    public static function createEndOfMinute(?string $timezone = null): static
    {
        return static::now($timezone)->setSecond(59);
    }
}
