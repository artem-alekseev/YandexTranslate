<?php

namespace YandexTranslate;

use Illuminate\Support\ServiceProvider;

class YandexTranslateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/yandex-translate.php' => config_path('yandex-translate.php'),
        ], 'yandex-translate');
    }
}
