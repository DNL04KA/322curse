<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Обработка webhook от Telegram
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('Telegram webhook received', $data);

        if (isset($data['message'])) {
            $this->handleMessage($data['message']);
        } elseif (isset($data['callback_query'])) {
            $this->handleCallbackQuery($data['callback_query']);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Обработка нажатий на inline кнопки
     */
    protected function handleCallbackQuery(array $callbackQuery)
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $data = $callbackQuery['data'];
        $messageId = $callbackQuery['message']['message_id'];

        // Получаем пользователя
        $telegramUser = TelegramUser::findByChatId($chatId);

        if (!$telegramUser) {
            $this->telegramService->sendMessageToChat($chatId, "❌ Ошибка: пользователь не найден");
            return;
        }

        // Обрабатываем callback данные
        if ($data === 'show_commands') {
            $this->handleHelpCommand($telegramUser);
        } elseif ($data === 'show_status') {
            $this->handleStatusCommand($telegramUser);
        } elseif ($data === 'show_about') {
            $this->handleAboutCommand($telegramUser);
        }

        // Отвечаем на callback query
        $this->answerCallbackQuery($callbackQuery['id']);
    }

    /**
     * Ответ на callback query
     */
    protected function answerCallbackQuery(string $callbackQueryId, string $text = "")
    {
        if (!$this->telegramService->getBotToken()) {
            return;
        }

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$this->telegramService->getBotToken()}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
                'text' => $text,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to answer callback query', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Обработка входящего сообщения
     */
    protected function handleMessage(array $message)
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';
        $user = $message['from'] ?? [];

        // Сохраняем/обновляем информацию о пользователе
        $telegramUser = TelegramUser::findOrCreateByChatId($chatId, [
            'first_name' => $user['first_name'] ?? null,
            'username' => $user['username'] ?? null,
        ]);

        // Обрабатываем команды
            if (str_starts_with($text, '/start')) {
                $this->handleStartCommand($telegramUser);
            } elseif (str_starts_with($text, '/help') || str_starts_with($text, '/commands')) {
                $this->handleHelpCommand($telegramUser);
            } elseif (str_starts_with($text, '/status')) {
                $this->handleStatusCommand($telegramUser);
            } elseif (str_starts_with($text, '/about')) {
                $this->handleAboutCommand($telegramUser);
            } else {
                $this->handleUnknownCommand($telegramUser);
            }
    }

    /**
     * Обработка команды /start
     */
    protected function handleStartCommand(TelegramUser $telegramUser)
    {
        $message = "🍕 *Добро пожаловать в FoodOrder Bot!*\n\n".
                  "🤖 Я бот для администрации FoodOrder.\n\n".
                  "📢 *Мои функции:*\n".
                  "• Получение уведомлений о новых заказах\n".
                  "• Статусы заказов в реальном времени\n".
                  "• Управление рестораном\n\n".
                  "👨‍💼 Этот бот предназначен только для администрации.\n".
                  "Если вы администратор, используйте кнопки ниже:";

        // Создаем inline клавиатуру
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '📋 Показать команды', 'callback_data' => 'show_commands'],
                    ['text' => '📊 Мой статус', 'callback_data' => 'show_status']
                ],
                [
                    ['text' => 'ℹ️ О сервисе', 'callback_data' => 'show_about']
                ]
            ]
        ];

        $this->telegramService->sendMessageToChat($telegramUser->chat_id, $message, $keyboard);
    }

    /**
     * Установка webhook для Telegram бота
     */
    public function setWebhook(Request $request)
    {
        $webhookUrl = $request->get('url', url('/telegram/webhook'));
        $botToken = config('services.telegram.bot_token');

        if (! $botToken) {
            return response()->json(['error' => 'Bot token not configured'], 500);
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
                'allowed_updates' => ['message'],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Webhook set successfully', ['response' => $data]);

                return response()->json([
                    'success' => true,
                    'message' => 'Webhook установлен успешно',
                    'webhook_url' => $webhookUrl,
                    'response' => $data,
                ]);
            } else {
                Log::error('Failed to set webhook', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось установить webhook',
                    'response' => $response->json(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Webhook setup exception', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при установке webhook: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Обработка команды /help или /commands
     */
    protected function handleHelpCommand(TelegramUser $telegramUser)
    {
        $message = "🤖 *FoodOrder Bot - все доступные команды*\n\n".
                  "📋 *ОСНОВНЫЕ КОМАНДЫ:*\n".
                  "🚀 `/start` - Начать работу с ботом и получить приветствие\n".
                  "📝 `/register +375XXXXXXXXX` - Зарегистрировать номер телефона для уведомлений\n".
                  "📊 `/status` - Проверить статус привязки номера телефона\n".
                  "ℹ️ `/about` - Информация о боте и сервисе\n".
                  "❓ `/help` или `/commands` - Показать этот список команд\n\n".

                  "🎯 *НАЗНАЧЕНИЕ БОТА:*\n".
                  "Этот бот предназначен для администрации FoodOrder.\n".
                  "Он автоматически отправляет уведомления о новых заказах.\n\n".

                  "📱 *УВЕДОМЛЕНИЯ АДМИНИСТРАЦИИ:*\n".
                  "🆕 Новый заказ поступил\n".
                  "👨‍🍳 Заказ начал готовиться\n".
                  "🚚 Заказ передан курьеру\n".
                  "✅ Заказ доставлен\n\n".

                  "👨‍💼 *ДЛЯ АДМИНИСТРАТОРОВ:*\n".
                  "• Автоматические уведомления о заказах\n".
                  "• Мониторинг статуса заказов\n".
                  "• Управление через админ-панель\n\n".

                  "🔗 Сайт: foodorder.com\n".
                  "👑 Админка: foodorder.com/admin";

        $this->telegramService->sendMessageToChat($telegramUser->chat_id, $message, null);
    }

    /**
     * Обработка команды /status
     */
    protected function handleStatusCommand(TelegramUser $telegramUser)
    {
        $statusMessage = "📊 *Статус бота*\n\n";

        $statusMessage .= "🤖 *FoodOrder Bot*\n";
        $statusMessage .= "👨‍💼 *Назначение:* Уведомления для администрации\n";
        $statusMessage .= "📱 *Telegram ID:* `{$telegramUser->chat_id}`\n";
        $statusMessage .= "👤 *Пользователь:* " . ($telegramUser->username ?: $telegramUser->first_name ?: 'Неизвестен') . "\n\n";

        $statusMessage .= "🔔 *Функции:*\n";
        $statusMessage .= "✅ Получение уведомлений о заказах\n";
        $statusMessage .= "✅ Мониторинг статуса заказов\n";
        $statusMessage .= "✅ Автоматические оповещения\n\n";

        $statusMessage .= "💡 *Как настроить уведомления:*\n";
        $statusMessage .= "Уведомления настраиваются через админ-панель сайта.";

        $this->telegramService->sendMessageToChat($telegramUser->chat_id, $statusMessage);
    }

    /**
     * Обработка команды /about
     */
    protected function handleAboutCommand(TelegramUser $telegramUser)
    {
        $message = "🍕 *FoodOrder - Сервис доставки еды*\n\n".
                  "🏪 *О сервисе:*\n".
                  "FoodOrder - это современная платформа для заказа еды\n".
                  "из лучших ресторанов, кафе и столовых Минска.\n\n".

                  "🤖 *О боте:*\n".
                  "Этот бот помогает получать уведомления о заказах:\n".
                  "• Статусы приготовления заказов\n".
                  "• Информация о доставке\n".
                  "• Подтверждения и обновления\n\n".

                  "📊 *Статистика:*\n".
                  "• 13+ ресторанов в Минске\n".
                  "• 100+ блюд на выбор\n".
                  "• Быстрая доставка\n".
                  "• Качественное обслуживание\n\n".

                  "🌐 *Сайт:* foodorder.com\n".
                  "📱 *Мобильное приложение:* Скоро в App Store и Google Play\n\n".

                  "📞 *Контакты:*\n".
                  "Поддержка: support@foodorder.com\n".
                  "📍 Адрес: г. Минск, ул. Примерная, 1";

        $this->telegramService->sendMessageToChat($telegramUser->chat_id, $message, null);
    }

    /**
     * Обработка неизвестной команды
     */
    protected function handleUnknownCommand(TelegramUser $telegramUser)
    {
        $message = "❓ *Неизвестная команда*\n\n".
                  "🤖 *Доступные команды FoodOrder Bot:*\n".
                  "🚀 `/start` - Начать работу с ботом\n".
                  "📊 `/status` - Проверить статус бота\n".
                  "ℹ️ `/about` - О боте и сервисе\n".
                  "❓ `/help` или `/commands` - Полный список команд\n\n".
                  "💡 Этот бот предназначен для администрации FoodOrder.";

        $this->telegramService->sendMessageToChat($telegramUser->chat_id, $message, null);
    }
}
