<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize {--cleanup : Clean up unused tables data} {--drop-unused : Drop unused tables}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database by cleaning unused tables and improving performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Оптимизация базы данных Laravel');
        $this->newLine();

        // Показать текущие таблицы
        $this->showCurrentTables();

        // Используемые таблицы
        $usedTables = [
            'users',
            'restaurants',
            'dishes',
            'orders',
            'order_items',
            'sessions', // Используется для корзины и авторизации
        ];

        // Неиспользуемые таблицы
        $unusedTables = [
            'password_reset_tokens', // Не используется в проекте
            'cache',                 // Переключаем на file
            'jobs',                  // Переключаем на sync
        ];

        $this->newLine();
        $this->info('📊 Анализ использования таблиц:');
        $this->table(
            ['Таблица', 'Статус', 'Записи', 'Рекомендация'],
            collect($usedTables)->map(function ($table) {
                $exists = Schema::hasTable($table);
                $count = $exists ? DB::table($table)->count() : 0;

                return [$table, '✅ Используется', $count, 'Оставить'];
            })->merge(
                collect($unusedTables)->map(function ($table) {
                    $exists = Schema::hasTable($table);
                    $count = $exists ? DB::table($table)->count() : 0;

                    return [$table, '❌ Не используется', $count, 'Очистить'];
                })
            )
        );

        // Очистка данных если запрошено
        if ($this->option('cleanup')) {
            $this->cleanupUnusedTables($unusedTables);
        }

        // Удаление таблиц если запрошено
        if ($this->option('drop-unused')) {
            $this->dropUnusedTables($unusedTables);
        }

        $this->newLine();
        $this->info('✅ Оптимизация завершена!');
        $this->comment('Рекомендуется:');
        $this->comment('1. В .env изменить CACHE_STORE=file и QUEUE_CONNECTION=sync');
        $this->comment('2. Удалить миграцию password_reset_tokens');
        $this->comment('3. Запустить: php artisan config:clear && php artisan cache:clear');
    }

    private function showCurrentTables()
    {
        $this->info('📋 Текущие таблицы в базе данных:');

        try {
            $connection = config('database.default');
            $tables = [];

            if ($connection === 'sqlite') {
                $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
                $tableData = [];
                foreach ($tables as $table) {
                    $tableName = $table->name;
                    if (! str_starts_with($tableName, 'sqlite_')) {
                        $count = DB::table($tableName)->count();
                        $tableData[] = [$tableName, $count];
                    }
                }
            } elseif ($connection === 'mysql') {
                $tables = DB::select('SHOW TABLES');
                $database = env('DB_DATABASE');
                $tableData = [];
                foreach ($tables as $table) {
                    $tableName = $table->{"Tables_in_{$database}"};
                    $count = DB::table($tableName)->count();
                    $tableData[] = [$tableName, $count];
                }
            } else {
                $this->warn("Поддержка {$connection} не реализована");

                return;
            }

            $this->table(['Таблица', 'Записей'], $tableData);
        } catch (\Exception $e) {
            $this->error('Не удалось получить список таблиц: '.$e->getMessage());
        }
    }

    private function cleanupUnusedTables($unusedTables)
    {
        $this->newLine();
        $this->warn('🧹 Очистка данных в неиспользуемых таблицах...');

        foreach ($unusedTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                DB::table($table)->delete();
                $this->info("✅ Очищено {$count} записей из таблицы {$table}");
            } else {
                $this->comment("⚠️  Таблица {$table} не существует");
            }
        }
    }

    private function dropUnusedTables($unusedTables)
    {
        $this->newLine();
        $this->error('🗑️  Удаление неиспользуемых таблиц...');

        foreach ($unusedTables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
                $this->info("✅ Удалена таблица {$table}");
            } else {
                $this->comment("⚠️  Таблица {$table} уже удалена");
            }
        }
    }
}
