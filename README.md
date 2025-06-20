# Laravel Yandex Smart Captcha

<p align="center">
<a href="https://packagist.org/packages/msglaravel/yandex-smart-captcha"><img src="https://poser.pugx.org/msglaravel/yandex-smart-captcha/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/msglaravel/yandex-smart-captcha"><img src="https://poser.pugx.org/msglaravel/yandex-smart-captcha/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/msglaravel/yandex-smart-captcha"><img src="https://poser.pugx.org/msglaravel/yandex-smart-captcha/license.svg" alt="License"></a>
</p>

Laravel package for Yandex [Smart Captcha](https://yandex.cloud/ru/docs/smartcaptcha/). This is a lightweight package which focuses on the backend validation of Yandex Smart Captcha.

## Installation


To get started, use Composer to add the package to your project's dependencies:

    composer require msglaravel/yandex-smart-captcha


Add `YANDEX_SMARTCAPTCHA_SITEKEY` and `YANDEX_SMARTCAPTCHA_SECRET` to your `.env` file.

```
YANDEX_SMARTCAPTCHA_SITEKEY=sitekey
YANDEX_SMARTCAPTCHA_SECRET=secret
```

Optionally, you can publish the config file:
```
php artisan vendor:publish --provider="MSGLaravel\YandexSmartCaptcha\YandexSmartCaptchaServiceProvider"
```

## Usage

#### Init Recaptcha Javascript

Желательно добавлять скрипт вызова библиотеки Yandex Smart Captcha в самом верху страницы. Например, в вашем header Blade-шаблоне:

```php
@yandexSmartCaptchaScript                  {{-- Basic use --}}
@yandexSmartCaptchaScript('myOnloadCb')    {{-- With custom onload callback --}}
```

#### Forms

Insert the captcha widget into your form using Blade include:

```html
    @include('yandex-smart-captcha::captcha')
```

If you need a version with a custom onload function, use:
```html
    @include('yandex-smart-captcha::captcha-extend')
```
Use it together with ``@yandexSmartCaptchaScript('onloadFunction')``.

```html
<form method="post" action="/register">
    @include('yandex-smart-captcha::captcha')
    <input type="submit" value="Register"></input>
</form>
```

#### Validation

In your controller:

```php
use MSGLaravel\YandexSmartCaptcha\Rules\YandexCaptchaRule;

$request->validate([
    // ...
    'smart-token' => ['required', new YandexSmartCaptchaRule()],
]);
```

You can customize the error message for the captcha in your controller (applies to all errors of this rule):
```php
use MSGLaravel\YandexSmartCaptcha\Rules\YandexCaptchaRule;

$request->validate(
    [
        'smart-token' => ['required', new YandexSmartCaptchaRule()],
    ],
    [
        'smart-token' => 'Ошибка проверки капчи. Попробуйте ещё раз.',
    ]
);
```

## Advanced

Override templates:
Copy ``captcha.blade.php`` or ``captcha-extend.blade.php`` from
``resources/views/vendor/yandex-smart-captcha/`` to your project for full customization.


## Localization & Custom Error Messages
This package includes localized validation error messages for Yandex Smart Captcha in both Russian and English.

If you want to customize error messages for your project, publish the language files to your application:

```
php artisan vendor:publish --provider="MSGLaravel\YandexSmartCaptcha\YandexSmartCaptchaServiceProvider" --tag=lang
```
This command will copy the language files to:
```
resources/lang/vendor/yandex-smart-captcha/ru/validation.php
resources/lang/vendor/yandex-smart-captcha/en/validation.php
You can now edit these files to override any validation error messages as needed.
```

#### Note:
Laravel will use your published files in ``resources/lang/vendor/yandex-smart-captcha`` instead of the package defaults.