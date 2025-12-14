<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, существует ли уже админ
        $adminExists = User::where('is_admin', true)->exists();

        if ($adminExists) {
            $this->command->info('ℹ️  Admin user already exists. Skipping...');

            return;
        }

        // Создаем админа с номером +375293709505 и паролем admin123
        User::create([
            'name' => 'Admin',
            'phone' => '+375293709505',
            'email' => null,
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'phone_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('👑 Phone: +375293709505');
        $this->command->info('🔑 Password: admin123');
    }
}
