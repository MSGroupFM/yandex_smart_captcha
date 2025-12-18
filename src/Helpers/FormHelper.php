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
	public static function getEndpoint(): string
	{
		$endpoint = trim((string) config('yandex-smart-captcha.endpoint', 'https://smartcaptcha.cloud.yandex.ru'));

		// Если схема не указана — добавляем https://
		if (!preg_match('#^https?://#i', $endpoint))
		{
			$endpoint = 'https://'.$endpoint;
		}

		// Убираем возможный слэш в конце
		return rtrim($endpoint, '/');
	}

	public static function initJs(): string
	{
		$endpoint = static::getEndpoint();

		return sprintf(
			'<script src="%s/captcha.js" defer></script>',
			e($endpoint)
		);
	}

	public static function extendInitJs(string $functionName = 'onloadFunction'): string
	{
		$endpoint = static::getEndpoint();

		return sprintf(
			'<script src="%s/captcha.js?render=onload&onload=%s"></script>',
			e($endpoint),
			e($functionName)
		);
	}
}
