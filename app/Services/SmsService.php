<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmsService
{
    /**
     * Отправить SMS через email (бесплатный способ)
     *
     * Работает с операторами, которые предоставляют SMS через email:
     * - МТС Беларусь: +37529XXXXXXX@sms.mts.by
     * - A1 Беларусь: +37529XXXXXXX@sms.a1.by
     * - life:) Беларусь: +37525XXXXXXX@sms.life.com.by
     */
    public function sendSms(string $phone, string $message): bool
    {
        try {
            // Преобразуем номер телефона в email для SMS
            $email = $this->phoneToEmail($phone);

            if (! $email) {
                Log::warning("Cannot convert phone {$phone} to SMS email");

                return false;
            }

            // Отправляем email как SMS
            Mail::raw($message, function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('FoodOrder') // Тема не важна для SMS
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info("SMS sent to {$phone} via email {$email}");

            return true;

        } catch (\Exception $e) {
            Log::error("SMS sending failed for {$phone}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Преобразовать номер телефона в email для SMS
     */
    protected function phoneToEmail(string $phone): ?string
    {
        // Убираем все кроме цифр и +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);

        // Убираем + в начале
        $digits = ltrim($cleanPhone, '+');

        // Беларусь - МТС (29)
        if (preg_match('/^37529\d{7}$/', $digits)) {
            $number = substr($digits, 3); // Убираем 375

            return "+{$number}@sms.mts.by";
        }

        // Беларусь - A1 (29, 44)
        if (preg_match('/^375(29|44)\d{7}$/', $digits)) {
            $number = substr($digits, 3); // Убираем 375

            return "+{$number}@sms.a1.by";
        }

        // Беларусь - life:) (25)
        if (preg_match('/^37525\d{7}$/', $digits)) {
            $number = substr($digits, 3); // Убираем 375

            return "+{$number}@sms.life.com.by";
        }

        // Для других операторов/стран можно добавить правила
        Log::info("No SMS email mapping for phone: {$phone}");

        return null;
    }

    /**
     * Проверить поддержку оператора
     */
    public function isOperatorSupported(string $phone): bool
    {
        return $this->phoneToEmail($phone) !== null;
    }

    /**
     * Получить список поддерживаемых операторов
     */
    public function getSupportedOperators(): array
    {
        return [
            'МТС Беларусь' => '+375 29 XXX-XX-XX',
            'A1 Беларусь' => '+375 (29|44) XXX-XX-XX',
            'life:) Беларусь' => '+375 25 XXX-XX-XX',
        ];
    }
}
