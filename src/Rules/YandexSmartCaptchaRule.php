<?php
/*
 * @package     yandex_smart_captcha
 * @author      Vladislav Tsygankov
 * @copyright   Copyright (c) 2025 MSGru. All rights reserved.
 * @license     MIT
 * @link        https://msgru.com/
 */

namespace MSGLaravel\YandexSmartCaptcha\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class YandexSmartCaptchaRule implements Rule
{
    protected $errorCode = null;

    public function passes($attribute, $value)
    {
        if (!$value) {
            $this->errorCode = 'missing_token';
            return false;
        }

        $serverKey = config('yandex-smart-captcha.secret');
        $response  = Http::asForm()->post('https://smartcaptcha.yandexcloud.net/validate', [
            'secret' => $serverKey,
            'token'  => $value,
            'ip'     => request()->ip(),
        ]);

        if (!$response->ok()) {
            $this->errorCode = 'service_unavailable';
            return false;
        }

        $data = $response->json();

        if (!isset($data['status'])) {
            $this->errorCode = 'invalid_response';
            return false;
        }

        if ($data['status'] === 'ok') {
            return true;
        }

        // Можно детектировать коды ошибок Яндекса если они есть в ответе
        if (!empty($data['error_codes'])) {
            $this->errorCode = $data['error_codes'][0];
        } else {
            $this->errorCode = $data['status'];
        }

        return false;
    }

    public function message()
    {
        $key = 'yandex-smart-captcha::validation.yandex_smart_captcha.' . ($this->errorCode ?? 'default');
        $message = trans($key);

        // Fallback if translation not found
        if ($message === $key) {
            $message = trans('yandex-smart-captcha::validation.yandex_smart_captcha.default');
        }

        return $message;
    }
}
