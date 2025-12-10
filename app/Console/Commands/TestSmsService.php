<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class TestSmsService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone? : Test phone number} {--message= : Custom message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS sending via email gateway';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $smsService = app(SmsService::class);

        $this->info('📱 Testing SMS Service (Email Gateway)');
        $this->newLine();

        // Показать поддерживаемые операторы
        $this->info('📋 Supported operators:');
        $operators = $smsService->getSupportedOperators();
        foreach ($operators as $name => $pattern) {
            $this->line("  • {$name}: {$pattern}");
        }
        $this->newLine();

        // Тестовый номер
        $testPhone = $this->argument('phone') ?: '+375 (29) 123-45-67';
        $testMessage = $this->option('message') ?: 'FoodOrder: Test SMS from '.now()->format('H:i:s');

        $this->info("🧪 Testing SMS to: {$testPhone}");
        $this->line("📝 Message: {$testMessage}");
        $this->newLine();

        // Проверяем поддержку оператора
        $isSupported = $smsService->isOperatorSupported($testPhone);
        if (! $isSupported) {
            $this->error("❌ Operator not supported for phone: {$testPhone}");
            $this->comment('Supported formats:');
            foreach ($operators as $name => $pattern) {
                $this->comment("  - {$pattern}");
            }

            return Command::FAILURE;
        }

        $this->info('✅ Operator supported');

        // Отправляем тестовое SMS
        $this->info('📤 Sending test SMS...');
        $sent = $smsService->sendSms($testPhone, $testMessage);

        if ($sent) {
            $this->info('✅ SMS sent successfully!');
            $this->comment('Note: SMS delivery depends on email-to-SMS gateway of the operator.');
            $this->comment('It may take a few minutes for the SMS to arrive.');
        } else {
            $this->error('❌ Failed to send SMS');
            $this->comment('Check Laravel logs: tail -f storage/logs/laravel.log');

            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('🎉 SMS test completed!');
        $this->comment('To test with different phone: php artisan sms:test +375291234567');

        return Command::SUCCESS;
    }
}
