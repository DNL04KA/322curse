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
                <form action="{{ route('orders.store') }}" method="POST">
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
                                <select class="form-select" id="country_code_select" onchange="updateCountryCode()">
                                    @php
                                        $userCountryCode = '+375'; // По умолчанию
                                        if (auth()->check() && auth()->user()->phone) {
                                            if (preg_match('/^\+(\d{3})/', auth()->user()->phone, $matches)) {
                                                $userCountryCode = '+' . $matches[1];
                                            }
                                        }
                                    @endphp
                                    <option value="+375" {{ $userCountryCode === '+375' ? 'selected' : '' }}>🇧🇾 +375</option>
                                    <option value="+7" {{ $userCountryCode === '+7' ? 'selected' : '' }}>🇷🇺 +7</option>
                                    <option value="+48" {{ $userCountryCode === '+48' ? 'selected' : '' }}>🇵🇱 +48</option>
                                    <option value="+49" {{ $userCountryCode === '+49' ? 'selected' : '' }}>🇩🇪 +49</option>
                                    <option value="+1" {{ $userCountryCode === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                    <option value="custom" {{ !in_array($userCountryCode, ['+375', '+7', '+48', '+49', '+1']) ? 'selected' : '' }}>🔧 Другое...</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control @error('country_code') is-invalid @enderror"
                                           id="country_code_input" name="country_code" value="{{ old('country_code', '+375') }}"
                                           placeholder="+375" style="max-width: 80px;" readonly>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" placeholder="(29) 123-45-67" required maxlength="20">
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
                            <label for="street" class="form-label">Улица и номер дома <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('street') is-invalid @enderror"
                                   id="street" name="street" value="{{ old('street') }}" placeholder="ул. Гикало, д. 9" required>
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="entrance" class="form-label">Подъезд</label>
                            <input type="text" class="form-control @error('entrance') is-invalid @enderror"
                                   id="entrance" name="entrance" value="{{ old('entrance') }}" placeholder="1">
                            @error('entrance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="floor" class="form-label">Этаж</label>
                            <input type="text" class="form-control @error('floor') is-invalid @enderror"
                                   id="floor" name="floor" value="{{ old('floor') }}" placeholder="5">
                            @error('floor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="apartment" class="form-label">Квартира</label>
                            <input type="text" class="form-control @error('apartment') is-invalid @enderror"
                                   id="apartment" name="apartment" value="{{ old('apartment') }}" placeholder="15">
                            @error('apartment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="additional_address" class="form-label">Дополнительная информация</label>
                            <textarea class="form-control @error('additional_address') is-invalid @enderror"
                                      id="additional_address" name="additional_address" rows="2"
                                      placeholder="Код домофона, ориентиры, особые указания...">{{ old('additional_address') }}</textarea>
                            @error('additional_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="delivery_time" class="form-label">Время доставки</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                           id="delivery_date" name="delivery_date"
                                           value="{{ old('delivery_date', now()->addHour()->format('Y-m-d')) }}"
                                           min="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <select class="form-select @error('delivery_time_select') is-invalid @enderror"
                                            id="delivery_time_select" name="delivery_time_select">
                                        <!-- Опции будут заполнены JavaScript -->
                                        <option value="">Выберите время</option>
                                    </select>
                                </div>
                            </div>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('delivery_time_select')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Минимальное время доставки - через 1 час от текущего времени</div>

                            <!-- Скрытое поле для объединения даты и времени -->
                            <input type="hidden" id="delivery_time" name="delivery_time" value="{{ old('delivery_time') }}">
                        </div>
                        <div class="col-12">
                            <label for="notes" class="form-label">Дополнительные пожелания</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="2" placeholder="Комментарии к заказу...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag"></i> Ваш заказ
                </h5>
            </div>
            <div class="card-body">
                @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item['dish']->name }}</h6>
                            <small class="text-muted">{{ $item['dish']->restaurant->name }}</small>
                            <br>
                            <small class="text-muted">{{ $item['quantity'] }} × {{ number_format($item['dish']->price, 2, ',', ' ') }} BYN</small>
                            @if($item['special_instructions'])
                                <br><small class="text-info">
                                    <i class="fas fa-sticky-note"></i> {{ $item['special_instructions'] }}
                                </small>
                            @endif
                        </div>
                        <div class="text-end">
                            <strong>{{ number_format($item['dish']->price * $item['quantity'], 2, ',', ' ') }} BYN</strong>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <strong class="h5 mb-0">Итого:</strong>
                    <strong class="h5 mb-0 text-primary">{{ number_format($total, 2, ',', ' ') }} BYN</strong>
                </div>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check"></i> Подтвердить заказ
            </button>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary mt-2">
                <i class="fas fa-arrow-left"></i> Вернуться в корзину
            </a>
        </div>
        </form>
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

    // Инициализация - убеждаемся, что значение country_code начинается с +
    if (countryCodeInput.value && !countryCodeInput.value.startsWith('+')) {
        countryCodeInput.value = '+' + countryCodeInput.value.replace(/^\+/, '');
    }

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

    // Заполнение данных авторизованного пользователя
    @if(auth()->check() && auth()->user()->phone)
        @php
            $userPhone = auth()->user()->phone;
            $countryCode = '+375'; // По умолчанию Беларусь
            $phoneDigits = '';

            // Проверяем формат номера телефона
            if (preg_match('/^\+(\d{3})(\d+)$/', $userPhone, $matches)) {
                // Формат: +375291234567
                $countryCode = '+' . $matches[1];
                $phoneDigits = $matches[2];
            } elseif (preg_match('/^\+(\d{3})\s*\(\d{2}\)\s*\d{3}-\d{2}-\d{2}$/', $userPhone, $matches)) {
                // Формат: +375 (29) 370-95-05
                $countryCode = '+' . $matches[1];
                // Извлекаем цифры из форматированного номера
                $phoneDigits = preg_replace('/\D/', '', substr($userPhone, strpos($userPhone, '(')));
            } elseif (preg_match('/^\+(\d{3})\s+(\d+)$/', $userPhone, $matches)) {
                // Формат: +375 291234567
                $countryCode = '+' . $matches[1];
                $phoneDigits = $matches[2];
            }
        @endphp
        // Заполняем поля телефона для авторизованного пользователя
        document.getElementById('country_code_input').value = '{{ $countryCode }}';
        document.getElementById('phone').value = '{{ $phoneDigits }}';
    @endif

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

    // Функция для обновления скрытого поля delivery_time
    function updateDeliveryTime() {
        const date = document.getElementById('delivery_date').value;
        const timeSelect = document.getElementById('delivery_time_select').value;

        if (date && timeSelect) {
            const deliveryTime = `${date}T${timeSelect}:00`;
            document.getElementById('delivery_time').value = deliveryTime;
        } else {
            document.getElementById('delivery_time').value = '';
        }
    }

    // Функция для обновления доступных вариантов времени
    function updateTimeOptions() {
        const dateInput = document.getElementById('delivery_date');
        const timeSelect = document.getElementById('delivery_time_select');
        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const minTime = selectedDate.getTime() === today.getTime()
            ? new Date(Date.now() + 60 * 60 * 1000) // +1 час
            : new Date(selectedDate.getTime()); // начало дня

        // Очищаем текущие опции
        timeSelect.innerHTML = '';

        // Генерируем опции времени с шагом 5 минут
        for (let hour = 0; hour < 24; hour++) {
            for (let minute = 0; minute < 60; minute += 5) {
                const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                const optionDateTime = new Date(`${dateInput.value}T${timeString}:00`);

                // Показываем только доступное время
                if (optionDateTime >= minTime) {
                    const option = document.createElement('option');
                    option.value = timeString;
                    option.textContent = timeString;
                    timeSelect.appendChild(option);
                }
            }
        }

        // Выбираем ближайшее доступное время
        if (timeSelect.options.length > 0) {
            timeSelect.selectedIndex = 0;
        }

        updateDeliveryTime();
    }

    // Обработчики изменений
    document.getElementById('delivery_date').addEventListener('change', updateTimeOptions);
    document.getElementById('delivery_time_select').addEventListener('change', updateDeliveryTime);

    // Инициализация
    updateTimeOptions();
});
</script>
@endsection
