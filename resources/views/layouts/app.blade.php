<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Сервис заказа еды') - FoodOrder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('styles')
    <style>
        /* Sticky Footer */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }

        /* ===== АНИМАЦИИ ===== */

        /* 1. Анимации карточек ресторанов и блюд */
        .restaurant-card, .dish-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .restaurant-card:nth-child(1) { animation-delay: 0.1s; }
        .restaurant-card:nth-child(2) { animation-delay: 0.15s; }
        .restaurant-card:nth-child(3) { animation-delay: 0.2s; }
        .restaurant-card:nth-child(4) { animation-delay: 0.25s; }
        .restaurant-card:nth-child(5) { animation-delay: 0.3s; }
        .restaurant-card:nth-child(6) { animation-delay: 0.35s; }
        .restaurant-card:nth-child(7) { animation-delay: 0.4s; }
        .restaurant-card:nth-child(8) { animation-delay: 0.45s; }
        .restaurant-card:nth-child(9) { animation-delay: 0.5s; }
        .restaurant-card:nth-child(10) { animation-delay: 0.55s; }

        .dish-card:nth-child(1) { animation-delay: 0.1s; }
        .dish-card:nth-child(2) { animation-delay: 0.15s; }
        .dish-card:nth-child(3) { animation-delay: 0.2s; }
        .dish-card:nth-child(4) { animation-delay: 0.25s; }
        .dish-card:nth-child(5) { animation-delay: 0.3s; }
        .dish-card:nth-child(6) { animation-delay: 0.35s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 2. Анимация добавления в корзину */
        .flying-dish {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            animation: flyToCart 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes flyToCart {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.3); opacity: 0; }
        }

        /* 3. Анимация изменения цены в корзине */
        .price-update {
            position: relative;
            animation: pricePulse 0.6s ease-in-out;
        }

        .price-update::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(23, 162, 184, 0.1), rgba(255, 193, 7, 0.1));
            border-radius: 8px;
            animation: priceGlow 0.6s ease-in-out;
            z-index: -1;
        }

        @keyframes pricePulse {
            0% {
                transform: scale(1);
                color: inherit;
            }
            25% {
                transform: scale(1.05);
                color: #28a745;
                font-weight: bold;
            }
            50% {
                transform: scale(1.08);
                color: #17a2b8;
                font-weight: bold;
            }
            75% {
                transform: scale(1.05);
                color: #ffc107;
                font-weight: bold;
            }
            100% {
                transform: scale(1);
                color: inherit;
                font-weight: normal;
            }
        }

        @keyframes priceGlow {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            50% {
                opacity: 1;
                transform: scale(1.02);
            }
            100% {
                opacity: 0;
                transform: scale(0.95);
            }
        }

        /* 4. Прогресс-бар оформления заказа */
        .order-progress {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            padding: 0 1rem;
            min-width: 100px;
        }

        .progress-step::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 100%;
            width: 80px;
            height: 3px;
            background: #e9ecef;
            z-index: 1;
        }

        .progress-step:last-child::after {
            display: none;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .progress-step.active .step-circle {
            background: #007bff;
            color: white;
            animation: stepPulse 0.6s ease-out;
        }

        .progress-step.completed .step-circle {
            background: #28a745;
            color: white;
        }

        .progress-step.completed::after {
            background: #28a745;
            animation: progressFill 0.8s ease-out;
        }

        .step-label {
            font-size: 0.8rem;
            text-align: center;
            color: #6c757d;
        }

        .progress-step.active .step-label {
            color: #007bff;
            font-weight: 500;
        }

        .progress-step.completed .step-label {
            color: #28a745;
            font-weight: 500;
        }

        @keyframes stepPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes progressFill {
            from { width: 0; }
            to { width: 80px; }
        }

        /* 5. Анимация успешного заказа */
        .success-checkmark {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem auto;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
            animation: checkmarkExpand 0.8s ease-out;
        }

        .success-checkmark::after {
            content: '✓';
            font-size: 4rem;
            color: white;
            font-weight: bold;
            animation: checkmarkDraw 0.8s ease-out 0.2s both;
        }

        @keyframes checkmarkExpand {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes checkmarkDraw {
            from {
                opacity: 0;
                transform: scale(0.5) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0deg);
            }
        }

        /* 6. Анимации поиска */
        .search-suggestions {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 7. Анимации кнопок (hover эффекты) */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* 8. Анимация загрузки контента (skeleton) */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
            border-radius: 8px;
        }

        .skeleton-card {
            height: 200px;
            margin-bottom: 1rem;
        }

        .skeleton-title {
            height: 20px;
            width: 70%;
            margin-bottom: 0.5rem;
        }

        .skeleton-text {
            height: 15px;
            width: 90%;
            margin-bottom: 0.3rem;
        }

        .skeleton-text:last-child {
            width: 50%;
        }

        .skeleton-button {
            height: 35px;
            width: 100px;
            border-radius: 20px;
        }

        @keyframes skeleton-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* 9. Анимации форм */
        .form-control:focus {
            transform: scale(1.01);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transition: all 0.3s ease;
        }

        .form-control.is-invalid {
            animation: shake 0.5s ease-in-out;
            border-color: #dc3545 !important;
        }

        .form-control.is-valid {
            animation: success-glow 0.5s ease-out;
            border-color: #28a745 !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @keyframes success-glow {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }

        /* 10. Анимации статуса заказов */
        .status-badge {
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .status-updated {
            animation: statusPulse 0.6s ease-out;
        }

        @keyframes statusPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Адаптация для пользователей с ограниченными анимациями */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-utensils"></i> FoodOrder
            </a>

            <!-- Поисковая строка -->
            <div class="d-none d-lg-block flex-grow-1 mx-4" style="max-width: 400px;">
                <form action="{{ route('search') }}" method="GET" class="position-relative">
                    <input type="text" name="q" class="form-control"
                           placeholder="Поиск блюд..."
                           value="{{ request('q') }}"
                           autocomplete="off"
                           id="search-input">
                    <button type="submit" class="btn position-absolute end-0 top-0" style="border: none; background: none;">
                        <i class="fas fa-search text-muted"></i>
                    </button>
                </form>
                <div id="search-suggestions" class="position-absolute bg-white border rounded shadow-sm d-none search-suggestions" style="z-index: 1000; width: 100%; max-height: 300px; overflow-y: auto;"></div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Мобильная поисковая строка -->
                <div class="d-lg-none mb-3">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control"
                                   placeholder="Поиск блюд..."
                                   value="{{ request('q') }}">
                            <button type="submit" class="btn btn-outline-light">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Рестораны</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i> Корзина
                            <span class="badge bg-primary" style="{{ session('cart') ? '' : 'display: none;' }}">{{ count(session('cart', [])) }}</span>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.orders.index') }}">
                                <i class="fas fa-list"></i> Мои заказы
                            </a>
                        </li>
                        @if(auth()->user()->is_admin)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog"></i> Админка
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                        <i class="fas fa-clipboard-list"></i> Управление заказами
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-users"></i> Управление пользователями
                                    </a></li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i> Мой профиль
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.password') }}">
                                    <i class="fas fa-key"></i> Сменить пароль
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> Выйти
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Войти
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>FoodOrder</h5>
                    <p>Сервис для заказа еды из местных столовых и кафе</p>
                </div>
                <div class="col-md-6 text-end">
                    <!-- Копирайт убран -->
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <!-- JavaScript для поиска -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const suggestionsContainer = document.getElementById('search-suggestions');
            let timeoutId;

            if (searchInput && suggestionsContainer) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        if (query.length >= 2) {
                            fetchSuggestions(query);
                        } else {
                            hideSuggestions();
                        }
                    }, 300);
                });

                searchInput.addEventListener('blur', function() {
                    // Задержка для клика по предложению
                    setTimeout(() => {
                        hideSuggestions();
                    }, 200);
                });

                searchInput.addEventListener('focus', function() {
                    if (this.value.trim().length >= 2) {
                        fetchSuggestions(this.value.trim());
                    }
                });
            }

            function fetchSuggestions(query) {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        showSuggestions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        hideSuggestions();
                    });
            }

            function showSuggestions(suggestions) {
                if (suggestions.length === 0) {
                    hideSuggestions();
                    return;
                }

                suggestionsContainer.innerHTML = '';
                suggestions.forEach(suggestion => {
                    const item = document.createElement('a');
                    item.href = `{{ route('search') }}?q=${encodeURIComponent(suggestion.name)}`;
                    item.className = 'list-group-item list-group-item-action px-3 py-2';
                    item.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${suggestion.name}</strong>
                                <small class="text-muted ms-2">${suggestion.category}</small>
                            </div>
                            <i class="fas fa-search text-muted"></i>
                        </div>
                    `;
                    suggestionsContainer.appendChild(item);
                });

                suggestionsContainer.classList.remove('d-none');
            }

            function hideSuggestions() {
                suggestionsContainer.classList.add('d-none');
            }

            // Обработка нажатия Enter в поисковой строке
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const form = this.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                });
            }

            // ===== ФУНКЦИИ АНИМАЦИЙ =====

            // Анимация летящей карточки в корзину
            window.animateToCart = function(element) {
                // Ищем иконку корзины в навигации
                const cartIcon = document.querySelector('.navbar-nav a[href*="cart"] .fa-shopping-cart');
                if (!cartIcon) {
                    console.warn('Cart icon not found');
                    return;
                }

                const cartRect = cartIcon.getBoundingClientRect();
                const elementRect = element.getBoundingClientRect();

                // Создаем клон элемента
                const flyingElement = document.createElement('div');
                flyingElement.className = 'flying-dish';
                flyingElement.innerHTML = '🍽️';
                flyingElement.style.left = (elementRect.left + elementRect.width / 2 - 25) + 'px';
                flyingElement.style.top = (elementRect.top + elementRect.height / 2 - 25) + 'px';

                document.body.appendChild(flyingElement);

                // Анимируем к корзине
                setTimeout(() => {
                    flyingElement.style.left = (cartRect.left + cartRect.width / 2 - 25) + 'px';
                    flyingElement.style.top = (cartRect.top + cartRect.height / 2 - 25) + 'px';
                }, 10);

                // Удаляем элемент после анимации
                setTimeout(() => {
                    if (flyingElement.parentNode) {
                        flyingElement.parentNode.removeChild(flyingElement);
                    }
                }, 800);
            };

            // Анимация обновления цены
            window.animatePriceUpdate = function(priceElement) {
                if (!priceElement) return;

                // Добавляем класс анимации
                priceElement.classList.add('price-update');

                // Удаляем класс через время анимации
                setTimeout(() => {
                    priceElement.classList.remove('price-update');
                }, 600); // 0.6s как в CSS анимации
            };

            // Анимация счетчика корзины
            window.animateCartCounter = function() {
                const cartBadge = document.querySelector('.navbar-nav a[href*="cart"] .badge');
                if (cartBadge) {
                    cartBadge.classList.add('cart-bounce');
                    setTimeout(() => {
                        cartBadge.classList.remove('cart-bounce');
                    }, 600);
                }
            };

            // Анимация уведомлений
            window.showToast = function(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                toast.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('fade');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 500);
                }, 3000);
            };

            // Добавляем bounce анимацию для счетчика корзины
            const style = document.createElement('style');
            style.textContent = `
                .cart-bounce {
                    animation: cartBounce 0.6s ease-out;
                }
                @keyframes cartBounce {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.2); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>
