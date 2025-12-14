<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramBotPolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:polling {--limit=100 : Максимальное количество обновлений}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск Telegram бота в режиме polling для локальной разработки';

    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = $this->telegramService->getBotToken();

        if (! $botToken) {
            $this->error('❌ Токен бота не настроен! Добавьте TELEGRAM_BOT_TOKEN в .env файл');

            return 1;
        }

        $this->info('🤖 Запуск Telegram бота в режиме polling...');
        $this->info('📡 Для остановки нажмите Ctrl+C');
        $this->info('');

        $offset = 0;
        $limit = (int) $this->option('limit');

        while (true) {
            try {
                // Получаем обновления
                $response = Http::timeout(30)->get("https://api.telegram.org/bot{$botToken}/getUpdates", [
                    'offset' => $offset,
                    'limit' => $limit,
                    'timeout' => 25,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['result']) && count($data['result']) > 0) {
                        foreach ($data['result'] as $update) {
                            $this->processUpdate($update);
                            $offset = max($offset, $update['update_id'] + 1);
                        }
                    }
                } else {
                    $this->error('❌ Ошибка получения обновлений: '.$response->status());
                    sleep(5);
                }

                // Небольшая пауза между запросами
                sleep(1);

            } catch (\Exception $e) {
                $this->error('❌ Ошибка: '.$e->getMessage());
                sleep(5);
            }
        }
    }

    /**
     * Обработка обновления от Telegram
     */
    protected function processUpdate(array $update)
    {
        $updateId = $update['update_id'];

        if (isset($update['message'])) {
            $username = isset($update['message']['from']['username']) ? $update['message']['from']['username'] : 'user';
            $text = isset($update['message']['text']) ? $update['message']['text'] : '';
            $this->info("📨 Новое сообщение от @{$username}: {$text}");

            // Создаем HTTP запрос для обработки через существующий контроллер
            $httpRequest = new \Illuminate\Http\Request;
            $httpRequest->merge(['message' => $update['message']]);

            try {
                $controller = app(\App\Http\Controllers\TelegramBotController::class);
                $controller->webhook($httpRequest);

                $this->info('✅ Сообщение обработано');
            } catch (\Exception $e) {
                $this->error('❌ Ошибка обработки: '.$e->getMessage());
            }
        } elseif (isset($update['callback_query'])) {
            $callbackUsername = isset($update['callback_query']['from']['username']) ? $update['callback_query']['from']['username'] : 'user';
            $callbackData = isset($update['callback_query']['data']) ? $update['callback_query']['data'] : '';
            $this->info("🔘 Callback от @{$callbackUsername}: {$callbackData}");

            // Обрабатываем callback
            $httpRequest = new \Illuminate\Http\Request;
            $httpRequest->merge(['callback_query' => $update['callback_query']]);

            try {
                $controller = app(\App\Http\Controllers\TelegramBotController::class);
                $controller->webhook($httpRequest);

                $this->info('✅ Callback обработан');
            } catch (\Exception $e) {
                $this->error('❌ Ошибка обработки callback: '.$e->getMessage());
            }
        }
    }
}
