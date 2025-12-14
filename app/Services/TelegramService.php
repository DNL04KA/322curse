<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected ?string $botToken;

    protected ?string $adminChatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
        $this->adminChatId = config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID'));
    }

    /**
     * Получить список всех админ chat ID
     * Можно указать несколько ID через запятую: "123,456,789"
     */
    protected function getAdminChatIds(): array
    {
        if (! $this->adminChatId) {
            return [];
        }

        // Если несколько ID через запятую, разбиваем
        return array_filter(array_map('trim', explode(',', $this->adminChatId)));
    }

    /**
     * Получить токен бота
     */
    public function getBotToken(): ?string
    {
        return $this->botToken;
    }

    /**
     * Отправить уведомление о новом заказе
     */
    public function sendNewOrderNotification(array $orderData): bool
    {
        $message = "🆕 *Новый заказ в FoodOrder*\n\n".
                  "👤 Клиент: {$orderData['customer_name']}\n".
                  "📱 Телефон: `{$orderData['customer_phone']}`\n".
                  "💰 Сумма: {$orderData['total']} BYN\n".
                  "🏠 Адрес: {$orderData['address']}\n\n".
                  "📋 Детали: /admin/orders/{$orderData['id']}";

        return $this->sendMessageToAdmin($message);
    }

    /**
     * Отправить уведомление об изменении статуса заказа
     */
    public function sendOrderStatusUpdate(array $orderData): bool
    {
        $statusEmoji = match ($orderData['status']) {
            'new' => '🆕',
            'preparing' => '👨‍🍳',
            'ready' => '✅',
            'delivering' => '🚚',
            'delivered' => '🎉',
            'cancelled' => '❌',
            default => '📝'
        };

        $message = "{$statusEmoji} *Изменение статуса заказа #{$orderData['id']}*\n\n".
                  "👤 Клиент: {$orderData['customer_name']}\n".
                  "📱 Телефон: `{$orderData['customer_phone']}`\n".
                  "📊 Новый статус: {$orderData['status_text']}\n\n".
                  "📋 Детали: /admin/orders/{$orderData['id']}";

        return $this->sendMessageToAdmin($message);
    }

    /**
     * Отправить сообщение в конкретный чат
     */
    public function sendMessageToChat(string $chatId, string $message, ?array $keyboard = null): bool
    {
        if (! $this->botToken) {
            Log::warning('Telegram bot token not configured');

            return false;
        }

        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true,
        ];

        // Добавляем клавиатуру, если она передана
        if ($keyboard) {
            $payload['reply_markup'] = json_encode($keyboard);
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", $payload);

            if ($response->successful()) {
                Log::info('Telegram message sent to chat successfully', [
                    'chat_id' => $chatId,
                    'response' => $response->json(),
                ]);

                return true;
            } else {
                Log::error('Telegram API error', [
                    'chat_id' => $chatId,
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('Telegram service exception', [
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }

    /**
     * Отправить сообщение админу (всем админам если их несколько)
     */
    public function sendMessageToAdmin(string $message): bool
    {
        $chatIds = $this->getAdminChatIds();

        if (empty($chatIds)) {
            Log::warning('Admin chat IDs not configured');

            return false;
        }

        $success = true;
        foreach ($chatIds as $chatId) {
            if (! $this->sendMessageToChat($chatId, $message)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Отправить сообщение админу (устаревший метод, используйте sendMessageToAdmin)
     */
    protected function sendMessage(string $message): bool
    {
        return $this->sendMessageToAdmin($message);
    }

    /**
     * Проверить настройки бота
     */
    public function testBot(): array
    {
        if (! $this->botToken) {
            return ['success' => false, 'message' => 'Bot token not configured'];
        }

        try {
            $response = Http::get("https://api.telegram.org/bot{$this->botToken}/getMe");

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'bot_name' => $data['result']['first_name'] ?? 'Unknown',
                    'bot_username' => $data['result']['username'] ?? 'Unknown',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Bot token is invalid',
                    'response' => $response->json(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: '.$e->getMessage(),
            ];
        }
    }
}
