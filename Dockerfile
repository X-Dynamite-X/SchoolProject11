# استخدام صورة PHP مع Apache
FROM php:8.2-apache

# تثبيت التبعيات اللازمة
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع
COPY . /var/www/html

# تعيين أذونات المجلدات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تثبيت تبعيات المشروع
RUN composer install --no-dev --optimize-autoloader

# تعيين متغيرات البيئة
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تفعيل mod_rewrite
RUN a2enmod rewrite

# تعيين المنفذ
EXPOSE 80

# أمر التشغيل
CMD ["apache2-foreground"]
