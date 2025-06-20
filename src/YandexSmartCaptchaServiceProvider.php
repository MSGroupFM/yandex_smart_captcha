<?php
/*
 * @package     yandex_smart_captcha
 * @author      Vladislav Tsygankov
 * @copyright   Copyright (c) 2025 MSGru. All rights reserved.
 * @license     MIT
 * @link        https://msgru.com/
 */

namespace MSGLaravel\YandexSmartCaptcha;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class YandexSmartCaptchaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/yandex-smart-captcha.php' => config_path('yandex-smart-captcha.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'yandex-smart-captcha');

        Blade::directive('yandexSmartCaptchaScript', function ($functionName = null) {
            if (!empty($functionName)) {
                return "<?php echo \MSGLaravel\YandexSmartCaptcha\Helpers\FormHelper::extendInitJs($functionName); ?>";
            } else {
                return "<?php echo \MSGLaravel\YandexSmartCaptcha\Helpers\FormHelper::initJs(); ?>";
            }
        });

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'yandex-smart-captcha');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/yandex-smart-captcha.php', 'yandex-smart-captcha'
        );
    }
}