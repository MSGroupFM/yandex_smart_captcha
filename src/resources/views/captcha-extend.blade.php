<div id="customYandexSmartCaptchaId"></div>

<script>
    function onloadFunction() {
        if (window.smartCaptcha) {
            const container = document.getElementById('customYandexSmartCaptchaId');

            const widgetId = window.smartCaptcha.render(container, {
                sitekey: '{{ config('yandex-smart-captcha.sitekey') }}',
                hl: '{{ app()->getLocale() }}',
            });
        }
    }
</script>