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
                <div id="search-suggestions" class="position-absolute bg-white border rounded shadow-sm d-none" style="z-index: 1000; width: 100%; max-height: 300px; overflow-y: auto;"></div>
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
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">
                                    <i class="fas fa-sign-out-alt"></i> Выйти
                                </button>
                            </form>
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
        });
    </script>
</body>
</html>
