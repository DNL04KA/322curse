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

        $phone = $this->argument('phone') ?: '+375291234567';

        // Создаем нового админа с хорошими данными
        $this->info("Creating new admin user...");
            $user = User::create([
                'name' => 'Administrator',
                'phone' => $phone,
                'email' => 'admin_' . time() . '@foodorder.com',
                'password' => \Illuminate\Support\Facades\Hash::make('Admin123!'),
                'is_admin' => true,
                'phone_verified_at' => now(),
            ]);

        $this->info("✅ New admin user created successfully!");
        $this->info("");
        $this->info("👑 ADMIN LOGIN CREDENTIALS:");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📱 Phone: {$user->phone}");
        $this->info("🔑 Password: Admin123!");
        $this->info("📧 Email: {$user->email}");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("");
        $this->info("🌐 Login URL: http://127.0.0.1:8000/login");

        return Command::SUCCESS;
    }
}
