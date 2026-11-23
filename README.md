# ARENA X

Платформа для проведения локальных киберспортивных турниров.

## Технологии
- Laravel 12 / PHP 8.2+
- Blade, Vite, CSS
- PostgreSQL (Supabase-compatible)

## Локальный запуск на macOS

Требуются PHP 8.2+, Composer, Node.js 20+ и npm.

```bash
git clone https://github.com/levpalch9-lang/arena-x.git
cd arena-x
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan serve
```

Откройте http://127.0.0.1:8000.

Перед подключением к общей базе заполните параметры PostgreSQL в `.env`.
Никогда не добавляйте реальные пароли и ключи Supabase в Git.

## Статус

Репозиторий инициализирован. Реализация модулей, подключение БД и тестирование запуска выполняются поэтапно; не считайте демо-аккаунты или функции готовыми, пока они не проверены.
