#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

if [[ ! -f .env ]]; then
    cp .env.example .env
fi

printf "Supabase Database Password (ввод скрыт): "
read -r -s ARENA_SUPABASE_DB_PASSWORD
printf "\n"

export ARENA_SUPABASE_DB_PASSWORD

php <<'PHP'
<?php
$path = getcwd().'/.env';
$contents = file_get_contents($path);
$password = getenv('ARENA_SUPABASE_DB_PASSWORD');
$contents = preg_replace('/^DB_URL=.*\\R?/m', '', $contents);

$values = [
    'DB_CONNECTION' => 'pgsql',
    'DB_HOST' => 'db.xqulqprxhrnrhgpwkebf.supabase.co',
    'DB_PORT' => '5432',
    'DB_DATABASE' => 'postgres',
    'DB_USERNAME' => 'postgres',
    'DB_PASSWORD' => $password,
    'DB_SCHEMA' => 'public',
    'DB_SSLMODE' => 'require',
];

foreach ($values as $key => $value) {
    $escaped = addcslashes((string) $value, "\\\"\n\r");
    $escaped = str_replace('$', '\\$', $escaped);
    $line = $key.'="'.$escaped.'"';
    $pattern = '/^'.preg_quote($key, '/').'=.*/m';

    if (preg_match($pattern, $contents)) {
        $contents = preg_replace($pattern, $line, $contents);
    } else {
        $contents .= "\n".$line;
    }
}

file_put_contents($path, rtrim($contents)."\n");
PHP

unset ARENA_SUPABASE_DB_PASSWORD
php artisan config:clear
php artisan migrate --force
php artisan db:seed --force
php artisan db:show --database=pgsql

printf "\nARENA X подключён к Supabase PostgreSQL. Пароль сохранён только в локальном .env.\n"
