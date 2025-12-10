<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegramBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test {--message= : Custom test message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram bot integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegramService = app(TelegramService::class);

        // Сначала проверяем настройки бота
        $this->info('🔍 Проверяем настройки Telegram бота...');

        $botInfo = $telegramService->testBot();

        if (! $botInfo['success']) {
            $this->error('❌ Ошибка: '.$botInfo['message']);
            if (isset($botInfo['response'])) {
                $this->error('Ответ API: '.json_encode($botInfo['response'], JSON_PRETTY_PRINT));
            }

            return 1;
        }

        $this->info('✅ Бот найден: '.$botInfo['bot_name']);
        $this->info('📱 Username: @'.$botInfo['bot_username']);

        // Отправляем тестовое сообщение
        $message = $this->option('message') ?: "🧪 *Тестовое сообщение от FoodOrder*\n\n⏰ Время: ".now()->format('d.m.Y H:i:s')."\n✅ Telegram интеграция работает!";

        $this->info('📤 Отправляем тестовое сообщение...');

        $result = $telegramService->sendMessage($message);

        if ($result) {
            $this->info('✅ Сообщение успешно отправлено!');
            $this->info('📨 Проверьте чат с ботом @'.$botInfo['bot_username']);
        } else {
            $this->error('❌ Не удалось отправить сообщение');
            $this->error('Возможные причины:');
            $this->error('- Бот не добавлен в чат');
            $this->error('- Неверный CHAT_ID');
            $this->error('- Проблемы с интернет-соединением');
        }

        return 0;
    }
}
