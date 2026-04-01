<?php

declare(strict_types=1);

namespace Chronos\Traits;

/**
 * 本地化 Trait
 */
trait Localization
{
    /**
     * 中文月份名称
     */
    protected static array $cnMonths = [
        '一月', '二月', '三月', '四月', '五月', '六月',
        '七月', '八月', '九月', '十月', '十一月', '十二月'
    ];

    /**
     * 中文月份名称（数字）
     */
    protected static array $cnMonthsNum = [
        '01月', '02月', '03月', '04月', '05月', '06月',
        '07月', '08月', '09月', '10月', '11月', '12月'
    ];

    /**
     * 中文星期名称
     */
    protected static array $cnDays = [
        '星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'
    ];

    /**
     * 中文星期名称（缩写）
     */
    protected static array $cnDaysShort = [
        '周日', '周一', '周二', '周三', '周四', '周五', '周六'
    ];

    /**
     * 获取中文月份名称
     */
    public function monthNameCn(): string
    {
        return self::$cnMonths[$this->month() - 1];
    }

    /**
     * 获取中文月份数字
     */
    public function monthNumCn(): string
    {
        return self::$cnMonthsNum[$this->month() - 1];
    }

    /**
     * 获取中文星期名称
     */
    public function dayNameCn(): string
    {
        return self::$cnDays[$this->dayOfWeek()];
    }

    /**
     * 获取中文星期名称（缩写）
     */
    public function shortDayNameCn(): string
    {
        return self::$cnDaysShort[$this->dayOfWeek()];
    }

    /**
     * 格式化为中文日期
     */
    public function toCnDateString(): string
    {
        return $this->format('Y年m月d日');
    }

    /**
     * 格式化为中文日期时间
     */
    public function toCnDateTimeString(): string
    {
        return $this->format('Y年m月d日 H:i:s');
    }

    /**
     * 格式化为中文日期（带星期）
     */
    public function toCnDateStringWithDay(): string
    {
        return $this->format('Y年m月d日') . ' ' . $this->dayNameCn();
    }

    /**
     * 格式化为中文简洁日期
     */
    public function toCnShortDateString(): string
    {
        return $this->format('m/d') . ' ' . $this->dayNameCn();
    }

    /**
     * 相对时间描述（中文）
     */
    public function diffForHumansCn(): string
    {
        $now = $this->copy()->now();
        $diff = $this->diffInSeconds($now, false);
        $absDiff = abs($diff);

        if ($absDiff < 60) {
            return '刚刚';
        }

        if ($absDiff < 3600) {
            $minutes = floor($absDiff / 60);
            $suffix = $diff >= 0 ? '后' : '前';
            return "{$minutes}分钟{$suffix}";
        }

        if ($absDiff < 86400) {
            $hours = floor($absDiff / 3600);
            $suffix = $diff >= 0 ? '后' : '前';
            return "{$hours}小时{$suffix}";
        }

        if ($absDiff < 604800) { // 7天
            $days = floor($absDiff / 86400);
            $suffix = $diff >= 0 ? '后' : '前';
            return "{$days}天{$suffix}";
        }

        if ($absDiff < 2592000) { // 30天
            $weeks = floor($absDiff / 604800);
            $suffix = $diff >= 0 ? '后' : '前';
            return "{$weeks}周{$suffix}";
        }

        if ($absDiff < 31536000) { // 365天
            $months = floor($absDiff / 2592000);
            $suffix = $diff >= 0 ? '后' : '前';
            return "{$months}个月{$suffix}";
        }

        $years = floor($absDiff / 31536000);
        $suffix = $diff >= 0 ? '后' : '前';
        return "{$years}年{$suffix}";
    }

    /**
     * 设置中文月份名称
     */
    public static function setCnMonths(array $months): void
    {
        if (count($months) === 12) {
            self::$cnMonths = $months;
        }
    }

    /**
     * 设置中文星期名称
     */
    public static function setCnDays(array $days): void
    {
        if (count($days) === 7) {
            self::$cnDays = $days;
        }
    }
}
