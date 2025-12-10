<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegramIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-integration {phone? : Test phone number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram integration and send test messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $telegramService = app(TelegramService::class);

        $this->info('🔍 Testing Telegram Integration');
        $this->newLine();

        // 1. Проверка настроек бота
        $this->info('1️⃣ Checking bot configuration...');
        $botInfo = $telegramService->testBot();

        if (! $botInfo['success']) {
            $this->error('❌ Bot configuration failed: '.$botInfo['message']);

            return Command::FAILURE;
        }

        $this->info('✅ Bot: @'.$botInfo['bot_name'].' ('.$botInfo['bot_username'].')');
        $this->newLine();

        // 2. Проверка привязанных пользователей
        $this->info('2️⃣ Checking linked users...');
        $linkedUsers = TelegramUser::count();
        $this->info("👥 Linked users: {$linkedUsers}");

        if ($linkedUsers > 0) {
            $this->table(
                ['ID', 'Phone', 'Chat ID', 'Username', 'Verified'],
                TelegramUser::all(['id', 'phone', 'chat_id', 'username', 'verified_at'])->map(function ($user) {
                    return [
                        $user->id,
                        $user->phone,
                        $user->chat_id,
                        $user->username ?: 'N/A',
                        $user->verified_at ? '✅' : '❌',
                    ];
                })
            );
        }
        $this->newLine();

        // 3. Отправка тестового сообщения
        $this->info('3️⃣ Sending test messages...');

        // Тестовое сообщение админу
        $adminMessage = "🧪 *FoodOrder - Test Message*\n\n".
                       "🤖 Bot integration test\n".
                       '⏰ '.now()->format('Y-m-d H:i:s')."\n\n".
                       '✅ If you see this message, Telegram integration is working!';

        $sentToAdmin = $telegramService->sendMessageToAdmin($adminMessage);
        $this->info($sentToAdmin ? '✅ Message sent to admin' : '❌ Failed to send to admin');

        // Тестовое сообщение пользователю
        $testPhone = $this->argument('phone') ?: '+375 (29) 123-45-67';
        $userMessage = "🧪 *FoodOrder - User Test Message*\n\n".
                      "📱 Phone: `$testPhone`\n".
                      '⏰ '.now()->format('Y-m-d H:i:s')."\n\n".
                      '✅ If you see this, your Telegram is linked to FoodOrder!';

        $sentToUser = $telegramService->sendMessageToUser($testPhone, $userMessage);
        $this->info($sentToUser ? "✅ Message sent to user {$testPhone}" : "❌ Failed to send to user {$testPhone}");

        $this->newLine();

        // 4. Инструкции для пользователей
        $this->info('📝 User Instructions:');
        $this->line('1. Create a Telegram bot with @BotFather');
        $this->line('2. Add bot token to .env: TELEGRAM_BOT_TOKEN=your_token');
        $this->line('3. Set webhook: php artisan telegram:setup-webhook');
        $this->line('4. Tell users to message bot: /register +375XXXXXXXXX');
        $this->newLine();

        $this->info('🎉 Telegram integration test completed!');

        return Command::SUCCESS;
    }
}
