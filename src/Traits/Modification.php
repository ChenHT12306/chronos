<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 日期修改 Trait
 */
trait Modification
{
    /**
     * 添加天数
     */
    public function addDays(int $value = 1): static
    {
        return $this->add('days', $value);
    }

    /**
     * 减去天数
     */
    public function subDays(int $value = 1): static
    {
        return $this->sub('days', $value);
    }

    /**
     * 添加一天
     */
    public function addDay(): static
    {
        return $this->addDays(1);
    }

    /**
     * 减去一天
     */
    public function subDay(): static
    {
        return $this->subDays(1);
    }

    /**
     * 添加周数
     */
    public function addWeeks(int $value = 1): static
    {
        return $this->add('days', $value * 7);
    }

    /**
     * 减去周数
     */
    public function subWeeks(int $value = 1): static
    {
        return $this->sub('days', $value * 7);
    }

    /**
     * 添加一周
     */
    public function addWeek(): static
    {
        return $this->addWeeks(1);
    }

    /**
     * 减去一周
     */
    public function subWeek(): static
    {
        return $this->subWeeks(1);
    }

    /**
     * 添加月数
     */
    public function addMonths(int $value = 1): static
    {
        return $this->add('months', $value);
    }

    /**
     * 减去月数
     */
    public function subMonths(int $value = 1): static
    {
        return $this->sub('months', $value);
    }

    /**
     * 添加一个月
     */
    public function addMonth(): static
    {
        return $this->addMonths(1);
    }

    /**
     * 减去一个月
     */
    public function subMonth(): static
    {
        return $this->subMonths(1);
    }

    /**
     * 添加年数
     */
    public function addYears(int $value = 1): static
    {
        return $this->add('years', $value);
    }

    /**
     * 减去年数
     */
    public function subYears(int $value = 1): static
    {
        return $this->sub('years', $value);
    }

    /**
     * 添加一年
     */
    public function addYear(): static
    {
        return $this->addYears(1);
    }

    /**
     * 减一年
     */
    public function subYear(): static
    {
        return $this->subYears(1);
    }

    /**
     * 添加小时
     */
    public function addHours(int $value = 1): static
    {
        return $this->add('hours', $value);
    }

    /**
     * 减去小时
     */
    public function subHours(int $value = 1): static
    {
        return $this->sub('hours', $value);
    }

    /**
     * 添加一个小时
     */
    public function addHour(): static
    {
        return $this->addHours(1);
    }

    /**
     * 减去一个小时
     */
    public function subHour(): static
    {
        return $this->subHours(1);
    }

    /**
     * 添加分钟
     */
    public function addMinutes(int $value = 1): static
    {
        return $this->add('minutes', $value);
    }

    /**
     * 减去分钟
     */
    public function subMinutes(int $value = 1): static
    {
        return $this->sub('minutes', $value);
    }

    /**
     * 添加一分钟
     */
    public function addMinute(): static
    {
        return $this->addMinutes(1);
    }

    /**
     * 减去一分钟
     */
    public function subMinute(): static
    {
        return $this->subMinutes(1);
    }

    /**
     * 添加秒数
     */
    public function addSeconds(int $value = 1): static
    {
        return $this->add('seconds', $value);
    }

    /**
     * 减去秒数
     */
    public function subSeconds(int $value = 1): static
    {
        return $this->sub('seconds', $value);
    }

    /**
     * 添加一秒钟
     */
    public function addSecond(): static
    {
        return $this->addSeconds(1);
    }

    /**
     * 减去一秒钟
     */
    public function subSecond(): static
    {
        return $this->subSeconds(1);
    }

    /**
     * 添加时间
     */
    protected function add(string $unit, int $value): static
    {
        $newDateTime = $this->dateTime->modify("+{$value} {$unit}");
        return new static($newDateTime);
    }

    /**
     * 减去时间
     */
    protected function sub(string $unit, int $value): static
    {
        $newDateTime = $this->dateTime->modify("-{$value} {$unit}");
        return new static($newDateTime);
    }

    /**
     * 设置年份
     */
    public function setYear(int $year): static
    {
        return $this->set('Y', $year);
    }

    /**
     * 设置月份
     */
    public function setMonth(int $month): static
    {
        $month = max(1, min(12, $month));
        return $this->set('n', $month);
    }

    /**
     * 设置日期
     */
    public function setDay(int $day): static
    {
        $maxDays = $this->daysInMonth();
        $day = max(1, min($maxDays, $day));
        return $this->set('j', $day);
    }

    /**
     * 设置小时
     */
    public function setHour(int $hour): static
    {
        $hour = max(0, min(23, $hour));
        return $this->set('G', $hour);
    }

    /**
     * 设置分钟
     */
    public function setMinute(int $minute): static
    {
        $minute = max(0, min(59, $minute));
        return $this->set('i', $minute);
    }

    /**
     * 设置秒数
     */
    public function setSecond(int $second): static
    {
        $second = max(0, min(59, $second));
        return $this->set('s', $second);
    }

    /**
     * 设置时间部分
     */
    protected function set(string $format, int $value): static
    {
        $newDateTime = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $this->dateTime->format('Y-m-d H:i:s')
        )->setTimezone($this->dateTime->getTimezone());

        // 使用 modify 来设置特定值
        $formatStr = match ($format) {
            'Y' => "first day of January {$value}",
            'n' => $this->dateTime->format("Y-{$value}-d H:i:s"),
            'j' => $this->dateTime->format("Y-m-{$value} H:i:s"),
            'G' => $this->dateTime->format("Y-m-d {$value}:i:s"),
            'i' => $this->dateTime->format("Y-m-d H:{$value}:s"),
            's' => $this->dateTime->format("Y-m-d H:i:{$value}"),
            default => $this->dateTime->format('Y-m-d H:i:s'),
        };

        $newDateTime = new \DateTimeImmutable($formatStr, $this->dateTime->getTimezone());
        return new static($newDateTime);
    }

    /**
     * 设置日期和时间
     */
    public function setDateTime(int $year, int $month, int $day, int $hour, int $minute, int $second = 0): static
    {
        return static::create($year, $month, $day, $hour, $minute, $second, $this->tzName());
    }

    /**
     * 设置时区
     */
    public function setTimezone(string $timezone): static
    {
        $newDateTime = $this->dateTime->setTimezone(new \DateTimeZone($timezone));
        return new static($newDateTime);
    }

    /**
     * 一天的开始 (00:00:00)
     */
    public function startOfDay(): static
    {
        return new static($this->dateTime->setTime(0, 0, 0));
    }

    /**
     * 一天的结束 (23:59:59)
     */
    public function endOfDay(): static
    {
        return new static($this->dateTime->setTime(23, 59, 59));
    }

    /**
     * 一小时的开始 (mm:00:00)
     */
    public function startOfHour(): static
    {
        $minute = $this->minute();
        return new static($this->dateTime->setTime($this->hour(), 0, 0));
    }

    /**
     * 一小时的结束 (mm:59:59)
     */
    public function endOfHour(): static
    {
        return new static($this->dateTime->setTime($this->hour(), 59, 59));
    }

    /**
     * 一分钟的开始 (:ss)
     */
    public function startOfMinute(): static
    {
        return new static($this->dateTime->setTime($this->hour(), $this->minute(), 0));
    }

    /**
     * 一分钟的结束 (:59)
     */
    public function endOfMinute(): static
    {
        return new static($this->dateTime->setTime($this->hour(), $this->minute(), 59));
    }

    /**
     * 月初
     */
    public function startOfMonth(): static
    {
        return new static($this->dateTime->setDate($this->year(), $this->month(), 1)->setTime(0, 0, 0));
    }

    /**
     * 月末
     */
    public function endOfMonth(): static
    {
        $lastDay = $this->daysInMonth();
        return new static($this->dateTime->setDate($this->year(), $this->month(), $lastDay)->setTime(23, 59, 59));
    }

    /**
     * 年初
     */
    public function startOfYear(): static
    {
        return new static($this->dateTime->setDate($this->year(), 1, 1)->setTime(0, 0, 0));
    }

    /**
     * 年末
     */
    public function endOfYear(): static
    {
        return new static($this->dateTime->setDate($this->year(), 12, 31)->setTime(23, 59, 59));
    }

    /**
     * 周初 (周一)
     */
    public function startOfWeek(): static
    {
        $dayOfWeek = $this->dayOfWeek();
        return $this->subDays($dayOfWeek)->startOfDay();
    }

    /**
     * 周末 (周日)
     */
    public function endOfWeek(): static
    {
        $dayOfWeek = $this->dayOfWeek();
        return $this->addDays(6 - $dayOfWeek)->endOfDay();
    }

    /**
     * 季度初
     */
    public function startOfQuarter(): static
    {
        $quarterMonth = (($this->quarter() - 1) * 3) + 1;
        return new static($this->dateTime->setDate($this->year(), $quarterMonth, 1)->setTime(0, 0, 0));
    }

    /**
     * 季度末
     */
    public function endOfQuarter(): static
    {
        $quarterMonth = $this->quarter() * 3;
        $date = new static($this->dateTime->setDate($this->year(), $quarterMonth, 1)->setTime(0, 0, 0));
        return $date->endOfMonth();
    }

    /**
     * 转为UTC时区
     */
    public function toUtc(): static
    {
        return $this->setTimezone('UTC');
    }

    /**
     * 转为本地时区
     */
    public function toLocalTime(): static
    {
        return $this->setTimezone(date_default_timezone_get());
    }
}
