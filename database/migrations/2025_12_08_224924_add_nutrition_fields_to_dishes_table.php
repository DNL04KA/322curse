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
            $table->integer('weight')->nullable()->after('category'); // граммовка в граммах
            $table->integer('calories')->nullable()->after('weight'); // калории
            $table->decimal('protein', 5, 2)->nullable()->after('calories'); // белки в граммах
            $table->decimal('fat', 5, 2)->nullable()->after('protein'); // жиры в граммах
            $table->decimal('carbs', 5, 2)->nullable()->after('fat'); // углеводы в граммах
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropColumn(['weight', 'calories', 'protein', 'fat', 'carbs']);
        });
    }
};
