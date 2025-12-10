@extends('layouts.app')

@section('title', 'Тест форматирования номера телефона')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-mobile-alt"></i> Тест форматирования номера</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Тестовое поле для проверки форматирования:</strong><br>
                        Попробуйте ввести номер телефона для Беларуси.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Номер телефона</label>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select class="form-select" id="country_code_select" onchange="updateCountryCode()">
                                    <option value="+375" selected>🇧🇾 +375</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="country_code_input" value="+375" style="max-width: 80px;" readonly>
                                    <input type="tel" class="form-control" id="phone_display" placeholder="(29) 123-45-67" maxlength="14">
                                    <input type="hidden" id="phone" name="phone">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Результат:</strong>
                        <div id="result" class="alert alert-secondary">
                            Введите номер для тестирования форматирования
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" onclick="resetField()" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo"></i> Очистить
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Перейти к входу
                        </a>
                    </div>
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
        if (digits.length >= 2) {
            formatted = '(' + digits.slice(0, 2) + ')';
        }

        if (digits.length >= 5) {
            formatted += ' ' + digits.slice(2, 3);
        }

        if (digits.length >= 7) {
            formatted += digits.slice(5, 2);
        }

        if (digits.length >= 9) {
            formatted += '-' + digits.slice(7, 2);
        }
    }

    return formatted;
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone_display');
    const phoneHidden = document.getElementById('phone');
    const countryCodeSelect = document.getElementById('country_code_select');
    const countryCodeInput = document.getElementById('country_code_input');
    const resultDiv = document.getElementById('result');

    // Функция обновления кода страны
    window.updateCountryCode = function() {
        const selectedValue = countryCodeSelect.value;
        countryCodeInput.value = selectedValue;
    };

    // Предотвращаем ввод недопустимых символов
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
        let maxDigits = 9;
        if (countryCode === '+7') maxDigits = 10;

        const currentDigits = this.value.replace(/\D/g, '');
        if (currentDigits.length >= maxDigits) {
            e.preventDefault();
        }
    });

    // Обработчик ввода
    phoneInput.addEventListener('input', function(e) {
        // Фильтруем ввод - разрешаем только цифры, скобки, дефисы и пробелы
        let filteredValue = this.value.replace(/[^0-9\(\)\-\s]/g, '');
        if (filteredValue !== this.value) {
            this.value = filteredValue;
        }

        // Ограничиваем количество цифр
        const countryCode = countryCodeInput.value;
        let maxDigits = 9;
        if (countryCode === '+7') maxDigits = 10;

        const cleanDigits = this.value.replace(/\D/g, '');
        const limitedDigits = cleanDigits.slice(0, maxDigits);

        // Синхронизируем скрытое поле
        phoneHidden.value = limitedDigits;

        // Применяем форматирование
        const formatted = applyPhoneFormatting(limitedDigits, countryCode);
        this.value = formatted;

        // Обновляем результат
        updateResult(formatted, limitedDigits);
    });

    // Обработчик вставки текста
    phoneInput.addEventListener('paste', function(e) {
        setTimeout(() => {
            let pastedText = this.value;
            let filteredDigits = pastedText.replace(/\D/g, '');

            const countryCode = countryCodeInput.value;
            let maxDigits = 9;
            if (countryCode === '+7') maxDigits = 10;

            filteredDigits = filteredDigits.slice(0, maxDigits);

            const formatted = applyPhoneFormatting(filteredDigits, countryCode);
            this.value = formatted;
            phoneHidden.value = filteredDigits;

            updateResult(formatted, filteredDigits);
        }, 0);
    });

    function updateResult(formatted, digits) {
        if (digits.length === 0) {
            resultDiv.className = 'alert alert-secondary';
            resultDiv.textContent = 'Введите номер для тестирования форматирования';
        } else {
            resultDiv.className = 'alert alert-success';
            resultDiv.innerHTML = `
                <strong>Отформатированный:</strong> ${formatted}<br>
                <strong>Чистые цифры:</strong> ${digits}<br>
                <strong>Длина:</strong> ${formatted.length} символов
            `;
        }
    }
});

function resetField() {
    document.getElementById('phone_display').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('result').className = 'alert alert-secondary';
    document.getElementById('result').textContent = 'Введите номер для тестирования форматирования';
}
</script>
@endsection
