@extends('layouts.app')

@section('title', 'Отследить заказ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-info text-white text-center">
                <h4 class="mb-0"><i class="fas fa-search"></i> Отследить заказ</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Введите номер телефона или email, указанный при оформлении заказа.
                </p>

                <form method="POST" action="{{ route('orders.track.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона</label>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select class="form-select" id="country_code_select" onchange="updateCountryCode()">
                                    <option value="+375" selected>🇧🇾 +375</option>
                                    <option value="+7">🇷🇺 +7</option>
                                    <option value="+48">🇵🇱 +48</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="custom">🔧 Другое...</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('country_code') is-invalid @enderror"
                                           id="country_code_input" name="country_code" value="{{ old('country_code', '+375') }}"
                                           placeholder="+375" style="max-width: 80px;" readonly>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="(29) 123-45-67" required autofocus maxlength="20">
                                </div>
                            </div>
                        </div>
                        @error('country_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Введите номер телефона, указанный при оформлении заказа</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-search"></i> Найти заказ
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Уже есть аккаунт?
                        <a href="{{ route('login') }}" class="text-decoration-none">Войти</a>
                    </p>
                    <p class="mb-0">Заказывайте удобнее с личным кабинетом:
                        <a href="{{ route('register') }}" class="text-decoration-none">Регистрация</a>
                    </p>
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

        if (countryCode === '+375') {
            phoneInput.placeholder = "(29) 123-45-67";
        } else if (countryCode === '+7') {
            phoneInput.placeholder = "(999) 123-45-67";
        } else if (countryCode === '+380') {
            phoneInput.placeholder = "(50) 123-45-67";
        } else if (countryCode === '+48') {
            phoneInput.placeholder = "(500) 123-456";
        } else {
            phoneInput.placeholder = "Введите номер телефона";
        }
    }

    // Обработка ввода кода страны
    countryCodeInput.addEventListener('input', function(e) {
        let value = this.value;

        // Убеждаемся, что начинается с +
        if (!value.startsWith('+')) {
            value = '+' + value.replace(/^\+/, '');
        }

        // Удаляем все нецифровые символы кроме +
        value = value.replace(/[^\d+]/g, '');

        // Ограничиваем длину
        if (value.length > 5) {
            value = value.slice(0, 5);
        }

        this.value = value;

        // Обновляем плейсхолдер
        updatePhonePlaceholder();
    });

    let formatTimeout;
    phoneInput.addEventListener('input', function(e) {
        clearTimeout(formatTimeout);
    phoneInput.addEventListener('input', function(e) {
        // Не обрабатываем, если это программное изменение
        if (this.dataset.formatting) return;

        const countryCode = countryCodeInput.value;

        // Извлекаем все цифры из input
        let digits = this.value.replace(/\D/g, '');

            // Определяем максимальную длину в зависимости от страны
            let maxDigits = 9;
            if (countryCode === '+7') {
                maxDigits = 10;
            } else if (countryCode === '+380') {
                maxDigits = 9;
            } else if (countryCode === '+48') {
                maxDigits = 9;
            }

            digits = digits.slice(0, maxDigits);

            // Форматируем в зависимости от страны и количества цифр
            let formatted = '';

            if (countryCode === '+375') {
                // Беларусь: (XX) XXX-XX-XX
                if (digits.length === 0) {
                    formatted = '';
                } else if (digits.length <= 2) {
                    formatted = digits;
                } else {
                    formatted = '(' + digits.slice(0, 2) + ')';

                if (digits.length >= 3) {
                    // Беларусь: (XX) XXX-XX-XX
                    let remaining = digits.slice(2); // Все цифры после кода оператора
                    let formattedRemaining = '';

                    // Всегда берем первые 3 цифры для XXX
                    if (remaining.length >= 1) {
                        formattedRemaining = remaining.slice(0, Math.min(3, remaining.length));
                        remaining = remaining.slice(formattedRemaining.length);
                    }

                    // Добавляем дефис и следующие 2 цифры, если есть
                    if (remaining.length >= 1) {
                        formattedRemaining += '-' + remaining.slice(0, Math.min(2, remaining.length));
                        remaining = remaining.slice(Math.min(2, remaining.length));
                    }

                    // Добавляем еще дефис и 2 цифры, если есть
                    if (remaining.length >= 1) {
                        formattedRemaining += '-' + remaining.slice(0, Math.min(2, remaining.length));
                    }

                    formatted += ' ' + formattedRemaining;
                }
                }
            } else if (countryCode === '+7') {
                // Россия: (XXX) XXX-XX-XX
                if (digits.length >= 3) {
                    formatted = '(' + digits.slice(0, 3) + ')';
                    if (digits.length > 3) {
                        formatted += ' ' + digits.slice(3, 3);
                        if (digits.length >= 7) {
                            formatted += '-' + digits.slice(6, 2);
                            if (digits.length >= 9) {
                                formatted += '-' + digits.slice(8, 2);
                            }
                        }
                    }
                } else {
                    formatted = digits;
                }
            } else if (countryCode === '+380') {
                // Украина: (XX) XXX-XX-XX
                if (digits.length >= 2) {
                    formatted = '(' + digits.slice(0, 2) + ')';
                    if (digits.length >= 5) {
                        formatted += ' ' + digits.slice(2, 3) + digits.slice(3, 2);
                        if (digits.length >= 7) {
                            formatted += '-' + digits.slice(5, 2);
                            if (digits.length >= 9) {
                                formatted += '-' + digits.slice(7, 2);
                            }
                        }
                    } else if (digits.length > 2) {
                        formatted += ' ' + digits.slice(2);
                    }
                } else {
                    formatted = digits;
                }
            } else if (countryCode === '+48') {
                // Польша: (XXX) XXX-XXX
                if (digits.length >= 3) {
                    formatted = '(' + digits.slice(0, 3) + ')';
                    if (digits.length > 3) {
                        formatted += ' ' + digits.slice(3, 3);
                        if (digits.length >= 7) {
                            formatted += '-' + digits.slice(6, 3);
                        }
                    }
                } else {
                    formatted = digits;
                }
            } else {
                // Для других стран - простой формат
                formatted = digits.replace(/(\d{3})(?=\d)/g, '$1-');
            }

            // Устанавливаем флаг, чтобы избежать рекурсии
            this.dataset.formatting = 'true';
            this.value = formatted;
            delete this.dataset.formatting;
        });
    });

    // Инициализация
    updateCountryCode();

    // Функция для адаптивной ширины select
    function adjustSelectWidth() {
        const select = countryCodeSelect;
        const selectedOption = select.options[select.selectedIndex];
        const isCustom = selectedOption.value === 'custom';

        if (isCustom) {
            select.style.width = '120px';
        } else {
            select.style.width = '80px';
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
