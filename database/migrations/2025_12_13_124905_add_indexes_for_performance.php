<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Индексы для таблицы users
        Schema::table('users', function (Blueprint $table) {
            $table->index('phone'); // Частый поиск по телефону при логине
            $table->index('is_admin'); // Фильтрация админов
            $table->index('email'); // Поиск по email
        });

        // Индексы для таблицы orders
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id'); // Получение заказов пользователя
            $table->index('status'); // Фильтрация по статусу
            $table->index('created_at'); // Сортировка по дате
            $table->index(['user_id', 'status']); // Комбинированный индекс
        });

        // Индексы для таблицы dishes
        Schema::table('dishes', function (Blueprint $table) {
            $table->index('restaurant_id'); // Получение блюд ресторана
            $table->index('is_available'); // Фильтрация доступных
            $table->index('category'); // Группировка по категориям
            $table->index(['restaurant_id', 'is_available']); // Комбинированный
        });

        // Индексы для таблицы order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id'); // Получение элементов заказа
            $table->index('dish_id'); // Статистика по блюдам
        });

        // Индексы для таблицы restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->index('is_active'); // Фильтрация активных ресторанов
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['is_admin']);
            $table->dropIndex(['email']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'status']);
        });

        Schema::table('dishes', function (Blueprint $table) {
            $table->dropIndex(['restaurant_id']);
            $table->dropIndex(['is_available']);
            $table->dropIndex(['category']);
            $table->dropIndex(['restaurant_id', 'is_available']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['dish_id']);
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
