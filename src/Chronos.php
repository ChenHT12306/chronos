<?php

declare(strict_types=1);

namespace Chronos;

use ArrayAccess;
use Chronos\Exceptions\InvalidDateException;
use Chronos\Traits\Comparison;
use Chronos\Traits\Conversion;
use Chronos\Traits\Factory;
use Chronos\Traits\Localization;
use Chronos\Traits\Modification;
use Chronos\Traits\Parsing;
use Chronos\Traits\Testing;

/**
 * Chronos - 一个优雅的 PHP 日期时间处理类
 *
 * @method Chronos addDays(int $value) 添加天数
 * @method Chronos subDays(int $value) 减去天数
 * @method Chronos addMonths(int $value) 添加月数
 * @method Chronos subMonths(int $value) 减去月数
 * @method Chronos addYears(int $value) 添加年数
 * @method Chronos subYears(int $value) 减去年数
 * @method Chronos addHours(int $value) 添加小时
 * @method Chronos subHours(int $value) 减去小时
 * @method Chronos addMinutes(int $value) 添加分钟
 * @method Chronos subMinutes(int $value) 减去分钟
 * @method Chronos addSeconds(int $value) 添加秒数
 * @method Chronos subSeconds(int $value) 减去秒数
 * @method bool isToday() 是否今天
 * @method bool isTomorrow() 是否明天
 * @method bool isYesterday() 是否昨天
 * @method string format(string $format) 格式化日期
 * @method int timestamp 时间戳
 */
class Chronos implements ArrayAccess
{
    use Comparison;
    use Conversion;
    use Factory;
    use Localization;
    use Modification;
    use Parsing;
    use Testing;

    /**
     * 英文月份名称
     */
    public const MONTHS = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    /**
     * 英文月份名称（缩写）
     */
    public const MONTHS_SHORT = [
        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ];

    /**
     * 英文星期名称
     */
    public const DAYS = [
        'Sunday', 'Monday', 'Tuesday', 'Wednesday',
        'Thursday', 'Friday', 'Saturday'
    ];

    /**
     * 英文星期名称（缩写）
     */
    public const DAYS_SHORT = [
        'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
    ];

    /**
     * @var \DateTimeImmutable
     */
    protected \DateTimeImmutable $dateTime;

    /**
     * 默认时区
     */
    protected static ?string $defaultTimezone = null;

    /**
     * 本地化数据
     */
    protected static array $locale = [];

    /**
     * 创建 Chronos 实例
     */
    public function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * 创建当前时间的实例
     */
    public static function now(?string $timezone = null): static
    {
        $tz = $timezone ?? static::$defaultTimezone ?? date_default_timezone_get();
        return new static(new \DateTimeImmutable('now', new \DateTimeZone($tz)));
    }

    /**
     * 创建今天的实例
     */
    public static function today(?string $timezone = null): static
    {
        return static::now($timezone)->startOfDay();
    }

    /**
     * 创建明天的实例
     */
    public static function tomorrow(?string $timezone = null): static
    {
        return static::today($timezone)->addDay();
    }

    /**
     * 创建昨天的实例
     */
    public static function yesterday(?string $timezone = null): static
    {
        return static::today($timezone)->subDay();
    }

    /**
     * 创建指定日期的实例
     */
    public static function create(
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        ?string $timezone = null
    ): static {
        $tz = $timezone ?? static::$defaultTimezone ?? date_default_timezone_get();
        
        try {
            $dateTime = new \DateTimeImmutable(
                sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second),
                new \DateTimeZone($tz)
            );
        } catch (\Exception $e) {
            throw new InvalidDateException("Invalid date: {$year}-{$month}-{$day}");
        }

        return new static($dateTime);
    }

    /**
     * 从时间戳创建实例
     */
    public static function createFromTimestamp(int $timestamp, ?string $timezone = null): static
    {
        $tz = $timezone ?? static::$defaultTimezone ?? date_default_timezone_get();
        $dateTime = (new \DateTimeImmutable('@' . $timestamp))->setTimezone(new \DateTimeZone($tz));
        return new static($dateTime);
    }

    /**
     * 从格式创建实例
     */
    public static function createFromFormat(string $format, string $time, ?string $timezone = null): static
    {
        $tz = $timezone ?? static::$defaultTimezone ?? date_default_timezone_get();
        
        $dateTime = \DateTimeImmutable::createFromFormat($format, $time, new \DateTimeZone($tz));
        
        if ($dateTime === false) {
            throw new InvalidDateException("Unable to create date from format: {$format}");
        }

        return new static($dateTime);
    }

    /**
     * 解析日期字符串
     */
    public static function parse(string $time, ?string $timezone = null): static
    {
        $tz = $timezone ?? static::$defaultTimezone ?? date_default_timezone_get();
        
        try {
            $dateTime = new \DateTimeImmutable($time, new \DateTimeZone($tz));
        } catch (\Exception $e) {
            throw new InvalidDateException("Unable to parse: {$time}");
        }

        return new static($dateTime);
    }

    /**
     * 获取内部 DateTimeImmutable 对象
     */
    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * 获取年份
     */
    public function year(): int
    {
        return (int) $this->dateTime->format('Y');
    }

    /**
     * 获取月份
     */
    public function month(): int
    {
        return (int) $this->dateTime->format('n');
    }

    /**
     * 获取日期
     */
    public function day(): int
    {
        return (int) $this->dateTime->format('j');
    }

    /**
     * 获取小时
     */
    public function hour(): int
    {
        return (int) $this->dateTime->format('G');
    }

    /**
     * 获取分钟
     */
    public function minute(): int
    {
        return (int) $this->dateTime->format('i');
    }

    /**
     * 获取秒数
     */
    public function second(): int
    {
        return (int) $this->dateTime->format('s');
    }

    /**
     * 获取星期几 (0 = 周日, 6 = 周六)
     */
    public function dayOfWeek(): int
    {
        return (int) $this->dateTime->format('w');
    }

    /**
     * 获取年份中的第几天
     */
    public function dayOfYear(): int
    {
        return (int) $this->dateTime->format('z') + 1;
    }

    /**
     * 获取星期几名称
     */
    public function dayName(): string
    {
        return static::DAYS[$this->dayOfWeek()];
    }

    /**
     * 获取月份名称
     */
    public function monthName(): string
    {
        return static::MONTHS[$this->month() - 1];
    }

    /**
     * 获取月份名称（缩写）
     */
    public function shortMonthName(): string
    {
        return static::MONTHS_SHORT[$this->month() - 1];
    }

    /**
     * 获取星期名称（缩写）
     */
    public function shortDayName(): string
    {
        return static::DAYS_SHORT[$this->dayOfWeek()];
    }

    /**
     * 获取季度
     */
    public function quarter(): int
    {
        return (int) ceil($this->month() / 3);
    }

    /**
     * 获取该月的总天数
     */
    public function daysInMonth(): int
    {
        return (int) $this->dateTime->format('t');
    }

    /**
     * 获取该年的总天数
     */
    public function daysInYear(): int
    {
        return $this->isLeapYear() ? 366 : 365;
    }

    /**
     * 是否闰年
     */
    public function isLeapYear(): bool
    {
        return $this->dateTime->format('L') === '1';
    }

    /**
     * 是否周末
     */
    public function isWeekend(): bool
    {
        $dayOfWeek = $this->dayOfWeek();
        return $dayOfWeek === 0 || $dayOfWeek === 6;
    }

    /**
     * 是否工作日
     */
    public function isWeekday(): bool
    {
        return !$this->isWeekend();
    }

    /**
     * 获取时区
     */
    public function timezone(): \DateTimeZone
    {
        return $this->dateTime->getTimezone();
    }

    /**
     * 获取时区名称
     */
    public function tzName(): string
    {
        return $this->dateTime->getTimezone()->getName();
    }

    /**
     * 获取 UTC 偏移量（秒）
     */
    public function offset(): int
    {
        return $this->dateTime->getOffset();
    }

    /**
     * 设置默认时区
     */
    public static function setDefaultTimezone(string $timezone): void
    {
        static::$defaultTimezone = $timezone;
    }

    /**
     * 获取默认时区
     */
    public static function getDefaultTimezone(): ?string
    {
        return static::$defaultTimezone;
    }

    /**
     * 设置本地化数据
     */
    public static function setLocale(string $locale, array $data): void
    {
        static::$locale[$locale] = $data;
    }

    /**
     * 获取本地化数据
     */
    public static function getLocale(string $locale): ?array
    {
        return static::$locale[$locale] ?? null;
    }

    /**
     * 克隆实例（用于链式调用）
     */
    protected function copy(): static
    {
        return new static($this->dateTime);
    }

    /**
     * 转换为 Carbon 实例（兼容 Laravel）
     */
    public function toCarbon(): \Carbon\Carbon
    {
        return \Carbon\Carbon::instance($this->dateTime);
    }

    /**
     * 转换为 DateTimeImmutable
     */
    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * 转换为 DateTime
     */
    public function toDateTime(): \DateTime
    {
        return \DateTime::createFromImmutable($this->dateTime);
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'year' => $this->year(),
            'month' => $this->month(),
            'day' => $this->day(),
            'hour' => $this->hour(),
            'minute' => $this->minute(),
            'second' => $this->second(),
            'day_of_week' => $this->dayOfWeek(),
            'day_of_year' => $this->dayOfYear(),
            'quarter' => $this->quarter(),
            'days_in_month' => $this->daysInMonth(),
            'is_leap_year' => $this->isLeapYear(),
            'timezone' => $this->tzName(),
            'timestamp' => $this->timestamp(),
        ];
    }

    /**
     * 转换为 JSON
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * 字符串表示
     */
    public function __toString(): string
    {
        return $this->toDateTimeString();
    }

    /**
     * ArrayAccess: 检查偏移是否存在
     */
    public function offsetExists(mixed $offset): bool
    {
        return in_array($offset, ['year', 'month', 'day', 'hour', 'minute', 'second']);
    }

    /**
     * ArrayAccess: 获取偏移值
     */
    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            'year' => $this->year(),
            'month' => $this->month(),
            'day' => $this->day(),
            'hour' => $this->hour(),
            'minute' => $this->minute(),
            'second' => $this->second(),
            default => null,
        };
    }

    /**
     * ArrayAccess: 设置偏移值（不支持）
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // 只读，不支持修改
    }

    /**
     * ArrayAccess: 删除偏移值（不支持）
     */
    public function offsetUnset(mixed $offset): void
    {
        // 只读，不支持删除
    }
}
