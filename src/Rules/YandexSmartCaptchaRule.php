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
use GuzzleHttp\Client;
use MSGLaravel\YandexSmartCaptcha\Helpers\FormHelper;

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
        $endPoint = FormHelper::getEndpoint();

        $client = new Client([
			'base_uri' => $endPoint,
            'timeout' => 5, // seconds (можно изменить по необходимости)
            'http_errors' => false // чтобы не бросало исключения на 4xx/5xx
        ]);

        try {
            $response = $client->post('/validate', [
                'form_params' => [
                    'secret' => $serverKey,
                    'token'  => $value,
                    'ip'     => request()->ip(),
                ]
            ]);
        } catch (\Exception $e) {
            $this->errorCode = 'service_unavailable';
            return false;
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode < 200 || $statusCode >= 300) {
            $this->errorCode = 'service_unavailable';
            return false;
        }

        $data = json_decode($response->getBody(), true);

        if (!isset($data['status'])) {
            $this->errorCode = 'invalid_response';
            return false;
        }

        if ($data['status'] === 'ok') {
            return true;
        }

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
