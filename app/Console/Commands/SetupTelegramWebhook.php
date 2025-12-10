<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class SetupTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setup-webhook {--remove : Remove webhook instead of setting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup or remove Telegram webhook for bot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = config('services.telegram.bot_token');

        if (! $botToken) {
            $this->error('Telegram bot token not configured!');
            $this->info('Add TELEGRAM_BOT_TOKEN to your .env file');

            return Command::FAILURE;
        }

        if ($this->option('remove')) {
            return $this->removeWebhook($botToken);
        }

        return $this->setupWebhook($botToken);
    }

    /**
     * Setup webhook
     */
    protected function setupWebhook(string $botToken): int
    {
        $webhookUrl = URL::to('/telegram/webhook');

        $this->info("Setting up webhook to: {$webhookUrl}");

        $response = Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
            'url' => $webhookUrl,
            'allowed_updates' => ['message'],
            'drop_pending_updates' => true,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['ok']) {
                $this->info('✅ Webhook successfully set!');
                $this->info('Bot: @'.($data['result']['username'] ?? 'Unknown'));

                return Command::SUCCESS;
            }
        }

        $this->error('❌ Failed to set webhook!');
        $this->error('Response: '.$response->body());

        return Command::FAILURE;
    }

    /**
     * Remove webhook
     */
    protected function removeWebhook(string $botToken): int
    {
        $this->info('Removing webhook...');

        $response = Http::post("https://api.telegram.org/bot{$botToken}/deleteWebhook", [
            'drop_pending_updates' => true,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['ok']) {
                $this->info('✅ Webhook successfully removed!');

                return Command::SUCCESS;
            }
        }

        $this->error('❌ Failed to remove webhook!');
        $this->error('Response: '.$response->body());

        return Command::FAILURE;
    }
}
