@extends('layouts.app')

@section('title', 'Рестораны')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div class="text-center mb-5">
            <h1 class="display-4 mb-3">
                <i class="fas fa-utensils text-primary"></i> FoodOrder
            </h1>
            <p class="lead text-muted mb-4">Заказывайте еду из лучших ресторанов Минска</p>

            <!-- Большая поисковая строка -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group input-group-lg shadow">
                            <input type="text" name="q" class="form-control border-0"
                                   placeholder="Что хотите заказать? Например: борщ, пицца, бургер..."
                                   value="{{ request('q') }}"
                                   autocomplete="off"
                                   id="main-search-input">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-search me-2"></i>Найти
                            </button>
                        </div>
                    </form>
                    <div id="main-search-suggestions" class="bg-white border rounded shadow-sm d-none mt-1 search-suggestions" style="z-index: 1000; max-height: 300px; overflow-y: auto;"></div>
                </div>
            </div>

            <!-- Популярные категории -->
            <div class="mt-4">
                <p class="text-muted mb-2">Популярные категории:</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('search', ['q' => 'борщ']) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-utensils me-1"></i> Борщ
                    </a>
                    <a href="{{ route('search', ['q' => 'пицца']) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-pizza-slice me-1"></i> Пицца
                    </a>
                    <a href="{{ route('search', ['q' => 'бургер']) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-hamburger me-1"></i> Бургер
                    </a>
                    <a href="{{ route('search', ['q' => 'паста']) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-spaghetti me-1"></i> Паста
                    </a>
                    <a href="{{ route('search', ['q' => 'суши']) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-fish me-1"></i> Суши
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-store text-primary"></i> Выберите ресторан
        </h2>
        <p class="text-muted">Или используйте поиск выше для быстрого нахождения нужных блюд</p>
    </div>
</div>

<div class="row">
    @forelse($restaurants as $restaurant)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm restaurant-card">
                @if($restaurant->image)
                    <img src="{{ asset('storage/' . $restaurant->image) }}" class="card-img-top" alt="{{ $restaurant->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="fas fa-utensils fa-3x text-muted"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                    @if($restaurant->description)
                        <p class="card-text text-muted">{{ Str::limit($restaurant->description, 100) }}</p>
                    @endif
                    <div class="mt-auto">
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-muted"></i> {{ $restaurant->address }}
                        </p>
                        @if($restaurant->phone)
                            <p class="card-text">
                                <i class="fas fa-phone text-muted"></i> {{ $restaurant->phone }}
                            </p>
                        @endif
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye"></i> Посмотреть меню
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-2x mb-3"></i>
                <h4>Рестораны не найдены</h4>
                <p>В данный момент нет доступных ресторанов.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('main-search-input');
        const suggestionsContainer = document.getElementById('main-search-suggestions');
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
                setTimeout(() => {
                    hideSuggestions();
                }, 200);
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    fetchSuggestions(this.value.trim());
                }
            });

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
                item.className = 'list-group-item list-group-item-action px-3 py-2 border-0';
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
    });
</script>
@endpush
