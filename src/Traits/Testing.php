<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 测试辅助 Trait
 */
trait Testing
{
    /**
     * 用于测试的模拟时间
     */
    protected static ?\DateTimeImmutable $testNow = null;

    /**
     * 设置测试时间
     */
    public static function setTestNow(\DateTimeImmutable|string $time, ?string $timezone = null): void
    {
        if (is_string($time)) {
            $tz = $timezone ?? date_default_timezone_get();
            static::$testNow = new \DateTimeImmutable($time, new \DateTimeZone($tz));
        } else {
            static::$testNow = $time;
        }
    }

    /**
     * 获取测试时间
     */
    public static function getTestNow(): ?\DateTimeImmutable
    {
        return static::$testNow;
    }

    /**
     * 清除测试时间
     */
    public static function clearTestNow(): void
    {
        static::$testNow = null;
    }

    /**
     * 是否设置了测试时间
     */
    public static function hasTestNow(): bool
    {
        return static::$testNow !== null;
    }

    /**
     * 使用测试时间创建实例
     */
    public static function nowForTest(?string $timezone = null): static
    {
        if (static::$testNow !== null) {
            $tz = $timezone ?? static::$testNow->getTimezone()->getName();
            return new static(new \DateTimeImmutable(
                static::$testNow->format('Y-m-d H:i:s'),
                new \DateTimeZone($tz)
            ));
        }

        return static::now($timezone);
    }

    /**
     * 在测试模式下使用测试时间
     */
    public function now(): static
    {
        if (static::$testNow !== null) {
            return new static(new \DateTimeImmutable(
                static::$testNow->format('Y-m-d H:i:s.u'),
                $this->dateTime->getTimezone()
            ));
        }

        return parent::now($this->tzName());
    }
}
