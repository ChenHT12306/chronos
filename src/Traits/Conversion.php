<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 日期转换 Trait
 */
trait Conversion
{
    /**
     * 获取时间戳
     */
    public function timestamp(): int
    {
        return (int) $this->dateTime->format('U');
    }

    /**
     * 获取毫秒时间戳
     */
    public function timestampMs(): int
    {
        return (int) ($this->dateTime->format('Uv'));
    }

    /**
     * 获取微秒时间戳
     */
    public function timestampUs(): string
    {
        return $this->dateTime->format('Uu');
    }

    /**
     * 格式化为日期字符串 (Y-m-d)
     */
    public function toDateString(): string
    {
        return $this->dateTime->format('Y-m-d');
    }

    /**
     * 格式化为时间字符串 (H:i:s)
     */
    public function toTimeString(): string
    {
        return $this->dateTime->format('H:i:s');
    }

    /**
     * 格式化为日期时间字符串 (Y-m-d H:i:s)
     */
    public function toDateTimeString(): string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    /**
     * 格式化为可读日期时间
     */
    public function toFormattedDateString(): string
    {
        return $this->dateTime->format('M j, Y');
    }

    /**
     * 格式化为长日期时间
     */
    public function toLongDateString(): string
    {
        return $this->dateTime->format('F j, Y');
    }

    /**
     * 格式化为长时间
     */
    public function toLongTimeString(): string
    {
        return $this->dateTime->format('g:i:s A');
    }

    /**
     * 格式化为 12 小时制时间
     */
    public function toTimeString12Hour(): string
    {
        return $this->dateTime->format('g:i A');
    }

    /**
     * 格式化为ATOM格式 (ISO 8601)
     */
    public function toAtomString(): string
    {
        return $this->dateTime->format(\DateTimeInterface::ATOM);
    }

    /**
     * 格式化为Cookie格式
     */
    public function toCookieString(): string
    {
        return $this->dateTime->format('l, d-M-Y H:i:s \G\M\T');
    }

    /**
     * 格式化为ISO 8601格式
     */
    public function toIsoString(): string
    {
        return $this->dateTime->format('c');
    }

    /**
     * 格式化为Rss格式
     */
    public function toRssString(): string
    {
        return $this->dateTime->format('D, d M Y H:i:s O');
    }

    /**
     * 格式化为 W3C 格式
     */
    public function toW3cString(): string
    {
        return $this->dateTime->format(\DateTimeInterface::W3C);
    }

    /**
     * 自定义格式
     */
    public function format(string $format): string
    {
        return $this->dateTime->format($format);
    }

    /**
     * 格式化为 'Y-m-d\TH:i:s.u' 格式（带微秒）
     */
    public function toIso8601MicroString(): string
    {
        return $this->dateTime->format('Y-m-d\TH:i:s.u');
    }

    /**
     * 获取 Unix 时间戳
     */
    public function getTimestamp(): int
    {
        return $this->timestamp();
    }

    /**
     * 获取时区偏移量（秒）
     */
    public function getOffset(): int
    {
        return $this->dateTime->getOffset();
    }
}
