<?php

declare(strict_types=1);

namespace Chronos\Tests;

use Chronos\Chronos;
use PHPUnit\Framework\TestCase;

/**
 * Chronos 基础测试
 */
class ChronosTest extends TestCase
{
    /**
     * 测试创建当前时间
     */
    public function test_now(): void
    {
        $now = Chronos::now();
        $this->assertInstanceOf(Chronos::class, $now);
        $this->assertEquals(date('Y-m-d'), $now->toDateString());
    }

    /**
     * 测试创建指定日期
     */
    public function test_create(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 30, 45);
        
        $this->assertEquals(2024, $date->year());
        $this->assertEquals(6, $date->month());
        $this->assertEquals(15, $date->day());
        $this->assertEquals(12, $date->hour());
        $this->assertEquals(30, $date->minute());
        $this->assertEquals(45, $date->second());
    }

    /**
     * 测试从时间戳创建
     */
    public function test_create_from_timestamp(): void
    {
        $timestamp = 1718454000; // 2024-06-15 12:00:00
        $date = Chronos::createFromTimestamp($timestamp);
        
        $this->assertEquals($timestamp, $date->timestamp());
    }

    /**
     * 测试从格式创建
     */
    public function test_create_from_format(): void
    {
        $date = Chronos::createFromFormat('Y-m-d', '2024-06-15');
        
        $this->assertEquals(2024, $date->year());
        $this->assertEquals(6, $date->month());
        $this->assertEquals(15, $date->day());
    }

    /**
     * 测试今天
     */
    public function test_today(): void
    {
        $today = Chronos::today();
        
        $this->assertEquals(0, $today->hour());
        $this->assertEquals(0, $today->minute());
        $this->assertEquals(0, $today->second());
    }

    /**
     * 测试明天
     */
    public function test_tomorrow(): void
    {
        $tomorrow = Chronos::tomorrow();
        $today = Chronos::today();
        
        $this->assertEquals($today->day() + 1, $tomorrow->day());
    }

    /**
     * 测试昨天
     */
    public function test_yesterday(): void
    {
        $yesterday = Chronos::yesterday();
        $today = Chronos::today();
        
        $this->assertEquals(1, $today->diffInDays($yesterday));
    }

    /**
     * 测试日期解析
     */
    public function test_parse(): void
    {
        $date = Chronos::parse('2024-06-15 12:30:00');
        
        $this->assertEquals(2024, $date->year());
        $this->assertEquals(6, $date->month());
        $this->assertEquals(15, $date->day());
    }

    /**
     * 测试添加天数
     */
    public function test_add_days(): void
    {
        $date = Chronos::create(2024, 6, 15)->addDays(5);
        
        $this->assertEquals(20, $date->day());
    }

    /**
     * 测试减去天数
     */
    public function test_sub_days(): void
    {
        $date = Chronos::create(2024, 6, 15)->subDays(5);
        
        $this->assertEquals(10, $date->day());
    }

    /**
     * 测试添加月数
     */
    public function test_add_months(): void
    {
        $date = Chronos::create(2024, 6, 15)->addMonths(2);
        
        $this->assertEquals(8, $date->month());
    }

    /**
     * 测试添加年数
     */
    public function test_add_years(): void
    {
        $date = Chronos::create(2024, 6, 15)->addYears(1);
        
        $this->assertEquals(2025, $date->year());
    }

    /**
     * 测试添加小时
     */
    public function test_add_hours(): void
    {
        $date = Chronos::create(2024, 6, 15, 10, 0, 0)->addHours(5);
        
        $this->assertEquals(15, $date->hour());
    }

    /**
     * 测试比较 - 等于
     */
    public function test_equal_to(): void
    {
        $date1 = Chronos::create(2024, 6, 15);
        $date2 = Chronos::create(2024, 6, 15);
        
        $this->assertTrue($date1->equalTo($date2));
    }

    /**
     * 测试比较 - 大于
     */
    public function test_greater_than(): void
    {
        $date1 = Chronos::create(2024, 6, 16);
        $date2 = Chronos::create(2024, 6, 15);
        
        $this->assertTrue($date1->greaterThan($date2));
    }

    /**
     * 测试比较 - 小于
     */
    public function test_less_than(): void
    {
        $date1 = Chronos::create(2024, 6, 14);
        $date2 = Chronos::create(2024, 6, 15);
        
        $this->assertTrue($date1->lessThan($date2));
    }

    /**
     * 测试是否今天
     */
    public function test_is_today(): void
    {
        $today = Chronos::today();
        
        $this->assertTrue($today->isToday());
    }

    /**
     * 测试是否周末
     */
    public function test_is_weekend(): void
    {
        // 2024-06-15 是周六
        $saturday = Chronos::create(2024, 6, 15);
        $this->assertTrue($saturday->isWeekend());
        
        // 2024-06-17 是周一
        $monday = Chronos::create(2024, 6, 17);
        $this->assertFalse($monday->isWeekend());
    }

    /**
     * 测试是否闰年
     */
    public function test_is_leap_year(): void
    {
        // 2024 是闰年
        $date1 = Chronos::create(2024, 6, 15);
        $this->assertTrue($date1->isLeapYear());
        
        // 2023 不是闰年
        $date2 = Chronos::create(2023, 6, 15);
        $this->assertFalse($date2->isLeapYear());
    }

    /**
     * 测试天数差异
     */
    public function test_diff_in_days(): void
    {
        $date1 = Chronos::create(2024, 6, 20);
        $date2 = Chronos::create(2024, 6, 15);
        
        $this->assertEquals(5, $date1->diffInDays($date2));
    }

    /**
     * 测试天数差异（绝对值）
     */
    public function test_diff_in_days_absolute(): void
    {
        $date1 = Chronos::create(2024, 6, 15);
        $date2 = Chronos::create(2024, 6, 20);
        
        $this->assertEquals(5, $date1->diffInDays($date2));
    }

    /**
     * 测试开始/结束日
     */
    public function test_start_and_end_of_day(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 30, 45);
        
        $startOfDay = $date->startOfDay();
        $this->assertEquals(0, $startOfDay->hour());
        $this->assertEquals(0, $startOfDay->minute());
        $this->assertEquals(0, $startOfDay->second());
        
        $endOfDay = $date->endOfDay();
        $this->assertEquals(23, $endOfDay->hour());
        $this->assertEquals(59, $endOfDay->minute());
        $this->assertEquals(59, $endOfDay->second());
    }

    /**
     * 测试开始/结束月
     */
    public function test_start_and_end_of_month(): void
    {
        $date = Chronos::create(2024, 6, 15);
        
        $startOfMonth = $date->startOfMonth();
        $this->assertEquals(1, $startOfMonth->day());
        $this->assertEquals(0, $startOfMonth->hour());
        
        $endOfMonth = $date->endOfMonth();
        $this->assertEquals(30, $endOfMonth->day());
        $this->assertEquals(23, $endOfMonth->hour());
    }

    /**
     * 测试星期名称
     */
    public function test_day_name(): void
    {
        // 2024-06-17 是周一
        $monday = Chronos::create(2024, 6, 17);
        $this->assertEquals('Monday', $monday->dayName());
        
        // 2024-06-15 是周六
        $saturday = Chronos::create(2024, 6, 15);
        $this->assertEquals('Saturday', $saturday->dayName());
    }

    /**
     * 测试月份名称
     */
    public function test_month_name(): void
    {
        $date = Chronos::create(2024, 6, 15);
        $this->assertEquals('June', $date->monthName());
    }

    /**
     * 测试中文月份名称
     */
    public function test_month_name_cn(): void
    {
        $date = Chronos::create(2024, 6, 15);
        $this->assertEquals('六月', $date->monthNameCn());
    }

    /**
     * 测试中文星期名称
     */
    public function test_day_name_cn(): void
    {
        // 2024-06-17 是周一
        $monday = Chronos::create(2024, 6, 17);
        $this->assertEquals('星期一', $monday->dayNameCn());
    }

    /**
     * 测试设置时区
     */
    public function test_set_timezone(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 0, 0, 'UTC');
        $tokyo = $date->setTimezone('Asia/Tokyo');
        
        $this->assertEquals('Asia/Tokyo', $tokyo->tzName());
    }

    /**
     * 测试季度
     */
    public function test_quarter(): void
    {
        // 6月是第二季度
        $q2 = Chronos::create(2024, 6, 15);
        $this->assertEquals(2, $q2->quarter());
        
        // 12月是第四季度
        $q4 = Chronos::create(2024, 12, 15);
        $this->assertEquals(4, $q4->quarter());
    }

    /**
     * 测试季度初
     */
    public function test_start_of_quarter(): void
    {
        $date = Chronos::create(2024, 6, 15)->startOfQuarter();
        
        $this->assertEquals(4, $date->month());
        $this->assertEquals(1, $date->day());
    }

    /**
     * 测试季度末
     */
    public function test_end_of_quarter(): void
    {
        $date = Chronos::create(2024, 6, 15)->endOfQuarter();
        
        $this->assertEquals(6, $date->month());
        $this->assertEquals(30, $date->day());
    }

    /**
     * 测试格式化
     */
    public function test_format(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 30, 45);
        
        $this->assertEquals('2024-06-15', $date->format('Y-m-d'));
        $this->assertEquals('15/06/2024', $date->format('d/m/Y'));
        $this->assertEquals('12:30:45', $date->format('H:i:s'));
    }

    /**
     * 测试转换为数组
     */
    public function test_to_array(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 30, 45);
        $array = $date->toArray();
        
        $this->assertArrayHasKey('year', $array);
        $this->assertArrayHasKey('month', $array);
        $this->assertArrayHasKey('day', $array);
        $this->assertArrayHasKey('hour', $array);
        $this->assertArrayHasKey('minute', $array);
        $this->assertArrayHasKey('second', $array);
        $this->assertArrayHasKey('timestamp', $array);
        
        $this->assertEquals(2024, $array['year']);
        $this->assertEquals(6, $array['month']);
    }

    /**
     * 测试 ArrayAccess
     */
    public function test_array_access(): void
    {
        $date = Chronos::create(2024, 6, 15);
        
        $this->assertTrue(isset($date['year']));
        $this->assertEquals(2024, $date['year']);
        $this->assertEquals(6, $date['month']);
        $this->assertEquals(15, $date['day']);
    }

    /**
     * 测试字符串转换
     */
    public function test_to_string(): void
    {
        $date = Chronos::create(2024, 6, 15, 12, 30, 45);
        
        $this->assertEquals('2024-06-15 12:30:45', (string) $date);
    }

    /**
     * 测试中文日期格式化
     */
    public function test_to_cn_date_string(): void
    {
        $date = Chronos::create(2024, 6, 15);
        $this->assertEquals('2024年06月15日', $date->toCnDateString());
    }

    /**
     * 测试两个日期之间的差异（秒）
     */
    public function test_diff_in_seconds(): void
    {
        $date1 = Chronos::create(2024, 6, 15, 12, 0, 0);
        $date2 = Chronos::create(2024, 6, 15, 12, 1, 0);
        
        $this->assertEquals(60, $date2->diffInSeconds($date1));
    }

    /**
     * 测试两个日期之间的差异（小时）
     */
    public function test_diff_in_hours(): void
    {
        $date1 = Chronos::create(2024, 6, 15, 10, 0, 0);
        $date2 = Chronos::create(2024, 6, 15, 14, 0, 0);
        
        $this->assertEquals(4, $date2->diffInHours($date1));
    }

    /**
     * 测试月份天数
     */
    public function test_days_in_month(): void
    {
        // 6月有30天
        $june = Chronos::create(2024, 6, 15);
        $this->assertEquals(30, $june->daysInMonth());
        
        // 2月在闰年有29天
        $feb = Chronos::create(2024, 2, 15);
        $this->assertEquals(29, $feb->daysInMonth());
    }

    /**
     * 测试周初
     */
    public function test_start_of_week(): void
    {
        // 2024-06-17 是周一
        $monday = Chronos::create(2024, 6, 17);
        $startOfWeek = $monday->startOfWeek();
        
        // 周初应该是周日 2024-06-16
        $this->assertEquals('Sunday', $startOfWeek->dayName());
        $this->assertEquals(16, $startOfWeek->day());
    }

    /**
     * 测试周末
     */
    public function test_end_of_week(): void
    {
        // 2024-06-17 是周一
        $monday = Chronos::create(2024, 6, 17);
        $endOfWeek = $monday->endOfWeek();
        
        // 周末应该是周六 2024-06-22
        $this->assertEquals('Saturday', $endOfWeek->dayName());
        $this->assertEquals(22, $endOfWeek->day());
    }
}
