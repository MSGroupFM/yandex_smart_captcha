<?php
/*
 * @package     yandex_smart_captcha
 * @author      Vladislav Tsygankov
 * @copyright   Copyright (c) 2025 MSGru. All rights reserved.
 * @license     MIT
 * @link        https://msgru.com/
 */

namespace MSGLaravel\YandexSmartCaptcha\Helpers;

class FormHelper
{
    public static function initJs()
    {
        return '<script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>';
    }

    public static function extendInitJs(string $functionName = 'onloadFunction')
    {
        return '<script src="https://smartcaptcha.yandexcloud.net/captcha.js?render=onload&onload=' . e($functionName) .'"></script>';
    }
}