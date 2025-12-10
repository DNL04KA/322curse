@extends('layouts.app')

@section('title', 'Тестирование Telegram бота')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fab fa-telegram text-primary"></i> Тестирование Telegram бота</h1>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> На главную
                </a>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-keyboard"></i> Отправить сообщение боту</h5>
                        </div>
                        <div class="card-body">
                            <form id="testForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="message" class="form-label">Сообщение:</label>
                                    <input type="text" class="form-control" id="message" name="message"
                                           placeholder="Введите команду, например: /help"
                                           value="/help" required>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Отправить
                                </button>
                            </form>

                            <div class="mt-3">
                                <h6>Примеры команд:</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-secondary btn-sm set-command" data-command="/start">
                                        <i class="fas fa-play"></i> /start
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm set-command" data-command="/help">
                                        <i class="fas fa-question-circle"></i> /help
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm set-command" data-command="/register +375291234567">
                                        <i class="fas fa-phone"></i> /register +375291234567
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-reply"></i> Ответы бота</h5>
                        </div>
                        <div class="card-body">
                            <div id="responses" class="responses-container">
                                <div class="text-center text-muted py-4">
                                    <i class="fab fa-telegram fa-2x mb-2"></i>
                                    <p>Отправьте сообщение, чтобы увидеть ответ бота</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Информация</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Важно!</strong> Это локальный тест. Для работы в Telegram настройте webhook.
                            </div>

                            <h6>Команды бота:</h6>
                            <ul>
                                <li><code>/start</code> - Начать работу с ботом</li>
                                <li><code>/help</code> - Показать справку</li>
                                <li><code>/register +375XXXXXXXXX</code> - Зарегистрировать номер телефона</li>
                            </ul>

                            <h6>Настройка webhook для Telegram:</h6>
                            <ol>
                                <li>Установите ngrok: <code>npm install -g ngrok</code></li>
                                <li>Запустите: <code>ngrok http 8000</code></li>
                                <li>Скопируйте HTTPS URL</li>
                                <li>Выполните: <code>php artisan telegram:setup-webhook [URL]/telegram/webhook</code></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.responses-container {
    max-height: 400px;
    overflow-y: auto;
}

.bot-message {
    background: #e3f2fd;
    border-left: 4px solid #2196f3;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.user-message {
    background: #f5f5f5;
    border-left: 4px solid #757575;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    text-align: right;
}

.timestamp {
    font-size: 0.8em;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testForm');
    const responsesContainer = document.getElementById('responses');

    // Обработчик кнопок с командами
    document.querySelectorAll('.set-command').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('message').value = this.dataset.command;
        });
    });

    // Обработчик формы
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const message = formData.get('message');

        // Добавляем сообщение пользователя
        addMessage('user', message);

        // Отправляем запрос
        fetch('/telegram/test', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Добавляем ответы бота
                if (data.bot_responses && data.bot_responses.length > 0) {
                    data.bot_responses.forEach(response => {
                        addMessage('bot', response.message);
                    });
                } else {
                    addMessage('bot', 'Бот не отправил ответ');
                }
            } else {
                addMessage('bot', 'Ошибка: ' + data.error, true);
            }
        })
        .catch(error => {
            addMessage('bot', 'Ошибка сети: ' + error.message, true);
        });
    });

    function addMessage(type, content, isError = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = type + '-message' + (isError ? ' text-danger' : '');

        // Преобразуем Markdown в HTML для отображения
        const formattedContent = content
            .replace(/\*(.*?)\*/g, '<strong>$1</strong>')  // Жирный текст
            .replace(/_(.*?)_/g, '<em>$1</em>')            // Курсив
            .replace(/`(.*?)`/g, '<code>$1</code>')        // Код
            .replace(/\n/g, '<br>');                       // Переносы строк

        messageDiv.innerHTML = formattedContent;

        // Добавляем временную метку
        const timestamp = document.createElement('div');
        timestamp.className = 'timestamp';
        timestamp.textContent = new Date().toLocaleTimeString();
        messageDiv.appendChild(timestamp);

        responsesContainer.appendChild(messageDiv);
        responsesContainer.scrollTop = responsesContainer.scrollHeight;
    }
});
</script>

@endsection


