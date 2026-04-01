<?php

declare(strict_types=1);

namespace Chronos;

use Illuminate\Support\ServiceProvider;

/**
 * Chronos 服务提供者
 */
class ChronosServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/chronos.php',
            'chronos'
        );

        $this->app->singleton('chronos', function () {
            return new Chronos(new \DateTimeImmutable());
        });

        $this->app->alias('chronos', Chronos::class);
    }

    /**
     * 引导服务
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/chronos.php' => config_path('chronos.php'),
            ], 'chronos-config');

            $this->publishes([
                __DIR__ . '/../resources/lang' => lang_path('vendor/chronos'),
            ], 'chronos-lang');
        }

        $this->loadTranslationsFrom(
            __DIR__ . '/../resources/lang',
            'chronos'
        );
    }

    /**
     * 获取提供的服务
     */
    public function provides(): array
    {
        return ['chronos', Chronos::class];
    }
}
