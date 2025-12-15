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
        Schema::table('dishes', function (Blueprint $table) {
            // Удаляем колонки пищевой ценности
            $table->dropColumn(['weight', 'calories', 'protein', 'fat', 'carbs']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            // Восстанавливаем колонки если нужно откатить миграцию
            $table->integer('weight')->nullable()->after('category');
            $table->integer('calories')->nullable()->after('weight');
            $table->decimal('protein', 5, 2)->nullable()->after('calories');
            $table->decimal('fat', 5, 2)->nullable()->after('protein');
            $table->decimal('carbs', 5, 2)->nullable()->after('fat');
        });
    }
};
