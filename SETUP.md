# 📖 Полное руководство по развёртыванию FoodOrder

## ✅ Требования

### Минимальные требования:
- **PHP** 8.1+
- **Composer** 2.0+
- **Node.js** 16+ (для сборки frontend)
- **SQLite** (встроен в PHP) ИЛИ **MySQL** 5.7+

### Проверка требований:
```bash
php --version
composer --version
node --version
npm --version
```

---

## 🚀 Установка (локальная разработка)

### 1. Клонирование проекта
```bash
git clone https://github.com/yourusername/food-order.git
cd food-order
```

### 2. Установка PHP зависимостей
```bash
composer install
```

### 3. Установка Node.js зависимостей
```bash
npm install
```

### 4. Генерация APP_KEY
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Инициализация БД

**SQLite (рекомендуется для локальной разработки):**
```bash
# БД создаётся автоматически при первой миграции
php artisan migrate
```

**MySQL:**
```bash
# Отредактируйте .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodorder
DB_USERNAME=root
DB_PASSWORD=your_password

# Создайте БД в MySQL:
# CREATE DATABASE foodorder;

# Запустите миграции:
php artisan migrate
```

### 6. Запуск сервера
```bash
php artisan serve
```

Приложение будет доступно по: **http://localhost:8000**

---

## 🔧 Конфигурация

### Переменные окружения (.env)

| Переменная | Описание | Пример |
|------------|---------|--------|
| `APP_NAME` | Название приложения | `FoodOrder` |
| `APP_ENV` | Окружение (local/production) | `local` |
| `APP_DEBUG` | Режим отладки | `true` |
| `APP_URL` | URL приложения | `http://localhost:8000` |
| `DB_CONNECTION` | Тип БД (sqlite/mysql) | `sqlite` |
| `DB_DATABASE` | Имя БД (для MySQL) | `foodorder` |
| `TELEGRAM_BOT_TOKEN` | Токен Telegram бота | `123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11` |
| `TELEGRAM_CHAT_ID` | ID чата админа (или несколько через запятую) | `123456789` или `123456789,987654321` |

### Создание .env файла
```bash
# Скопируйте .env.example
cp .env.example .env

# Отредактируйте под ваши нужды
nano .env  # или используйте VS Code
```

---

## 📱 Telegram боты и уведомления

### Создание Telegram бота
1. Откройте [@BotFather](https://t.me/BotFather) в Telegram
2. Отправьте `/newbot`
3. Введите имя и username бота
4. Скопируйте полученный токен

### Получение Telegram ID
```bash
# Способ 1: Через API
# Замените <TOKEN> на ваш токен и отправьте сообщение боту
curl "https://api.telegram.org/bot<TOKEN>/getUpdates"

# Найдите "id" в ответе - это ваш chat_id

# Способ 2: Через бота
# Добавьте бота себе и отправьте /start
# Проверьте логи:
tail -f storage/logs/laravel.log
```

### Конфигурирование
```env
# .env
TELEGRAM_BOT_TOKEN=123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
TELEGRAM_CHAT_ID=987654321
```

### Несколько администраторов
```env
# Разделите несколько chat_id запятыми (без пробелов)
TELEGRAM_CHAT_ID=123456789,987654321,555666777
```

### Тестирование
```bash
php artisan tinker

# Тест подключения
>>> app('TelegramService')->testBot()

# Отправка сообщения
>>> app('TelegramService')->sendMessageToAdmin('Test message')
```

---

## 🎨 Frontend

### Архитектура
- **HTML**: Blade шаблоны (серверный рендеринг)
- **CSS**: Tailwind CSS 4
- **JS**: Vanilla JavaScript + Vite bundler

### Файлы
- `resources/views/` - Blade шаблоны
- `resources/css/app.css` - Стили (Tailwind)
- `resources/js/app.js` - JavaScript точка входа

### Сборка для разработки (с hot reload)
```bash
npm run dev
```

### Сборка для продакшена
```bash
npm run build
```

Бундл появится в `public/build/`

---

## 📊 База данных

### Миграции
```bash
# Запустить все миграции
php artisan migrate

# Откатить последнюю миграцию
php artisan migrate:rollback

# Откатить всё и пересоздать
php artisan migrate:fresh

# Откатить + пересоздать + заполнить данными
php artisan migrate:fresh --seed
```

### Структура таблиц
```
users
├── id
├── name
├── phone (unique)
├── email (nullable)
├── password
├── is_admin
└── timestamps

restaurants
├── id
├── name
├── phone
├── address
└── timestamps

dishes
├── id
├── restaurant_id (FK → restaurants)
├── name
├── description
├── price
├── image_url
└── timestamps

orders
├── id
├── user_id (FK → users, nullable)
├── restaurant_id (FK → restaurants)
├── customer_name
├── customer_phone
├── customer_email (nullable)
├── status (pending/confirmed/preparing/ready/delivered/cancelled)
├── total_price
└── timestamps

order_items
├── id
├── order_id (FK → orders)
├── dish_id (FK → dishes)
├── quantity
├── price_at_purchase
└── timestamps

telegram_users
├── id
├── user_id (FK → users, nullable)
├── telegram_id (unique)
├── telegram_name
└── timestamps
```

---

## 🧪 Тестирование

### Запуск тестов
```bash
php artisan test
```

### Создание тестовых данных
```bash
php artisan tinker

# Создать пользователя
>>> use App\Models\User; use Illuminate\Support\Facades\Hash;
>>> User::create(['name' => 'Test User', 'phone' => '+375291234567', 'password' => Hash::make('password123'), 'is_admin' => true])

# Создать ресторан
>>> use App\Models\Restaurant;
>>> Restaurant::create(['name' => 'Test Restaurant', 'phone' => '+375291234567', 'address' => 'Test Address'])
```

---

## 🚀 Продакшен (Production)

### Подготовка сервера
```bash
# 1. SSH на сервер и клонируйте репозиторий
ssh user@your-server.com
cd /var/www
git clone https://github.com/yourusername/food-order.git
cd food-order

# 2. Установите зависимости
composer install --no-dev --optimize-autoloader
npm install --only=production
npm run build

# 3. Настройте права доступа
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .

# 4. Скопируйте .env и настройте
cp .env.example .env
nano .env  # Отредактируйте переменные

# 5. Генерируйте ключ
php artisan key:generate

# 6. Запустите миграции
php artisan migrate --force
```

### Оптимизация для продакшена
```bash
# Кэшируйте конфигурацию
php artisan config:cache

# Кэшируйте маршруты
php artisan route:cache

# Кэшируйте представления
php artisan view:cache

# Очистите кэш
php artisan cache:clear
```

### Веб-сервер (Nginx)
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/food-order/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### SSL сертификат (Let's Encrypt)
```bash
sudo certbot certonly --webroot -w /var/www/food-order/public -d yourdomain.com
```

### Логи и мониторинг
```bash
# Реальное время логи
tail -f storage/logs/laravel.log

# Вывести последние 100 строк
tail -100 storage/logs/laravel.log

# Поиск ошибок
grep -i error storage/logs/laravel.log
```

---

## 🐛 Решение проблем

### Проблема: "No such file or directory" для database.sqlite
**Решение:**
```bash
php artisan migrate
# или
touch database/database.sqlite
php artisan migrate
```

### Проблема: "Class does not exist"
**Решение:**
```bash
composer dump-autoload
php artisan cache:clear
```

### Проблема: Права доступа (Permission denied)
**Решение:**
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/build  # если собираете npm run build
```

### Проблема: Telegram уведомления не приходят
**Решение:**
```bash
# 1. Проверьте токен
php artisan tinker
>>> app('TelegramService')->testBot()

# 2. Проверьте логи
tail -f storage/logs/laravel.log

# 3. Проверьте что вы добавили бота и отправили ему сообщение
# 4. Убедитесь что TELEGRAM_CHAT_ID правильный
```

---

## 📚 Дополнительные команды

```bash
# Запуск локального сервера на определённом порту
php artisan serve --port=8080

# Запуск с другим хостом
php artisan serve --host=0.0.0.0 --port=8000

# Очистка всех кэшей
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Генерация документации
php artisan ide-helper:generate

# Формат кода (если установлен pint)
./vendor/bin/pint

# Анализ кода (если установлен phpstan)
./vendor/bin/phpstan analyse
```

---

## 🤝 Поддержка

Если возникают проблемы:
1. Проверьте README.md
2. Посмотрите TELEGRAM_SETUP.md
3. Прочитайте логи: `tail -f storage/logs/laravel.log`
4. Создайте Issue на GitHub

---

**Последнее обновление:** 10 декабря 2025
**Версия:** 1.0.0
