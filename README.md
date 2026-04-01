# Chronos

一个优雅的 PHP 日期时间处理库，类似 Carbon，提供链式调用和丰富的时间操作方法。

## 特性

- ✨ 链式调用 API
- 🕐 完整的时区支持
- 📅 丰富的日期时间操作方法
- 🔄 日期比较和差异计算
- 🌐 中文本地化支持
- 📦 Laravel 原生集成
- 🧪 测试辅助功能

## 安装

```bash
composer require your-vendor/chronos
```

## Laravel 集成

### 注册服务提供者

在 `config/app.php` 中添加：

```php
'providers' => [
    // ...
    Chronos\ChronosServiceProvider::class,
],

'aliases' => [
    // ...
    'Chronos' => Chronos\Facades\Chronos::class,
],
```

### 发布配置文件

```bash
php artisan vendor:publish --tag=chronos-config
```

## 快速开始

### 基本使用

```php
use Chronos\Chronos;

// 获取当前时间
$now = Chronos::now();

// 创建指定日期
$date = Chronos::create(2024, 6, 15, 12, 30, 45);

// 从字符串解析
$date = Chronos::parse('2024-06-15 12:30:45');

// 从格式创建
$date = Chronos::createFromFormat('Y-m-d', '2024-06-15');
```

### 日期操作

```php
$date = Chronos::now();

// 添加时间
$date->addDays(5);           // 添加5天
$date->addMonths(1);         // 添加1个月
$date->addYears(2);          // 添加2年
$date->addHours(3);          // 添加3小时
$date->addMinutes(30);       // 添加30分钟
$date->addSeconds(60);      // 添加60秒

// 链式调用
$date = Chronos::now()
    ->addDays(5)
    ->addHours(3)
    ->startOfDay();

// 月初/月末
$date->startOfMonth();       // 月初
$date->endOfMonth();         // 月末

// 年初/年末
$date->startOfYear();        // 年初
$date->endOfYear();         // 年末

// 季度
$date->startOfQuarter();     // 季度初
$date->endOfQuarter();       // 季度末

// 周初/周末
$date->startOfWeek();        // 周初 (周一)
$date->endOfWeek();          // 周末 (周日)
```

### 日期比较

```php
$date1 = Chronos::create(2024, 6, 15);
$date2 = Chronos::create(2024, 6, 20);

// 比较方法
$date1->equalTo($date2);             // 是否相等
$date1->greaterThan($date2);          // 是否大于
$date1->lessThan($date2);             // 是否小于
$date1->between($date2, $date3);      // 是否在两个日期之间

// 便捷方法
$date->isToday();                     // 是否今天
$date->isTomorrow();                  // 是否明天
$date->isYesterday();                 // 是否昨天
$date->isWeekend();                   // 是否周末
$date->isWeekday();                   // 是否工作日
$date->isFuture();                    // 是否未来
$date->isPast();                      // 是否过去
$date->isLeapYear();                  // 是否闰年
```

### 日期差异

```php
$date1 = Chronos::create(2024, 6, 15);
$date2 = Chronos::create(2024, 6, 20);

// 获取差异
$date2->diffInDays($date1);           // 天数差异
$date2->diffInHours($date1);          // 小时差异
$date2->diffInMinutes($date1);        // 分钟差异
$date2->diffInSeconds($date1);        // 秒数差异
$date2->diffInWeeks($date1);          // 周数差异
$date2->diffInMonths($date1);         // 月数差异
$date2->diffInYears($date1);          // 年数差异

// 人类可读格式
$date->diffForHumans();               // "3天前" / "2小时后"
```

### 格式化输出

```php
$date = Chronos::create(2024, 6, 15, 12, 30, 45);

// 标准格式
$date->toDateString();                 // "2024-06-15"
$date->toTimeString();                 // "12:30:45"
$date->toDateTimeString();             // "2024-06-15 12:30:45"

// 友好格式
$date->toFormattedDateString();       // "Jun 15, 2024"
$date->toLongDateString();            // "June 15, 2024"
$date->toLongTimeString();            // "12:30:45 PM"

// 中文格式
$date->toCnDateString();              // "2024年06月15日"
$date->toCnDateTimeString();          // "2024年06月15日 12:30:45"
$date->toCnDateStringWithDay();       // "2024年06月15日 星期六"

// 自定义格式
$date->format('Y年m月d日 H:i:s');      // "2024年06月15日 12:30:45"
$date->format('Y/m/d');               // "2024/06/15"
```

### 中文支持

```php
$date = Chronos::create(2024, 6, 15);

// 中文月份和星期
$date->monthNameCn();                  // "六月"
$date->dayNameCn();                    // "星期六"
$date->shortDayNameCn();              // "周六"

// 中文日期
$date->toCnDateString();              // "2024年06月15日"
$date->toCnDateTimeString();          // "2024年06月15日 12:30:45"
$date->toCnDateStringWithDay();       // "2024年06月15日 星期六"
$date->toCnShortDateString();          // "06/15 周六"

// 中文相对时间
$date->diffForHumansCn();             // "3天前" / "2小时后"
```

### 时区处理

```php
$date = Chronos::create(2024, 6, 15, 12, 0, 0, 'UTC');

// 设置时区
$tokyo = $date->setTimezone('Asia/Tokyo');

// 转换为 UTC
$utc = $date->toUtc();

// 获取时区信息
$date->tzName();                      // 时区名称
$date->offset();                      // UTC 偏移秒数
```

### 获取日期组件

```php
$date = Chronos::create(2024, 6, 15, 12, 30, 45);

$date->year();                        // 2024
$date->month();                       // 6
$date->day();                         // 15
$date->hour();                        // 12
$date->minute();                      // 30
$date->second();                      // 45
$date->dayOfWeek();                   // 0 (周日) - 6 (周六)
$date->dayOfYear();                   // 167
$date->quarter();                     // 2
$date->daysInMonth();                 // 30
$date->dayName();                     // "Saturday"
$date->monthName();                   // "June"
$date->timestamp();                   // Unix 时间戳
```

### 测试支持

```php
use Chronos\Chronos;

// 设置测试时间
Chronos::setTestNow('2024-06-15 12:00:00');

// 之后调用 now() 会返回测试时间
$now = Chronos::now();  // 2024-06-15 12:00:00

// 清除测试时间
Chronos::clearTestNow();
```

### 转换为其他类型

```php
$date = Chronos::create(2024, 6, 15, 12, 30, 45);

// 转换为数组
$date->toArray();

// 转换为 Carbon (需要安装 carbon)
$carbon = $date->toCarbon();

// 转换为 DateTime
$dateTime = $date->toDateTime();

// 转换为 DateTimeImmutable
$dateTimeImmutable = $date->toDateTimeImmutable();

// 转换为时间戳
$timestamp = $date->timestamp();
```

## API 文档

### 静态工厂方法

| 方法 | 描述 |
|------|------|
| `now($tz)` | 获取当前时间 |
| `today($tz)` | 获取今天的日期 |
| `tomorrow($tz)` | 获取明天的日期 |
| `yesterday($tz)` | 获取昨天的日期 |
| `create($y, $m, $d, $h, $i, $s, $tz)` | 创建指定日期 |
| `parse($time, $tz)` | 解析日期字符串 |
| `createFromFormat($format, $time, $tz)` | 从格式创建 |
| `createFromTimestamp($timestamp, $tz)` | 从时间戳创建 |

### 实例方法

#### 修改方法

| 方法 | 描述 |
|------|------|
| `addDays($n)` | 添加 n 天 |
| `subDays($n)` | 减去 n 天 |
| `addMonths($n)` | 添加 n 月 |
| `subMonths($n)` | 减去 n 月 |
| `addYears($n)` | 添加 n 年 |
| `subYears($n)` | 减去 n 年 |
| `addHours($n)` | 添加 n 小时 |
| `subHours($n)` | 减去 n 小时 |
| `startOfDay()` | 一天开始 |
| `endOfDay()` | 一天结束 |
| `startOfMonth()` | 月初 |
| `endOfMonth()` | 月末 |
| `startOfYear()` | 年初 |
| `endOfYear()` | 年末 |
| `setTimezone($tz)` | 设置时区 |

#### 比较方法

| 方法 | 描述 |
|------|------|
| `equalTo($date)` | 是否等于 |
| `greaterThan($date)` | 是否大于 |
| `lessThan($date)` | 是否小于 |
| `isToday()` | 是否今天 |
| `isTomorrow()` | 是否明天 |
| `isYesterday()` | 是否昨天 |
| `isWeekend()` | 是否周末 |
| `isLeapYear()` | 是否闰年 |
| `diffInDays($date)` | 天数差异 |

#### 格式化方法

| 方法 | 描述 |
|------|------|
| `format($format)` | 自定义格式 |
| `toDateString()` | Y-m-d |
| `toTimeString()` | H:i:s |
| `toDateTimeString()` | Y-m-d H:i:s |
| `toCnDateString()` | 中文日期 |
| `toAtomString()` | ATOM 格式 |

## 配置

发布配置文件后，可以在 `config/chronos.php` 中修改：

```php
return [
    // 默认时区
    'timezone' => env('CHRONOS_TIMEZONE', null),
    
    // 默认语言
    'locale' => env('CHRONOS_LOCALE', 'zh_CN'),
];
```

## 贡献

欢迎提交 Issue 和 Pull Request！

## 许可证

MIT License
