<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = [
        'phone',
        'chat_id',
        'first_name',
        'username',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Найти пользователя по номеру телефона
     */
    public static function findByPhone(string $phone): ?self
    {
        return self::where('phone', $phone)->first();
    }

    /**
     * Найти или создать пользователя по chat_id
     */
    public static function findOrCreateByChatId(string $chatId, array $data = []): self
    {
        return self::firstOrCreate(
            ['chat_id' => $chatId],
            $data
        );
    }
}
