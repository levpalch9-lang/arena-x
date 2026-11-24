# Подключение ARENA X к Supabase PostgreSQL

Проект использует Laravel 12 и прямое серверное подключение к PostgreSQL Supabase. Ключи Supabase не нужны в браузере: запросы выполняет Laravel.

## Локальная настройка

1. Скопируйте значения из `deployment/supabase-db.env.example` в локальный `.env` в корне проекта.
2. В Supabase Dashboard откройте проект `arena-x` → **Connect** → **Connection string**. Если прямое подключение недоступно в вашей сети, используйте Session Pooler и его host/port/username из панели.
3. Впишите Database Password только в локальную переменную `DB_PASSWORD`. Не вставляйте её в исходники и не отправляйте в переписку.
4. Выполните `php artisan config:clear`.
5. Выполните `php artisan migrate --force`. Базовая миграция проверяет существующие таблицы и не пересоздаёт уже заполненную схему Supabase.

## Важно

- Не запускайте `php artisan migrate:fresh` на Supabase: эта команда удаляет таблицы и данные.
- Не используйте `service_role` / secret key в HTML, JavaScript или Vite-переменных.
- RLS включён на таблицах. Laravel работает через доверенное серверное подключение к PostgreSQL; пароль хранится только в `.env`.
- Для локального офлайн-запуска можно оставить SQLite из корневого `.env.example`.
- Перед публикацией задайте `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` с публичным адресом и включите HTTPS.
