# Запуск проекта

1. ```bash
   docker compose up -d
   ```

2. ```bash
   cp .env.example .env
   ```

3. Поместить файл `google-service-account.json` в:

   ```
   storage/app/google-service-account.json
   ```

   Или указать свой путь в `.env` через `GOOGLE_APPLICATION_CREDENTIALS`.

4. Настроить `.env` при необходимости.

5. ```bash
   php artisan key:generate
   ```

6. ```bash
   composer install
   ```
