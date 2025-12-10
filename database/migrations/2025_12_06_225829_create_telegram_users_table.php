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
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique(); // Номер телефона в формате +375XXXXXXXXX
            $table->string('chat_id'); // Telegram chat ID
            $table->string('first_name')->nullable(); // Имя пользователя в Telegram
            $table->string('username')->nullable(); // Username в Telegram
            $table->timestamp('verified_at')->nullable(); // Когда пользователь подтвердил связь
            $table->timestamps();

            $table->index(['phone', 'chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_users');
    }
};
