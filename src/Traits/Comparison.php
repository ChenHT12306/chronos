<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 日期比较 Trait
 */
trait Comparison
{
    /**
     * 检查是否等于指定日期
     */
    public function equalTo(self $date): bool
    {
        return $this->timestamp() === $date->timestamp();
    }

    /**
     * 检查是否不等于指定日期
     */
    public function notEqualTo(self $date): bool
    {
        return !$this->equalTo($date);
    }

    /**
     * 检查是否大于指定日期
     */
    public function greaterThan(self $date): bool
    {
        return $this->timestamp() > $date->timestamp();
    }

    /**
     * 检查是否大于等于指定日期
     */
    public function greaterThanOrEqualTo(self $date): bool
    {
        return $this->timestamp() >= $date->timestamp();
    }

    /**
     * 检查是否小于指定日期
     */
    public function lessThan(self $date): bool
    {
        return $this->timestamp() < $date->timestamp();
    }

    /**
     * 检查是否小于等于指定日期
     */
    public function lessThanOrEqualTo(self $date): bool
    {
        return $this->timestamp() <= $date->timestamp();
    }

    /**
     * 检查是否在两个日期之间
     */
    public function between(self $date1, self $date2, bool $equal = true): bool
    {
        if ($equal) {
            return $this->timestamp() >= min($date1->timestamp(), $date2->timestamp())
                && $this->timestamp() <= max($date1->timestamp(), $date2->timestamp());
        }
        
        return $this->timestamp() > min($date1->timestamp(), $date2->timestamp())
            && $this->timestamp() < max($date1->timestamp(), $date2->timestamp());
    }

    /**
     * 检查是否不在两个日期之间
     */
    public function notBetween(self $date1, self $date2, bool $equal = true): bool
    {
        return !$this->between($date1, $date2, $equal);
    }

    /**
     * 检查是否今天
     */
    public function isToday(): bool
    {
        return $this->toDateString() === $this->copy()->today()->toDateString();
    }

    /**
     * 检查是否明天
     */
    public function isTomorrow(): bool
    {
        return $this->toDateString() === $this->copy()->tomorrow()->toDateString();
    }

    /**
     * 检查是否昨天
     */
    public function isYesterday(): bool
    {
        return $this->toDateString() === $this->copy()->yesterday()->toDateString();
    }

    /**
     * 检查是否未来
     */
    public function isFuture(): bool
    {
        return $this->timestamp() > $this->copy()->now()->timestamp();
    }

    /**
     * 检查是否过去
     */
    public function isPast(): bool
    {
        return $this->timestamp() < $this->copy()->now()->timestamp();
    }

    /**
     * 检查是否同一天
     */
    public function isSameDay(self $date): bool
    {
        return $this->year() === $date->year()
            && $this->month() === $date->month()
            && $this->day() === $date->day();
    }

    /**
     * 检查是否同一年
     */
    public function isSameYear(self $date): bool
    {
        return $this->year() === $date->year();
    }

    /**
     * 检查是否同一月
     */
    public function isSameMonth(self $date): bool
    {
        return $this->year() === $date->year() && $this->month() === $date->month();
    }

    /**
     * 检查是否同一小时
     */
    public function isSameHour(self $date): bool
    {
        return $this->isSameDay($date) && $this->hour() === $date->hour();
    }

    /**
     * 检查是否同一分钟
     */
    public function isSameMinute(self $date): bool
    {
        return $this->isSameHour($date) && $this->minute() === $date->minute();
    }

    /**
     * 检查是否同一秒
     */
    public function isSameSecond(self $date): bool
    {
        return $this->timestamp() === $date->timestamp();
    }

    /**
     * 比较两个日期
     * 
     * @return int -1 if less than, 0 if equal, 1 if greater than
     */
    public function compare(self $date): int
    {
        $diff = $this->timestamp() - $date->timestamp();
        
        if ($diff < 0) return -1;
        if ($diff > 0) return 1;
        
        return 0;
    }

    /**
     * 获取两个日期之间的差异（秒）
     */
    public function diffInSeconds(self $date, bool $abs = true): int
    {
        $diff = $this->timestamp() - $date->timestamp();
        
        return $abs ? abs($diff) : $diff;
    }

    /**
     * 获取两个日期之间的差异（分钟）
     */
    public function diffInMinutes(self $date, bool $abs = true): int
    {
        return (int) floor($this->diffInSeconds($date, $abs) / 60);
    }

    /**
     * 获取两个日期之间的差异（小时）
     */
    public function diffInHours(self $date, bool $abs = true): int
    {
        return (int) floor($this->diffInSeconds($date, $abs) / 3600);
    }

    /**
     * 获取两个日期之间的差异（天数）
     */
    public function diffInDays(self $date, bool $abs = true): int
    {
        return (int) floor($this->diffInSeconds($date, $abs) / 86400);
    }

    /**
     * 获取两个日期之间的差异（周数）
     */
    public function diffInWeeks(self $date, bool $abs = true): int
    {
        return (int) floor($this->diffInDays($date, $abs) / 7);
    }

    /**
     * 获取两个日期之间的差异（月数）
     */
    public function diffInMonths(self $date, bool $abs = true): int
    {
        $from = $this->dateTime;
        $to = $date->dateTime;
        
        $diff = (int) ($to->format('Y') - $from->format('Y')) * 12
            + (int) ($to->format('n') - $from->format('n'));
        
        // 考虑日期差异
        $dayDiff = (int) $to->format('j') - (int) $from->format('j');
        if ($dayDiff < 0) {
            $diff--;
        } elseif ($dayDiff === 0 && $to->format('H:i:s') < $from->format('H:i:s')) {
            $diff--;
        }
        
        return $abs ? abs($diff) : $diff;
    }

    /**
     * 获取两个日期之间的差异（年数）
     */
    public function diffInYears(self $date, bool $abs = true): int
    {
        $diff = (int) $this->dateTime->format('Y') - (int) $date->dateTime->format('Y');
        
        // 考虑月日差异
        $monthDiff = (int) $this->dateTime->format('n') - (int) $date->dateTime->format('n');
        if ($monthDiff < 0 || ($monthDiff === 0 && $this->day() < $date->day())) {
            $diff--;
        }
        
        return $abs ? abs($diff) : $diff;
    }

    /**
     * 获取人类可读的差异
     */
    public function diffForHumans(self $date = null, bool $absolute = true): string
    {
        $date = $date ?? $this->copy()->now();
        $diff = $this->diffInSeconds($date, false);
        $absDiff = abs($diff);
        
        $direction = $diff >= 0 ? '后' : '前';
        
        if ($absDiff < 60) {
            return '刚刚';
        }
        
        if ($absDiff < 3600) {
            $minutes = floor($absDiff / 60);
            return "{$minutes}分钟{$direction}";
        }
        
        if ($absDiff < 86400) {
            $hours = floor($absDiff / 3600);
            return "{$hours}小时{$direction}";
        }
        
        if ($absDiff < 604800) { // 7天
            $days = floor($absDiff / 86400);
            return "{$days}天{$direction}";
        }
        
        if ($absDiff < 2592000) { // 30天
            $weeks = floor($absDiff / 604800);
            return "{$weeks}周{$direction}";
        }
        
        if ($absDiff < 31536000) { // 365天
            $months = floor($absDiff / 2592000);
            return "{$months}月{$direction}";
        }
        
        $years = floor($absDiff / 31536000);
        return "{$years}年{$direction}";
    }
}
