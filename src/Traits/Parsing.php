<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 日期解析 Trait
 */
trait Parsing
{
    /**
     * 判断是否是有效日期
     */
    public static function isValidDate(int $year, int $month, int $day): bool
    {
        return checkdate($month, $day, $year);
    }

    /**
     * 判断是否是有效时间
     */
    public static function isValidTime(int $hour, int $minute, int $second = 0): bool
    {
        return $hour >= 0 && $hour < 24 && $minute >= 0 && $minute < 60 && $second >= 0 && $second < 60;
    }

    /**
     * 从自然语言解析日期
     */
    public static function parseNaturalLanguage(string $input, ?string $timezone = null): static
    {
        $input = trim($input);
        $tz = $timezone ?? date_default_timezone_get();

        // 处理相对时间
        $modifiers = [];

        // 今天/明天/昨天
        if (preg_match('/^(今天?|tomorrow|yesterday)$/iu', $input, $matches)) {
            $modifier = match (mb_strtolower($matches[1])) {
                '今天', 'today' => 'today',
                '明天', 'tomorrow' => 'tomorrow',
                '昨天', 'yesterday' => 'yesterday',
                default => 'today',
            };
            $input = str_replace($matches[1], '', $input);
            $modifiers[] = $modifier;
        }

        // 时间部分
        if (preg_match('/(\d{1,2}):(\d{2})(?::(\d{2}))?/', $input, $timeMatches)) {
            $input = str_replace($timeMatches[0], '', $input);
        }

        // 自然语言关键词
        $keywords = [
            '上午' => 'am',
            '下午' => 'pm',
            '早上' => 'am',
            '中午' => '12:00',
            '晚上' => 'pm',
            'monday' => 'next monday',
            'tuesday' => 'next tuesday',
            'wednesday' => 'next wednesday',
            'thursday' => 'next thursday',
            'friday' => 'next friday',
            'saturday' => 'next saturday',
            'sunday' => 'next sunday',
            '周一' => 'next monday',
            '周二' => 'next tuesday',
            '周三' => 'next wednesday',
            '周四' => 'next thursday',
            '周五' => 'next friday',
            '周六' => 'next saturday',
            '周日', '周天' => 'next sunday',
        ];

        foreach ($keywords as $keyword => $replacement) {
            if (stripos($input, $keyword) !== false) {
                $input = str_ireplace($keyword, $replacement, $input);
            }
        }

        $baseTime = !empty($modifiers) ? implode(' ', $modifiers) : 'now';
        $timeString = isset($timeMatches[0]) ? $timeMatches[0] : '';

        $finalInput = trim("{$baseTime} {$input} {$timeString}");

        return static::parse($finalInput, $tz);
    }

    /**
     * 解析模糊日期
     */
    public static function parseFuzzy(string $input, ?string $timezone = null): static
    {
        $input = trim($input);
        $tz = $timezone ?? date_default_timezone_get();

        // Y-m-d 格式
        if (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $input)) {
            return static::parse($input, $tz);
        }

        // d/m/Y 或 d-m-Y 格式
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $input, $matches)) {
            // 判断是否是 d/m/y 还是 m/d/y
            if ((int) $matches[1] > 12) {
                // 肯定是 d/m/y
                $date = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
            } elseif ((int) $matches[2] > 12) {
                // 肯定是 d/m/y
                $date = sprintf('%04d-%02d-%02d', $matches[3], $matches[1], $matches[2]);
            } else {
                // 模糊，假设为 d/m/y
                $date = sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
            }
            return static::parse($date, $tz);
        }

        // 纯数字日期
        if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $input, $matches)) {
            return static::create((int) $matches[1], (int) $matches[2], (int) $matches[3], 0, 0, 0, $tz);
        }

        // 默认直接解析
        return static::parse($input, $tz);
    }

    /**
     * 解析年龄
     */
    public static function parseAge(int $age): static
    {
        return static::now()->subYears($age);
    }

    /**
     * 解析秒数为 Chronos 对象
     */
    public static function parseSeconds(int $seconds): static
    {
        return static::createFromTimestamp($seconds);
    }

    /**
     * 从 SQL 格式解析
     */
    public static function fromSql(string $date, ?string $timezone = null): static
    {
        return static::parse($date, $timezone);
    }

    /**
     * 从原子格式解析
     */
    public static function fromAtom(string $date): static
    {
        $dateTime = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, $date);
        
        if ($dateTime === false) {
            throw new \Chronos\Exceptions\InvalidDateException("Unable to parse Atom format: {$date}");
        }

        return new static($dateTime);
    }

    /**
     * 从 ISO 8601 格式解析
     */
    public static function fromIso(string $date): static
    {
        $dateTime = \DateTimeImmutable::createFromFormat(\DateTimeInterface::ISO8601, $date);
        
        if ($dateTime === false) {
            throw new \Chronos\Exceptions\InvalidDateException("Unable to parse ISO8601 format: {$date}");
        }

        return new static($dateTime);
    }

    /**
     * 从 Cookie 格式解析
     */
    public static function fromCookie(string $date): static
    {
        $dateTime = \DateTimeImmutable::createFromFormat('l, d-M-Y H:i:s \G\M\T', $date);
        
        if ($dateTime === false) {
            throw new \Chronos\Exceptions\InvalidDateException("Unable to parse Cookie format: {$date}");
        }

        return new static($dateTime);
    }

    /**
     * 从 RSS 格式解析
     */
    public static function fromRss(string $date): static
    {
        $dateTime = \DateTimeImmutable::createFromFormat('D, d M Y H:i:s O', $date);
        
        if ($dateTime === false) {
            throw new \Chronos\Exceptions\InvalidDateException("Unable to parse RSS format: {$date}");
        }

        return new static($dateTime);
    }

    /**
     * 获取最大日期
     */
    public static function max(self ...$dates): static
    {
        if (empty($dates)) {
            throw new \InvalidArgumentException('At least one date must be provided');
        }

        $max = array_shift($dates);
        foreach ($dates as $date) {
            if ($date->greaterThan($max)) {
                $max = $date;
            }
        }

        return $max;
    }

    /**
     * 获取最小日期
     */
    public static function min(self ...$dates): static
    {
        if (empty($dates)) {
            throw new \InvalidArgumentException('At least one date must be provided');
        }

        $min = array_shift($dates);
        foreach ($dates as $date) {
            if ($date->lessThan($min)) {
                $min = $date;
            }
        }

        return $min;
    }
}
