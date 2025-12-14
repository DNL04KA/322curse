<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {phone? : The phone number of the user to make admin} {--clear : Clear all users before creating admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an administrator by phone number or create new admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Очистка всех пользователей если указана опция --clear
        if ($this->option('clear')) {
            $userCount = User::count();
            User::query()->delete();
            $this->info("✅ Cleared {$userCount} users from database.");
        }

        $phone = $this->argument('phone');

        // Если номер не указан, запрашиваем данные
        if (! $phone) {
            $phone = $this->ask('Введите номер телефона администратора', '+375293709505');
        }

        // Проверяем, существует ли пользователь с таким номером
        $existingUser = User::where('phone', $phone)->first();

        if ($existingUser) {
            if ($existingUser->is_admin) {
                $this->warn("⚠️  Пользователь с номером {$phone} уже является администратором!");

                return Command::FAILURE;
            }

            // Делаем существующего пользователя админом
            $existingUser->update(['is_admin' => true]);
            $this->info("✅ Пользователь {$existingUser->name} теперь администратор!");

            return Command::SUCCESS;
        }

        // Создаем нового админа
        $name = $this->ask('Введите имя администратора', 'Administrator');
        $email = $this->ask('Введите email (опционально)', null);
        $password = $this->secret('Введите пароль (по умолчанию: admin123)') ?: 'admin123';

        $this->info('Создание нового администратора...');

        $user = User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'is_admin' => true,
            'phone_verified_at' => now(),
        ]);

        $this->info('');
        $this->info('✅ Новый администратор создан успешно!');
        $this->info('');
        $this->info('👑 ДАННЫЕ ДЛЯ ВХОДА:');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("👤 Имя: {$user->name}");
        $this->info("📱 Телефон: {$user->phone}");
        $this->info("🔑 Пароль: {$password}");

        if ($user->email) {
            $this->info("📧 Email: {$user->email}");
        }

        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('');
        $this->info('🌐 Адрес для входа: http://127.0.0.1:8000/login');

        return Command::SUCCESS;
    }
}
