@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Рестораны</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Корзина</a></li>
                <li class="breadcrumb-item active">Оформление заказа</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-clipboard-list text-primary"></i> Оформление заказа
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-user"></i> Данные получателя
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" onsubmit="preparePhoneForSubmit()">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Имя и фамилия <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                   id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
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
                    <div class="col-12">
                        <label for="customer_email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                               id="customer_email" name="customer_email" value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" placeholder="your@email.com">
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Необязательно, но удобно для получения чеков и акций</div>
                    </div>
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt text-primary"></i> Адрес доставки</h5>
                    </div>
                    <div class="col-md-4">
                        <label for="city" class="form-label">Город <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="city" name="city" value="{{ old('city', 'Минск') }}" readonly required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label for="street" class="form-label">Улица, дом, квартира <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('street') is-invalid @enderror"
                               id="street" name="street" value="{{ old('street') }}" placeholder="пр. Независимости, 10, кв. 5" required>
                        @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="entrance" class="form-label">Подъезд</label>
                        <input type="text" class="form-control @error('entrance') is-invalid @enderror"
                               id="entrance" name="entrance" value="{{ old('entrance') }}" placeholder="1">
                        @error('entrance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="floor" class="form-label">Этаж</label>
                        <input type="text" class="form-control @error('floor') is-invalid @enderror"
                               id="floor" name="floor" value="{{ old('floor') }}" placeholder="5">
                        @error('floor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="apartment" class="form-label">Квартира</label>
                        <input type="text" class="form-control @error('apartment') is-invalid @enderror"
                               id="apartment" name="apartment" value="{{ old('apartment') }}" placeholder="15">
                        @error('apartment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="additional_address" class="form-label">Дополнительная информация</label>
                        <input type="text" class="form-control @error('additional_address') is-invalid @enderror"
                               id="additional_address" name="additional_address" value="{{ old('additional_address') }}" placeholder="домофон, код">
                        @error('additional_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fas fa-clock text-primary"></i> Время доставки</h5>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_date" class="form-label">Дата доставки <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                               id="delivery_date" name="delivery_date" value="{{ old('delivery_date', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                        @error('delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_time_select" class="form-label">Время доставки <span class="text-danger">*</span></label>
                        <select class="form-select @error('delivery_time_select') is-invalid @enderror"
                                id="delivery_time_select" name="delivery_time_select" required>
                            <option value="">Выберите время...</option>
                        </select>
                        @error('delivery_time_select')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="notes" class="form-label">Комментарий к заказу</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3" placeholder="Особые пожелания к заказу...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check"></i> Подтвердить заказ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @include('orders._cart_summary', ['cartItems' => $cartItems, 'total' => $total])
    </div>
</div>
</form>
</div>

<script>
function preparePhoneForSubmit() {
    // Сначала проверяем время доставки
    if (!validateDeliveryTime()) {
        return false; // Останавливаем отправку формы
    }

    const countryCode = document.getElementById('country_code_input').value;
    const phoneInput = document.getElementById('phone');

    // Получаем только цифры из телефона
    const phoneDigits = phoneInput.value.replace(/\D/g, '');

    // Для Беларуси форматируем как +375(XX) XXX-XX-XX
    if (countryCode === '+375' && phoneDigits.length === 9) {
        phoneInput.value = countryCode + '(' + phoneDigits.slice(0, 2) + ') ' +
                          phoneDigits.slice(2, 5) + '-' +
                          phoneDigits.slice(5, 7) + '-' +
                          phoneDigits.slice(7, 9);
    } else {
        // Для остальных стран просто код + цифры
        phoneInput.value = countryCode + phoneDigits;
    }

    return true;
}

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

        // Форматы номеров по странам
        const placeholders = {
            '+375': '(29) 123-45-67',           // Беларусь - 9 цифр
            '+7': '9001234567 (10 цифр)',       // Россия - 10 цифр
            '+48': '500123456 (9 цифр)',        // Польша - 9 цифр
            '+49': '1701234567 (12 цифр)',      // Германия - 12 цифр
            '+1': '5551234567 (10 цифр)'        // США - 10 цифр
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
        // Не обрабатываем, если это программное изменение или авто-заполнение
        if (this.dataset.formatting || this.dataset.autoFill) return;

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

        // ТОЛЬКО для Беларуси (+375) специальный формат
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

    // Предотвращаем ввод нецифровых символов
    phoneInput.addEventListener('keypress', function(e) {
        // Разрешаем только цифры и управляющие клавиши
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
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


    // Функция для заполнения времени доставки
    function populateTimeSelect() {
        const timeSelect = document.getElementById('delivery_time_select');
        const dateInput = document.getElementById('delivery_date');

        if (!timeSelect || !dateInput) {
            return;
        }

        // Очищаем селект
        timeSelect.innerHTML = '<option value="">Выберите время...</option>';

        // Получаем выбранную дату
        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Определяем минимальное время для выбора
        let minTime = null;
        if (selectedDate.getTime() === today.getTime()) {
            // Сегодня - минимум через 1 час
            const now = new Date();
            minTime = new Date(now.getTime() + 60 * 60 * 1000); // +1 час
        }
        // Для будущих дат minTime остается null - доступны все времена

        // Создаем варианты времени с шагом 30 минут от 9:00 до 22:30
        let selectedIndex = -1;
        
        // Округляем минимальное время до ближайших 30 минут вверх
        let defaultTime = '09:00';
        if (minTime) {
            const minHour = minTime.getHours();
            const minMinute = minTime.getMinutes();
            
            // Если минуты от 0 до 29 - округляем до :30 этого часа
            // Если минуты от 30 до 59 - округляем до :00 следующего часа
            if (minMinute <= 30) {
                defaultTime = `${minHour.toString().padStart(2, '0')}:30`;
            } else {
                const nextHour = minHour + 1;
                defaultTime = `${nextHour.toString().padStart(2, '0')}:00`;
            }
        }

        for (let hour = 9; hour <= 22; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

                // Проверяем, доступно ли это время для сегодняшней даты
                if (minTime) {
                    const optionTime = new Date(selectedDate);
                    optionTime.setHours(hour, minute, 0, 0);

                    if (optionTime < minTime) {
                        continue; // Пропускаем время раньше минимального
                    }
                }

                const option = document.createElement('option');
                option.value = timeString;
                option.textContent = timeString;

                // Выбираем ближайшее доступное время
                if (selectedIndex === -1 && timeString >= defaultTime) {
                    selectedIndex = timeSelect.options.length;
                    option.selected = true;
                }

                timeSelect.appendChild(option);
            }
        }

        // Если не нашли подходящего времени, выбираем первый доступный вариант
        if (timeSelect.options.length > 1 && selectedIndex === -1) {
            timeSelect.selectedIndex = 1; // Пропускаем "Выберите время..."
        }
    }

    // Заполнение данных авторизованного пользователя
    @if(auth()->check() && auth()->user()->phone)
        @php
            $userPhone = auth()->user()->phone;
            if (preg_match('/^\+(\d{1,3})(\d+)$/', $userPhone, $matches)) {
                $userCountryCode = '+' . $matches[1];
                $userPhoneDigits = $matches[2];
            } else {
                $userCountryCode = '+375';
                $userPhoneDigits = preg_replace('/\D/', '', $userPhone);
            }
        @endphp
        setTimeout(() => {
            // Устанавливаем флаг, чтобы избежать обработки ввода
            phoneInput.dataset.autoFill = 'true';
            countryCodeInput.dataset.autoFill = 'true';

            document.getElementById('country_code_input').value = '{{ $userCountryCode }}';
            document.getElementById('phone').value = '{{ $userPhoneDigits }}';
            const countrySelect = document.getElementById('country_code_select');
            if (countrySelect.querySelector('option[value="{{ $userCountryCode }}"]')) {
                countrySelect.value = '{{ $userCountryCode }}';
            } else {
                countrySelect.value = 'custom';
                updateCountryCode();
            }
            updatePhonePlaceholder();

            // Убираем флаг после небольшой задержки
            setTimeout(() => {
                delete phoneInput.dataset.autoFill;
                delete countryCodeInput.dataset.autoFill;
            }, 200);
        }, 100);
    @endif

    // Функция для проверки и валидации времени доставки
    function validateDeliveryTime() {
        const dateInput = document.getElementById('delivery_date');
        const timeSelect = document.getElementById('delivery_time_select');

        if (!dateInput || !timeSelect || !dateInput.value || !timeSelect.value) {
            return true; // Не проверяем, если поля пустые
        }

        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const selectedTime = timeSelect.value;
        const [hours, minutes] = selectedTime.split(':').map(Number);

        const deliveryDateTime = new Date(selectedDate);
        deliveryDateTime.setHours(hours, minutes, 0, 0);

        const now = new Date();
        const today = new Date(now);
        today.setHours(0, 0, 0, 0);

        // Проверка на прошедшую дату
        if (selectedDate < today) {
            alert('Нельзя выбрать дату раньше сегодняшней!');
            dateInput.value = today.toISOString().split('T')[0];
            populateTimeSelect();
            return false;
        }

        // Проверка времени для сегодняшней даты
        if (selectedDate.getTime() === today.getTime()) {
            const minTime = new Date(now.getTime() + 60 * 60 * 1000); // +1 час

            if (deliveryDateTime < minTime) {
                alert('Время доставки на сегодня должно быть не раньше чем через 1 час от текущего времени!');
                populateTimeSelect(); // Перезаполняем время
                return false;
            }
        }

        // Проверка рабочего времени (9:00 - 23:00)
        if (hours < 9 || hours >= 23) {
            alert('Доставка осуществляется только с 9:00 до 23:00!');
            return false;
        }

        return true;
    }

    // Проверка даты и обновление времени
    document.getElementById('delivery_date').addEventListener('change', function(e) {
        const selectedDate = new Date(this.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            alert('Нельзя выбрать дату раньше сегодняшней!');
            this.value = today.toISOString().split('T')[0];
        }
        populateTimeSelect();
    });

    // Проверка времени при изменении
    document.getElementById('delivery_time_select').addEventListener('change', function(e) {
        validateDeliveryTime();
    });

    // Инициализация времени при загрузке страницы
    // Проверяем, что поле даты заполнено
    const dateInput = document.getElementById('delivery_date');
    if (dateInput && dateInput.value) {
        populateTimeSelect();
    } else if (dateInput) {
        // Если дата не выбрана, устанавливаем сегодняшнюю дату
        const today = new Date();
        dateInput.value = today.toISOString().split('T')[0];
        populateTimeSelect();
    }
});
</script>
@endsection
