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

// دالة لجلب CSRF token
function getCSRFToken() {
    // أولاً جرب من meta tag
    const metaToken = $('meta[name="csrf-token"]').attr('content');
    if (metaToken) {
        return metaToken;
    }

    // ثم جرب من XSRF cookie
    const xsrfToken = getCookie('XSRF-TOKEN');
    if (xsrfToken) {
        return decodeURIComponent(xsrfToken);
    }

    return null;
}

// دالة لإعادة تحديث CSRF token
async function refreshCSRFToken() {
    try {
        await $.ajax({
            url: baseUrl + "/sanctum/csrf-cookie",
            method: "GET",
            xhrFields: { withCredentials: true },
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // تحديث meta tag إذا كان موجوداً
        const newToken = getCookie('XSRF-TOKEN');
        if (newToken) {
            $('meta[name="csrf-token"]').attr('content', decodeURIComponent(newToken));
        }

        return true;
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
        return false;
    }
}

// دالة مساعدة لإرسال طلبات AJAX مع CSRF
window.ajaxWithCSRF = async function(options) {
    // جلب CSRF token أولاً
    try {
        await $.ajax({
            url: baseUrl + "/sanctum/csrf-cookie",
            method: "GET",
            xhrFields: { withCredentials: true }
        });
    } catch (error) {
        console.warn('Failed to fetch CSRF cookie:', error);
    }

    // الحصول على CSRF token
    const csrfToken = getCSRFToken();

    // إعداد headers افتراضية
    const defaultHeaders = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    // إضافة CSRF token إلى headers
    if (csrfToken) {
        defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
        defaultHeaders['X-XSRF-TOKEN'] = csrfToken;
    }

    // دمج headers المخصصة مع الافتراضية
    options.headers = Object.assign(defaultHeaders, options.headers || {});

    // إضافة withCredentials
    options.xhrFields = Object.assign({ withCredentials: true }, options.xhrFields || {});

    // إضافة baseUrl إذا لم يكن url كامل
    if (options.url && options.url.indexOf("http") !== 0) {
        options.url = baseUrl + options.url;
    }

    return $.ajax(options);
};

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

        // إضافة CSRF token
        const csrfToken = getCSRFToken();
        if (csrfToken) {
            jqXHR.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            jqXHR.setRequestHeader('X-XSRF-TOKEN', csrfToken);
        }

        // إضافة headers أساسية
        jqXHR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        jqXHR.setRequestHeader('Accept', 'application/json');

        // للطلبات التي تحتاج CSRF protection
        if (settings.type && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(settings.type.toUpperCase())) {
            // التأكد من وجود CSRF token
            if (!csrfToken) {
                console.warn('CSRF token not found for', settings.type, 'request to', settings.url);
            }
        }
    },
    error: function(xhr, status, error) {
        // إذا كان الخطأ 419 (CSRF Token Mismatch)
        if (xhr.status === 419) {
            console.warn('CSRF token expired, attempting to refresh...');

            // محاولة تحديث CSRF token وإعادة المحاولة
            refreshCSRFToken().then(function(success) {
                if (success) {
                    console.log('CSRF token refreshed successfully');
                    // يمكن إعادة تشغيل الطلب هنا إذا لزم الأمر
                } else {
                    console.error('Failed to refresh CSRF token');
                }
            });
        }
    }
});

// تحديث CSRF token عند تحميل الصفحة
$(document).ready(function() {
    // تحديث CSRF token إذا لم يكن موجوداً
    if (!getCSRFToken()) {
        refreshCSRFToken();
    }
});
</script>

</body>

</html>
