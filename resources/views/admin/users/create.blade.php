@extends('layouts.app')

@section('title', 'Создание нового пользователя')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Управление пользователями</a></li>
                <li class="breadcrumb-item active">Создание пользователя</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-user-plus text-success"></i> Создание нового пользователя</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> К списку пользователей
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Обнаружены ошибки валидации:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-plus"></i> Данные нового пользователя
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Имя пользователя <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Отображаемое имя пользователя в системе</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Адрес электронной почты (необязательно)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Номер телефона <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <select class="form-select" id="country_code_select" onchange="updateCountryCode()">
                                            <option value="+375" selected>🇧🇾 +375</option>
                                            <option value="+7">🇷🇺 +7</option>
                                            <option value="+380">🇺🇦 +380</option>
                                            <option value="+48">🇵🇱 +48</option>
                                            <option value="+49">🇩🇪 +49</option>
                                            <option value="+1">🇺🇸 +1</option>
                                            <option value="custom">🔧 ...</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control @error('country_code') is-invalid @enderror"
                                                   id="country_code_input" name="country_code" value="{{ old('country_code', '+375') }}"
                                                   placeholder="+375" style="max-width: 80px;" readonly>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="(29) 123-45-67" required maxlength="25">
                                        </div>
                                    </div>
                                </div>
                                @error('country_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Номер телефона должен быть уникальным</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Пароль <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Минимум 8 символов</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Подтверждение пароля <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" required minlength="8">
                                <div class="form-text">Повторите пароль для подтверждения</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Роль пользователя</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin" {{ old('is_admin') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin">
                                        <strong>Администратор</strong>
                                    </label>
                                </div>
                                <div class="form-text">Отметьте, если пользователь должен иметь права администратора</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Поля отмеченные <span class="text-danger">*</span> обязательны для заполнения
                                    </small>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-user-plus"></i> Создать пользователя
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Статистика системы -->
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Статистика системы
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-primary mb-1">{{ \App\Models\User::count() }}</div>
                        <small class="text-muted">Всего пользователей</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success mb-1">{{ \App\Models\User::where('is_admin', true)->count() }}</div>
                        <small class="text-muted">Администраторов</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    const countryCodeSelect = document.getElementById('country_code_select');
    const countryCodeInput = document.getElementById('country_code_input');
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const form = document.querySelector('form');

    // Функция обновления кода страны
    window.updateCountryCode = function() {
        const selectedValue = countryCodeSelect.value;

        if (selectedValue === 'custom') {
            countryCodeInput.readOnly = false;
            countryCodeInput.value = '+';
            countryCodeInput.focus();
        } else {
            countryCodeInput.readOnly = true;
            countryCodeInput.value = selectedValue;
        }

        // Обновляем плейсхолдер для номера телефона
        updatePhonePlaceholder();
    };

    // Функция обновления плейсхолдера номера телефона
    function updatePhonePlaceholder() {
        const countryCode = countryCodeInput.value;

        // Распространенные форматы номеров по странам
        const placeholders = {
            '+375': '(29) 123-45-67',    // Беларусь - 9 цифр
            '+7': '9001234567 (10 цифр)',   // Россия - 10 цифр
            '+380': '(50) 123-45-67',   // Украина - 9 цифр
            '+48': '500123456 (9 цифр)',    // Польша - 9 цифр
            '+49': '1701234567 (12 цифр)',  // Германия - 12 цифр
            '+1': '5551234567 (10 цифр)'    // США - 10 цифр
        };

        phoneInput.placeholder = placeholders[countryCode] || "Введите номер телефона";
    }

    // Обработка ввода кода страны
    countryCodeInput.addEventListener('input', function(e) {
        let value = this.value;

        // Убеждаемся, что начинается с +
        if (!value.startsWith('+')) {
            value = '+' + value.replace(/^\+/, '');
        }

        // Удаляем все нецифровые символы кроме + и пробелов
        value = value.replace(/[^\d+\s]/g, '');

        // Ограничиваем длину до разумных пределов
        if (value.length > 8) {
            value = value.slice(0, 8);
        }

        this.value = value;
        updatePhonePlaceholder();
    });

    // Обработка ввода номера телефона
    phoneInput.addEventListener('input', function(e) {
        // Не обрабатываем, если это программное изменение
        if (this.dataset.formatting) return;

        const countryCode = countryCodeInput.value;

        // Очищаем от нецифровых символов и ограничиваем длину в зависимости от страны
        let digits = this.value.replace(/\D/g, '');

        // Максимальное количество цифр в зависимости от страны
        const maxDigitsByCountry = {
            '+375': 9,   // Беларусь: (XX) XXX-XX-XX
            '+7': 10,    // Россия
            '+380': 9,   // Украина
            '+48': 9,    // Польша
            '+49': 12,   // Германия
            '+1': 10     // США
        };

        const maxDigits = maxDigitsByCountry[countryCode] || 15;
        digits = digits.slice(0, maxDigits);

        let formatted = digits;

        // ТОЛЬКО для Беларуси (+375) специальный формат при полном номере
        if (countryCode === '+375' && digits.length >= 2) {
            if (digits.length === 9) {
                // Полный формат: (XX) XXX-XX-XX
                formatted = '(' + digits.slice(0, 2) + ') ' +
                           digits.slice(2, 5) + '-' +
                           digits.slice(5, 7) + '-' +
                           digits.slice(7, 9);
            } else if (digits.length >= 2) {
                // Частичный формат: (XX) XXX...
                formatted = '(' + digits.slice(0, 2) + ')';
                if (digits.length > 2) {
                    formatted += ' ' + digits.slice(2);
                }
            }
        }
        // Для ВСЕХ остальных стран - просто цифры без форматирования

        // Устанавливаем флаг, чтобы избежать рекурсии
        this.dataset.formatting = 'true';
        this.value = formatted.trim();

        // Сохраняем полный номер с кодом страны для отправки формы
        if (digits.length > 0) {
            const fullFormatted = countryCode + formatted.trim();
            // Создаем скрытое поле с полным номером, если его нет
            let hiddenField = document.getElementById('full_phone');
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.id = 'full_phone';
                hiddenField.name = 'full_phone';
                this.parentNode.appendChild(hiddenField);
            }
            hiddenField.value = fullFormatted;
        }

        delete this.dataset.formatting;
    });

    // Валидация паролей
    function validatePasswords() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmationInput.value;

        // Убираем предыдущие классы валидации
        passwordConfirmationInput.classList.remove('is-valid', 'is-invalid');

        if (confirmation && password !== confirmation) {
            passwordConfirmationInput.classList.add('is-invalid');
            return false;
        } else if (confirmation && password === confirmation) {
            passwordConfirmationInput.classList.add('is-valid');
            return true;
        }

        return true;
    }

    passwordConfirmationInput.addEventListener('input', validatePasswords);
    passwordInput.addEventListener('input', validatePasswords);

    // Проверка формы перед отправкой
    let isSubmitting = false;
    form.addEventListener('submit', function(e) {
        // Предотвращаем двойную отправку
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        if (!validatePasswords()) {
            e.preventDefault();
            alert('Пароли не совпадают!');
            return false;
        }

        // Проверяем минимальную длину пароля
        if (passwordInput.value.length < 8) {
            e.preventDefault();
            alert('Пароль должен содержать минимум 8 символов!');
            passwordInput.focus();
            return false;
        }

        // Блокируем повторную отправку
        isSubmitting = true;
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Создание...';
        }
    });

    // Инициализация
    updateCountryCode();

    // Функция для адаптивной ширины select
    function adjustSelectWidth() {
        const select = countryCodeSelect;
        const selectedOption = select.options[select.selectedIndex];
        const isCustom = selectedOption.value === 'custom';

        if (isCustom) {
            select.style.width = '100px';
        } else {
            select.style.width = '100px'; // Фиксированная ширина для кодов стран
        }
    }

    // Настраиваем ширину при загрузке и изменении
    adjustSelectWidth();
    countryCodeSelect.addEventListener('change', adjustSelectWidth);

    // При фокусе показываем полный текст
    countryCodeSelect.addEventListener('focus', function() {
        this.style.width = '140px';
    });

    // При потере фокуса возвращаем адаптивную ширину
    countryCodeSelect.addEventListener('blur', function() {
        setTimeout(adjustSelectWidth, 100);
    });
});
</script>
@endsection
