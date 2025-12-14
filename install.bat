@echo off
chcp 65001 >nul
cls

echo ═══════════════════════════════════════════════════
echo 🍽️  FoodOrder - Автоматическая установка (Windows)
echo ═══════════════════════════════════════════════════
echo.

echo 🔍 Проверка установленных программ...
echo.

REM Проверка PHP
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ PHP не установлен. Установите XAMPP или PHP 8.2+
    pause
    exit /b 1
)
echo ✅ PHP установлен

REM Проверка Composer
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Composer не установлен. Скачайте с https://getcomposer.org
    pause
    exit /b 1
)
echo ✅ Composer установлен

REM Проверка Node.js
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js не установлен. Скачайте с https://nodejs.org
    pause
    exit /b 1
)
echo ✅ Node.js установлен

echo.
echo 📦 Установка зависимостей...
echo.

REM Установка PHP зависимостей
echo → Установка PHP пакетов (Composer)...
call composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorlevel% neq 0 (
    echo ❌ Ошибка установки Composer пакетов
    pause
    exit /b 1
)

REM Установка JavaScript зависимостей
echo → Установка JavaScript пакетов (npm)...
call npm install
if %errorlevel% neq 0 (
    echo ❌ Ошибка установки npm пакетов
    pause
    exit /b 1
)

echo.
echo ⚙️  Настройка окружения...
echo.

REM Создание .env файла
if not exist .env (
    echo → Создание файла .env...
    copy .env.example .env >nul
    echo ✅ Файл .env создан
) else (
    echo ℹ️  Файл .env уже существует
)

REM Генерация ключа приложения
echo → Генерация ключа приложения...
php artisan key:generate --ansi

echo.
echo ═══════════════════════════════════════════════════
echo 🗄️  НАСТРОЙКА БАЗЫ ДАННЫХ
echo ═══════════════════════════════════════════════════
echo.
echo 📝 ВАМ НУЖНО:
echo.
echo 1. Открыть phpMyAdmin (http://localhost/phpmyadmin)
echo 2. Создать базу данных с именем: food_order
echo    (кодировка: utf8mb4_unicode_ci)
echo.
echo 3. Открыть файл .env в блокноте и настроить:
echo    DB_CONNECTION=mysql
echo    DB_HOST=127.0.0.1
echo    DB_PORT=3306
echo    DB_DATABASE=food_order
echo    DB_USERNAME=root
echo    DB_PASSWORD=ваш_пароль_mysql
echo.
echo ═══════════════════════════════════════════════════
echo.

pause
echo.

echo → Создание таблиц в базе данных...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ❌ Ошибка миграций. Проверьте настройки БД в .env
    pause
    exit /b 1
)

echo → Добавление тестовых данных и админа...
php artisan db:seed --force
if %errorlevel% neq 0 (
    echo ❌ Ошибка заполнения данных
    pause
    exit /b 1
)

echo.
echo 🎨 Сборка фронтенда...
call npm run build
if %errorlevel% neq 0 (
    echo ❌ Ошибка сборки фронтенда
    pause
    exit /b 1
)

echo.
echo 🔗 Создание символической ссылки для хранилища...
php artisan storage:link

echo.
echo ═══════════════════════════════════════════════════
echo ✅ УСТАНОВКА ЗАВЕРШЕНА!
echo ═══════════════════════════════════════════════════
echo.
echo 🎉 FoodOrder готов к использованию!
echo.
echo 📋 Данные администратора:
echo    Телефон: +375293709505
echo    Пароль:  admin123
echo.
echo 🚀 Запуск приложения:
echo    php artisan serve
echo.
echo 🌐 После запуска откройте в браузере:
echo    http://127.0.0.1:8000
echo.
echo 📖 Полная инструкция: INSTALLATION.md
echo.
echo ❤️  Приятного использования!
echo.
pause

