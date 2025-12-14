@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Рестораны</a></li>
                <li class="breadcrumb-item active">{{ $restaurant->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>
            <i class="fas fa-utensils text-primary"></i> {{ $restaurant->name }}
        </h1>
        @if($restaurant->description)
            <p class="lead">{{ $restaurant->description }}</p>
        @endif
    </div>
    <div class="col-md-4 text-md-end">
        <p class="mb-1">
            <i class="fas fa-map-marker-alt text-muted"></i> {{ $restaurant->address }}
        </p>
        @if($restaurant->phone)
            <p class="mb-1">
                <i class="fas fa-phone text-muted"></i> {{ $restaurant->phone }}
            </p>
        @endif
    </div>
</div>

@php
    $groupedDishes = $restaurant->dishes->where('is_available', true)->groupBy('category');
@endphp

@if($groupedDishes->count() > 0)
    @foreach($groupedDishes as $category => $dishes)
        <div class="row mb-5">
            <div class="col-12">
                <h3 class="mb-3 border-bottom pb-2">
                    <i class="fas fa-utensils text-primary"></i> {{ $category }}
                    <span class="badge bg-primary ms-2">{{ $dishes->count() }}</span>
                </h3>
            </div>
            @foreach($dishes as $dish)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm dish-card">
                        @if($dish->image)
                            <img src="{{ asset('storage/' . $dish->image) }}" class="card-img-top" alt="{{ $dish->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-utensils fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $dish->name }}</h5>
                            @if($dish->description)
                                <p class="card-text text-muted">{{ Str::limit($dish->description, 80) }}</p>
                            @endif

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">{{ number_format($dish->price, 2, ',', ' ') }} BYN</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('dishes.show', $dish) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> Подробнее
                                    </a>
                                    <form action="{{ route('cart.add', $dish) }}" method="POST" class="add-to-cart-form d-inline">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="10" style="max-width: 80px;">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-cart-plus"></i> В корзину
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h4>Меню недоступно</h4>
                <p>В данный момент меню этого ресторана недоступно.</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Вернуться к ресторанам</a>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка форм добавления в корзину через AJAX
    document.querySelectorAll('.add-to-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            // Показываем загрузку
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Добавление...';
            button.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Анимация летящей карточки
                    animateToCart(button.closest('.card'));

                    // Анимация счетчика корзины
                    animateCartCounter();

                    // Показываем уведомление об успехе
                    showToast(data.message, 'success');

                    // Обновляем счетчик корзины в навигации
                    updateCartCounter(data.cart_count);
                } else {
                    showToast('Ошибка при добавлении в корзину', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка', 'danger');
            })
            .finally(() => {
                // Восстанавливаем кнопку
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    });


    function updateCartCounter(count) {
        const cartBadge = document.querySelector('.navbar-nav a[href*="cart"] .badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            if (count > 0) {
                cartBadge.style.display = 'inline-block';
            } else {
                cartBadge.style.display = 'none';
            }
        }
    }
});
</script>
@endpush
