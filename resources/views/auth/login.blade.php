@extends('layouts.app')

@section('title', 'Вход в систему')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Вход в систему</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" onsubmit="preparePhoneForSubmit()">
                    @csrf

                    <div class="mb-3">
                        <label for="phone" class="form-label">Номер телефона <span class="text-danger">*</span></label>
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
                                           id="phone_display" name="phone_display" value="{{ old('phone') }}" placeholder="(29) 123-45-67" required autofocus maxlength="14" pattern="[0-9\(\)\-\s]*" inputmode="numeric">
                                    <input type="hidden" id="phone" name="phone" value="{{ old('phone') }}">
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
                        <label for="password" class="form-label">Пароль <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Войти
                        </button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-2">Нет аккаунта?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> Зарегистрироваться
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Функция применения форматирования номера телефона
function applyPhoneFormatting(digits, countryCode) {
    // Ограничиваем количество цифр в зависимости от страны
    let maxDigits = 9;
    if (countryCode === '+7') {
        maxDigits = 10;
    } else if (countryCode === '+380') {
        maxDigits = 9;
    } else if (countryCode === '+48') {
        maxDigits = 9;
    }

    digits = digits.slice(0, maxDigits);

    // Форматируем в зависимости от страны
    let formatted = '';

    if (countryCode === '+375') {
        // Беларусь: (XX) XXX-XX-XX
        console.log('Formatting Belarusian number:', digits, 'length:', digits.length);

        if (digits.length <= 2) {
            formatted = digits;
        } else {
            formatted = '(' + digits.slice(0, 2) + ')';

            if (digits.length >= 3) {
                let remaining = digits.slice(2); // Все цифры после кода оператора

                // Форматируем оставшиеся цифры: XXX-XX-XX
                if (remaining.length >= 3) {
                    formatted += ' ' + remaining.slice(0, 3);
                    remaining = remaining.slice(3);
                }

                if (remaining.length >= 2) {
                    formatted += '-' + remaining.slice(0, 2);
                    remaining = remaining.slice(2);
                }

                if (remaining.length >= 2) {
                    formatted += '-' + remaining.slice(0, 2);
                    remaining = remaining.slice(2);
                }

                // Если остались цифры, добавляем их
                if (remaining.length > 0) {
                    formatted += remaining;
                }
            }
        }
    } else if (countryCode === '+7') {
        // Россия: (XXX) XXX-XX-XX
        if (digits.length >= 3) {
            formatted = '(' + digits.slice(0, 3) + ')';
            if (digits.length > 3) {
                formatted += ' ' + digits.slice(3, 3);
                if (digits.length > 6) {
                    formatted += '-' + digits.slice(6, 2);
                    if (digits.length > 8) {
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
            if (digits.length > 2) {
                formatted += ' ' + digits.slice(2, 3);
                if (digits.length > 5) {
                    formatted += '-' + digits.slice(5, 2);
                    if (digits.length > 7) {
                        formatted += '-' + digits.slice(7, 2);
                    }
                }
            }
        } else {
            formatted = digits;
        }
    } else if (countryCode === '+48') {
        // Польша: XXX-XXX-XXX
        if (digits.length > 0) {
            formatted = digits.slice(0, 3);
            if (digits.length > 3) {
                formatted += '-' + digits.slice(3, 3);
                if (digits.length > 6) {
                    formatted += '-' + digits.slice(6, 3);
                }
            }
        }
    } else {
        formatted = digits;
    }

    console.log('applyPhoneFormatting result:', formatted);
    return formatted;
}


// Функция подготовки номера телефона перед отправкой формы
function preparePhoneForSubmit() {
    const phoneDisplay = document.getElementById('phone_display');
    const phoneHidden = document.getElementById('phone');

    // Извлекаем только цифры из отформатированного номера
    const cleanPhone = phoneDisplay.value.replace(/\D/g, '');
    phoneHidden.value = cleanPhone;
}



// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone_display');
    const phoneHidden = document.getElementById('phone');
    const countryCodeSelect = document.getElementById('country_code_select');
    const countryCodeInput = document.getElementById('country_code_input');

    console.log('Phone formatting initialized:', {
        phoneInput: !!phoneInput,
        phoneHidden: !!phoneHidden,
        countryCodeSelect: !!countryCodeSelect,
        countryCodeInput: !!countryCodeInput
    });

    // Тестовая проверка
    if (phoneInput) {
        phoneInput.addEventListener('focus', function() {
            console.log('Phone input focused');
        });
    }

    // Применяем форматирование к существующему значению при загрузке
    if (phoneInput && phoneInput.value) {
        console.log('Applying initial formatting to existing value');
        // Получаем чистые цифры из текущего значения
        const cleanDigits = phoneInput.value.replace(/\D/g, '');
        if (cleanDigits) {
            // Применяем форматирование
            const countryCode = countryCodeInput.value;
            phoneInput.dataset.formatting = 'true';

            // Применяем логику форматирования
            let formatted = applyPhoneFormatting(cleanDigits, countryCode);
            phoneInput.value = formatted;

            // Синхронизируем скрытое поле
            phoneHidden.value = cleanDigits;

            delete phoneInput.dataset.formatting;
        }
    }

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

    // Предотвращаем ввод недопустимых символов на уровне клавиатуры
    phoneInput.addEventListener('keydown', function(e) {
        // Разрешаем специальные клавиши
        if (e.ctrlKey || e.altKey || e.metaKey ||
            e.key === 'Backspace' || e.key === 'Delete' ||
            e.key === 'Tab' || e.key === 'Enter' ||
            e.key === 'ArrowLeft' || e.key === 'ArrowRight' ||
            e.key === 'ArrowUp' || e.key === 'ArrowDown' ||
            e.key === 'Home' || e.key === 'End') {
            return;
        }

        // Разрешаем только цифры
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
            return;
        }

        // Проверяем лимит цифр
        const countryCode = countryCodeInput.value;
        let maxDigits = 9; // Беларусь по умолчанию
        if (countryCode === '+7') {
            maxDigits = 10;
        } else if (countryCode === '+380') {
            maxDigits = 9;
        } else if (countryCode === '+48') {
            maxDigits = 9;
        }

        const currentDigits = this.value.replace(/\D/g, '');
        if (currentDigits.length >= maxDigits) {
            e.preventDefault();
        }
    });

    phoneInput.addEventListener('input', function(e) {
        console.log('Input event triggered, raw value:', this.value);

        // Не обрабатываем, если это программное изменение
        if (this.dataset.formatting) {
            console.log('Skipping - programmatic change');
            return;
        }

        const countryCode = countryCodeInput.value;
        console.log('Country code:', countryCode);

        // Получаем все цифры из текущего значения
        let allDigits = this.value.replace(/\D/g, '');
        console.log('All digits found:', allDigits);

        // Ограничиваем количество цифр
        let maxDigits = 9; // Беларусь по умолчанию
        if (countryCode === '+7') maxDigits = 10;
        else if (countryCode === '+380') maxDigits = 9;
        else if (countryCode === '+48') maxDigits = 9;

        allDigits = allDigits.slice(0, maxDigits);
        console.log('Limited digits:', allDigits);

        // Синхронизируем скрытое поле
        phoneHidden.value = allDigits;

        // Применяем форматирование
        const formatted = applyPhoneFormatting(allDigits, countryCode);
        console.log('Applying formatting:', formatted);

        // Устанавливаем отформатированное значение
        this.dataset.formatting = 'true';
        this.value = formatted;
        delete this.dataset.formatting;
    });

    // Обработчик вставки текста
    phoneInput.addEventListener('paste', function(e) {
        // Даем браузеру вставить текст, а затем фильтруем
        setTimeout(() => {
            let pastedText = this.value;

            // Фильтруем только цифры
            let filteredDigits = pastedText.replace(/\D/g, '');

            // Ограничиваем количество цифр
            const countryCode = countryCodeInput.value;
            let maxDigits = 9;
            if (countryCode === '+7') maxDigits = 10;
            else if (countryCode === '+380') maxDigits = 9;
            else if (countryCode === '+48') maxDigits = 9;

            filteredDigits = filteredDigits.slice(0, maxDigits);

            // Применяем форматирование
            const formatted = applyPhoneFormatting(filteredDigits, countryCode);

            this.dataset.formatting = 'true';
            this.value = formatted;
            phoneHidden.value = filteredDigits;
            delete this.dataset.formatting;
        }, 0);

        // Не обрабатываем, если это программное изменение
        if (this.dataset.formatting) return;

        const countryCode = countryCodeInput.value;

        // Ограничиваем количество цифр в зависимости от страны
        let maxDigits = 9; // Беларусь по умолчанию
        if (countryCode === '+7') {
            maxDigits = 10;
        } else if (countryCode === '+380') {
            maxDigits = 9;
        } else if (countryCode === '+48') {
            maxDigits = 9;
        }

        // Извлекаем все цифры из input
        let cleanDigits = this.value.replace(/\D/g, '');
        console.log('Extracted digits:', cleanDigits);

        // Ограничиваем количество цифр
        cleanDigits = cleanDigits.slice(0, maxDigits);
        console.log('Limited to max digits:', cleanDigits);

        // Синхронизируем скрытое поле с чистыми цифрами
        phoneHidden.value = cleanDigits;

        // Применяем форматирование
        const formatted = applyPhoneFormatting(cleanDigits, countryCode);
        console.log('Formatted result:', formatted);

        // Всегда устанавливаем форматированное значение
        this.dataset.formatting = 'true';
        this.value = formatted;
        delete this.dataset.formatting;
    });

        /* DISABLED OLD CODE
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
    }); DISABLED OLD CODE */

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
