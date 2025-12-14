#!/bin/bash

# 🍽️ FoodOrder - Автоматическая установка
# Этот скрипт настроит весь проект автоматически

echo "╔════════════════════════════════════════╗"
echo "║   🍽️  FoodOrder - Автоустановка   🍽️   ║"
echo "╔════════════════════════════════════════╗"
echo ""

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Функция для вывода ошибок
error() {
    echo -e "${RED}❌ ОШИБКА: $1${NC}"
    exit 1
}

# Функция для вывода успеха
success() {
    echo -e "${GREEN}✅ $1${NC}"
}

# Функция для вывода предупреждений
warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

# Функция для вывода информации
info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

echo ""
echo "🔍 Проверяю установленные программы..."
echo ""

# Проверка PHP
if ! command -v php &> /dev/null; then
    error "PHP не установлен! Установите PHP: brew install php"
fi
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -f1-2 -d".")
success "PHP $PHP_VERSION установлен"

# Проверка Composer
if ! command -v composer &> /dev/null; then
    error "Composer не установлен! Установите Composer: brew install composer"
fi
success "Composer установлен"

# Проверка Node.js
if ! command -v node &> /dev/null; then
    error "Node.js не установлен! Установите Node.js: brew install node"
fi
NODE_VERSION=$(node -v)
success "Node.js $NODE_VERSION установлен"

# Проверка npm
if ! command -v npm &> /dev/null; then
    error "npm не установлен! Переустановите Node.js"
fi
success "npm установлен"

# Проверка MySQL
if ! command -v mysql &> /dev/null; then
    warning "MySQL не найден в PATH"
    # Проверяем стандартное место установки
    if [ -f "/usr/local/mysql/bin/mysql" ]; then
        export PATH="/usr/local/mysql/bin:$PATH"
        success "MySQL найден в /usr/local/mysql/bin/"
    else
        error "MySQL не установлен! Установите MySQL Community Server"
    fi
else
    success "MySQL установлен"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📦 Устанавливаю зависимости проекта..."
echo ""

# Установка PHP зависимостей
info "Устанавливаю PHP зависимости (Composer)..."
if composer install --no-interaction; then
    success "PHP зависимости установлены"
else
    error "Не удалось установить PHP зависимости"
fi

echo ""

# Установка Node.js зависимостей
info "Устанавливаю Node.js зависимости (npm)..."
if npm install; then
    success "Node.js зависимости установлены"
else
    error "Не удалось установить Node.js зависимости"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "⚙️  Настраиваю конфигурацию..."
echo ""

# Создание .env файла
if [ ! -f .env ]; then
    info "Создаю файл .env..."
    cp .env.example .env
    success "Файл .env создан"
else
    warning "Файл .env уже существует, пропускаю"
fi

# Генерация ключа приложения
info "Генерирую ключ приложения..."
php artisan key:generate --no-interaction
success "Ключ приложения сгенерирован"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🗄️  Настройка базы данных..."
echo ""

# Запрос параметров MySQL
info "Сейчас нужно настроить подключение к MySQL"
echo ""

read -p "📝 Введите имя базы данных [food_order]: " DB_NAME
DB_NAME=${DB_NAME:-food_order}

read -p "📝 Введите пользователя MySQL [root]: " DB_USER
DB_USER=${DB_USER:-root}

read -sp "🔑 Введите пароль MySQL: " DB_PASS
echo ""

# Проверка подключения к MySQL
info "Проверяю подключение к MySQL..."
if mysql -u"$DB_USER" -p"$DB_PASS" -e "SELECT 1;" &> /dev/null; then
    success "Подключение к MySQL успешно"
else
    error "Не удалось подключиться к MySQL. Проверьте имя пользователя и пароль"
fi

# Создание базы данных
info "Создаю базу данных $DB_NAME..."
mysql -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
if [ $? -eq 0 ]; then
    success "База данных $DB_NAME создана"
else
    warning "База данных возможно уже существует"
fi

# Обновление .env файла
info "Обновляю настройки базы данных в .env..."
sed -i.bak "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
sed -i.bak "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i.bak "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i.bak "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env
rm -f .env.bak
success "Настройки базы данных обновлены"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🏗️  Создаю таблицы и заполняю данными..."
echo ""

# Запуск миграций
info "Создаю таблицы в базе данных..."
if php artisan migrate --no-interaction --force; then
    success "Таблицы созданы"
else
    error "Не удалось создать таблицы"
fi

echo ""

# Заполнение тестовыми данными
info "Заполняю базу тестовыми данными..."
if php artisan db:seed --no-interaction --force; then
    success "Тестовые данные добавлены"
else
    warning "Не удалось добавить тестовые данные"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🎨 Собираю фронтенд..."
echo ""

# Сборка фронтенда
info "Компилирую CSS и JavaScript..."
if npm run build; then
    success "Фронтенд собран"
else
    error "Не удалось собрать фронтенд"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║         ✅ УСТАНОВКА ЗАВЕРШЕНА! ✅      ║${NC}"
echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
echo ""

echo "🎉 Проект FoodOrder готов к использованию!"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🚀 КАК ЗАПУСТИТЬ СЕРВЕР:"
echo ""
echo "   1. Запусти сервер командой:"
echo -e "      ${BLUE}php artisan serve${NC}"
echo ""
echo "   2. Открой в браузере:"
echo -e "      ${BLUE}http://127.0.0.1:8000${NC}"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "🔑 ДАННЫЕ ДЛЯ ВХОДА:"
echo ""
echo "   👤 Администратор:"
echo -e "      Телефон: ${GREEN}+375293709505${NC}"
echo -e "      Пароль:  ${GREEN}admin123${NC}"
echo ""
echo "   👥 Обычный пользователь:"
echo "      Зарегистрируйся на сайте"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "💡 ПОЛЕЗНЫЕ КОМАНДЫ:"
echo ""
echo "   • Запустить сервер:"
echo -e "     ${BLUE}php artisan serve${NC}"
echo ""
echo "   • Создать нового админа:"
echo -e "     ${BLUE}php artisan make:admin${NC}"
echo ""
echo "   • Пересоздать базу данных:"
echo -e "     ${BLUE}php artisan migrate:fresh --seed${NC}"
echo ""
echo "   • Пересобрать фронтенд:"
echo -e "     ${BLUE}npm run build${NC}"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📚 ДОКУМЕНТАЦИЯ:"
echo ""
echo "   • ПРОСТАЯ_ИНСТРУКЦИЯ.md - как пользоваться"
echo "   • README.md - полная документация"
echo "   • INSTALLATION.md - подробная установка"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${GREEN}✨ ПРИЯТНОГО ИСПОЛЬЗОВАНИЯ! ✨${NC}"
echo ""

