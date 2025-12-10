@extends('layouts.app')

@section('title', 'Локальное тестирование Telegram бота')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fab fa-telegram"></i> Локальное тестирование Telegram бота
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Для чего это нужно?</h5>
                    <p>Поскольку Telegram требует HTTPS для webhook, а у нас локальная разработка с HTTP,
                    этот инструмент позволяет тестировать бота без установки ngrok.</p>
                </div>

                <form id="test-form" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="message" class="form-label">
                            <strong>Сообщение для бота:</strong>
                        </label>
                        <input type="text" class="form-control form-control-lg" id="message" name="message"
                               placeholder="Введите команду бота, например: /start" required>
                        <div class="form-text">
                            Попробуйте: <code>/start</code>, <code>/help</code>, <code>/register +375291234567</code>, <code>/status</code>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-paper-plane"></i> Отправить сообщение боту
                    </button>
                </form>

                <div id="loading" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Обработка...</span>
                    </div>
                    <p class="mt-2">Бот обрабатывает ваше сообщение...</p>
                </div>

                <div id="response" class="mt-4 d-none">
                    <h5>📨 Ответ бота:</h5>
                    <div id="response-content"></div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">🎛️ Клавиатура в /start</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="btn-group-vertical w-100" role="group">
                                        <div class="btn-group mb-2" role="group">
                                            <button class="btn btn-outline-primary" onclick="setCommand('/start')">/start</button>
                                            <button class="btn btn-outline-secondary" onclick="setCommand('/help')">/help</button>
                                        </div>
                                        <div class="btn-group mb-2" role="group">
                                            <button class="btn btn-outline-info" onclick="setCommand('/register +375291234567')">/register</button>
                                            <button class="btn btn-outline-warning" onclick="setCommand('/status')">/status</button>
                                        </div>
                                        <button class="btn btn-outline-success" onclick="setCommand('/about')">/about</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">ℹ️ Информация</h6>
                            </div>
                            <div class="card-body">
                                <h6>Для продакшена:</h6>
                                <ol>
                                    <li>Получите HTTPS домен</li>
                                    <li>Установите webhook: <code>/telegram/set-webhook?url=https://yourdomain.com/telegram/webhook</code></li>
                                    <li>Бот будет работать в Telegram</li>
                                </ol>

                                <h6 class="mt-3">Текущее состояние:</h6>
                                <p class="mb-0">
                                    <span class="badge bg-success">Сервер работает</span>
                                    <span class="badge bg-warning">Webhook не установлен (нужен HTTPS)</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setCommand(command) {
    document.getElementById('message').value = command;
}

document.getElementById('test-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const message = document.getElementById('message').value.trim();
    if (!message) {
        alert('Введите сообщение!');
        return;
    }

    const loading = document.getElementById('loading');
    const response = document.getElementById('response');
    const responseContent = document.getElementById('response-content');

    // Показываем загрузку
    loading.classList.remove('d-none');
    response.classList.add('d-none');

    fetch('/telegram/test-local', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        loading.classList.add('d-none');

        if (data.success) {
            response.classList.remove('d-none');

            let html = '<div class="alert alert-success"><strong>✅ Входящее сообщение:</strong> ' + data.input_message + '</div>';

            if (data.bot_responses && data.bot_responses.length > 0) {
                data.bot_responses.forEach((resp, index) => {
                    html += '<div class="card mt-3">';
                    html += '<div class="card-header"><strong>📤 Ответ бота #' + (index + 1) + '</strong></div>';
                    html += '<div class="card-body">';

                    // Экранируем HTML в сообщении
                    const messageText = resp.message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    html += '<pre style="white-space: pre-wrap; font-family: inherit;">' + messageText + '</pre>';

                    if (resp.keyboard) {
                        html += '<div class="mt-3"><strong>🎛️ Клавиатура:</strong></div>';
                        const keyboard = resp.keyboard.inline_keyboard;
                        keyboard.forEach(row => {
                            html += '<div class="btn-group mb-1" role="group">';
                            row.forEach(button => {
                                html += '<button class="btn btn-outline-primary btn-sm" disabled>' + button.text + '</button>';
                            });
                            html += '</div><br>';
                        });
                    }

                    html += '</div></div>';
                });
            } else {
                html += '<div class="alert alert-warning">🤖 Бот не отправил ответа</div>';
            }

            responseContent.innerHTML = html;
        } else {
            responseContent.innerHTML = '<div class="alert alert-danger"><strong>❌ Ошибка:</strong> ' + data.error + '</div>';
            response.classList.remove('d-none');
        }
    })
    .catch(error => {
        loading.classList.add('d-none');
        response.classList.remove('d-none');
        responseContent.innerHTML = '<div class="alert alert-danger"><strong>❌ Ошибка сети:</strong> ' + error.message + '</div>';
        console.error('Error:', error);
    });
});
</script>
@endpush

