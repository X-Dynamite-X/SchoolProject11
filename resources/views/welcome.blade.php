<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var baseUrl = "https://reptile-pumped-bear.ngrok-free.app/SchoolProject11";

// دالة لجلب قيمة الكوكي
function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return match[2];
    return null;
}

// إعدادات AJAX global
$.ajaxSetup({
    xhrFields: {
        withCredentials: true
    },
    beforeSend: function(jqXHR, settings) {
        // إضافة baseUrl إذا لم يكن url كامل
        if (settings.url.indexOf("http") !== 0) {
            settings.url = baseUrl + settings.url;
        }

        // إضافة CSRF token من meta
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            jqXHR.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        }

        // إضافة XSRF token من كوكي
        const xsrfToken = getCookie('XSRF-TOKEN');
        if (xsrfToken) {
            jqXHR.setRequestHeader('X-XSRF-TOKEN', decodeURIComponent(xsrfToken));
        }

        // إضافة Content-Type للطلبات POST
        if (settings.type === 'POST' || settings.type === 'PUT' || settings.type === 'PATCH') {
            if (!settings.contentType && !settings.data instanceof FormData) {
                jqXHR.setRequestHeader('Content-Type', 'application/json');
            }
        }
    }
});
</script>

</body>

</html>
