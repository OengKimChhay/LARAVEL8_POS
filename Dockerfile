FROM lorisleiva/laravel-docker:8.0
EXPOSE 8000

WORKDIR /var/www
COPY .  /var/www

RUN composer install
RUN php artisan key:generate
RUN cp .env.example .env

# CMD php artisan --host=0.0.0.0 serve