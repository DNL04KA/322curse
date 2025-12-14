@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0"><i class="fas fa-user-plus"></i> Регистрация</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя и фамилия <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                    <label for="phone" class="form-label">Номер телефона <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select class="form-select" id="country_code_select" onchange="updateCountryCode()" style="max-width: 100px;">
                                    <option value="+375" selected>🇧🇾 +375</option>
                                    <option value="+7">🇷🇺 +7</option>
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
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Необязательно, но удобно для получения акций</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Минимум 8 символов</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Подтверждение пароля <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Зарегистрироваться
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Уже есть аккаунт?
                        <a href="{{ route('login') }}" class="text-decoration-none">Войти</a>
                    </p>
                </div>

                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-info-circle"></i> Как работает регистрация:</h6>
                    <ol class="text-start mb-0">
                        <li>Заполните форму выше</li>
                        <li>Нажмите кнопку "Зарегистрироваться"</li>
                        <li>Вы автоматически войдете в систему</li>
                        <li>Регистрация завершена! Можете делать заказы.</li>
                    </ol>
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
        delete this.dataset.formatting;
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