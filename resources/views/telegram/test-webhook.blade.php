@extends('layouts.app')

@section('title', 'Тест Telegram Webhook')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fab fa-telegram"></i> Тест Telegram Webhook</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Продакшн режим:</strong> Код верификации сохраняется в кэше и отправляется через бота.
                        Для тестирования используйте команду <code>/register +375291234567</code> в боте.
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-terminal"></i> Все доступные команды бота:</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <code>/start</code>
                                    <small class="text-muted d-block">Начать работу с ботом и получить приветствие</small>
                                </div>
                                <span class="badge bg-primary">🚀</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <code>/help</code> или <code>/commands</code>
                                    <small class="text-muted d-block">Показать полный список команд с описаниями</small>
                                </div>
                                <span class="badge bg-info">❓</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <code>/register +375XXXXXXXXX</code>
                                    <small class="text-muted d-block">Привязать номер телефона для уведомлений</small>
                                </div>
                                <span class="badge bg-success">📝</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <code>/status</code>
                                    <small class="text-muted d-block">Проверить статус привязки номера телефона</small>
                                </div>
                                <span class="badge bg-warning">📊</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <code>/about</code>
                                    <small class="text-muted d-block">Информация о боте и сервисе FoodOrder</small>
                                </div>
                                <span class="badge bg-secondary">ℹ️</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-code"></i> Тест через Artisan команду:</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-2">Запустите в терминале:</p>
                                <code class="d-block p-2 bg-dark text-light rounded">
                                    php artisan telegram:test-locally register +375291234567
                                </code>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5><i class="fas fa-server"></i> Статус сервера:</h5>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-1"><strong>URL:</strong> <code>http://127.0.0.1:8000</code></p>
                                <p class="mb-1"><strong>Режим:</strong> <span class="badge bg-success">Продакшн (APP_DEBUG=false)</span></p>
                                <p class="mb-0"><strong>Время:</strong> <span id="current-time">{{ now()->format('H:i:s') }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Перейти на главную
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Зарегистрироваться
                        </a>
                        <a href="{{ route('telegram.test') }}" class="btn btn-outline-info">
                            <i class="fas fa-flask"></i> Тестовый интерфейс бота
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обновляем время каждую секунду
    setInterval(function() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString('ru-RU');
    }, 1000);
});
</script>

<style>
.list-group-item code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}
</style>
@endsection
