@extends('layouts.app')

@section('title', 'Поиск блюд')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><i class="fas fa-search text-primary"></i> Поиск блюд</h1>
                @if($query)
                    <p class="text-muted mb-0">Результаты поиска по запросу: <strong>"{{ $query }}"</strong></p>
                    <p class="text-muted">Найдено: {{ $totalResults }} результатов в {{ $restaurants->count() }} ресторанах</p>
                @else
                    <p class="text-muted mb-0">Введите название блюда для поиска</p>
                @endif
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> На главную
            </a>
        </div>

        @if($query && $restaurants->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Ничего не найдено</h4>
                <p class="text-muted">Попробуйте изменить запрос или выбрать другой ресторан</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-utensils"></i> Посмотреть все рестораны
                </a>
            </div>
        @elseif($restaurants->isNotEmpty())
            @foreach($restaurants as $restaurantData)
                <div class="card shadow mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <a href="{{ route('restaurants.show', $restaurantData['restaurant']) }}" class="text-decoration-none">
                                        {{ $restaurantData['restaurant']->name }}
                                    </a>
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $restaurantData['restaurant']->address }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-phone"></i> {{ $restaurantData['restaurant']->phone }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ $restaurantData['dish_count'] }} блюд</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($restaurantData['dishes'] as $dish)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-1">{{ $dish->name }}</h6>
                                                <span class="badge bg-secondary">{{ $dish->category }}</span>
                                            </div>
                                            <p class="card-text text-muted small">{{ $dish->description }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="h5 text-primary mb-0">{{ number_format($dish->price, 2) }} BYN</span>
                                                <form action="{{ route('cart.add', $dish) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-cart-plus"></i> В корзину
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!$query)
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4>Начните поиск</h4>
                    <p class="text-muted">Введите название блюда в поле поиска выше</p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="list-group text-start">
                                <div class="list-group-item">
                                    <strong>Популярные запросы:</strong>
                                </div>
                                <a href="{{ route('search', ['q' => 'борщ']) }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-utensils text-primary me-2"></i> борщ
                                </a>
                                <a href="{{ route('search', ['q' => 'пицца']) }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-pizza-slice text-warning me-2"></i> пицца
                                </a>
                                <a href="{{ route('search', ['q' => 'бургер']) }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-hamburger text-danger me-2"></i> бургер
                                </a>
                                <a href="{{ route('search', ['q' => 'паста']) }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-spaghetti text-info me-2"></i> паста
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

