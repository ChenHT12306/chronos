<?php

declare(strict_types=1);

namespace Chronos\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * 设置测试环境
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 清理测试环境
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
