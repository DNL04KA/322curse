@extends('layouts.app')

@section('title', $dish->name . ' - ' . $dish->restaurant->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Рестораны</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurants.show', $dish->restaurant) }}">{{ $dish->restaurant->name }}</a></li>
                <li class="breadcrumb-item active">{{ $dish->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-lg">
            <div class="row g-0">
                <div class="col-md-6">
                    @if($dish->image)
                        <img src="{{ asset('storage/' . $dish->image) }}" class="card-img-top rounded-start" alt="{{ $dish->name }}" style="height: 400px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center rounded-start" style="height: 400px;">
                            <i class="fas fa-utensils fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="card-body h-100 d-flex flex-column">
                        <div class="mb-auto">
                            <h1 class="card-title h2 mb-2">{{ $dish->name }}</h1>
                            <p class="text-muted mb-3">
                                <i class="fas fa-utensils me-2"></i>{{ $dish->category }} •
                                <a href="{{ route('restaurants.show', $dish->restaurant) }}" class="text-decoration-none">{{ $dish->restaurant->name }}</a>
                            </p>

                            @if($dish->description)
                                <p class="card-text mb-4">{{ $dish->description }}</p>
                            @endif

                            <!-- Питательная информация -->
                            @if($dish->calories || $dish->protein || $dish->fat || $dish->carbs)
                                <div class="card mb-4 border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-chart-pie me-2"></i>Питательная ценность
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            @if($dish->weight)
                                                <div class="col-6 col-md-3 mb-3">
                                                    <div class="h4 text-primary mb-1">{{ $dish->weight }}</div>
                                                    <small class="text-muted">Граммовка</small>
                                                </div>
                                            @endif
                                            @if($dish->calories)
                                                <div class="col-6 col-md-3 mb-3">
                                                    <div class="h4 text-warning mb-1">{{ $dish->calories }}</div>
                                                    <small class="text-muted">Ккал</small>
                                                </div>
                                            @endif
                                            @if($dish->protein)
                                                <div class="col-4 col-md-2 mb-3">
                                                    <div class="h5 text-success mb-1">{{ number_format($dish->protein, 1) }}</div>
                                                    <small class="text-muted">Белки (г)</small>
                                                </div>
                                            @endif
                                            @if($dish->fat)
                                                <div class="col-4 col-md-2 mb-3">
                                                    <div class="h5 text-danger mb-1">{{ number_format($dish->fat, 1) }}</div>
                                                    <small class="text-muted">Жиры (г)</small>
                                                </div>
                                            @endif
                                            @if($dish->carbs)
                                                <div class="col-4 col-md-2 mb-3">
                                                    <div class="h5 text-info mb-1">{{ number_format($dish->carbs, 1) }}</div>
                                                    <small class="text-muted">Углеводы (г)</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Цена -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center">
                                    <span class="h3 text-success mb-0 me-3">{{ number_format($dish->price, 2, ',', ' ') }} BYN</span>
                                    @if($dish->weight && $dish->calories)
                                        <small class="text-muted">
                                            ≈ {{ number_format($dish->calories / $dish->weight * 100, 0) }} ккал/100г
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="mt-4">
                            <div class="row g-2">
                                <div class="col-12">
                                    <form action="{{ route('cart.add', $dish) }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control form-control-lg" value="1" min="1" max="10" style="max-width: 100px;">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-cart-plus me-2"></i>Добавить в корзину
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12">
                                    <a href="{{ route('restaurants.show', $dish->restaurant) }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="fas fa-arrow-left me-2"></i>Вернуться в меню ресторана
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Информация о ресторане -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-store me-2"></i>{{ $dish->restaurant->name }}
                </h5>
            </div>
            <div class="card-body">
                @if($dish->restaurant->description)
                    <p class="text-muted mb-3">{{ Str::limit($dish->restaurant->description, 100) }}</p>
                @endif

                <div class="mb-2">
                    <i class="fas fa-map-marker-alt text-muted me-2"></i>{{ $dish->restaurant->address }}
                </div>

                @if($dish->restaurant->phone)
                    <div class="mb-2">
                        <i class="fas fa-phone text-muted me-2"></i>{{ $dish->restaurant->phone }}
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('restaurants.show', $dish->restaurant) }}" class="btn btn-primary w-100">
                        <i class="fas fa-utensils me-2"></i>Посмотреть меню
                    </a>
                </div>
            </div>
        </div>

        <!-- Похожие блюда -->
        @php
            $similarDishes = $dish->restaurant->dishes()
                ->where('category', $dish->category)
                ->where('id', '!=', $dish->id)
                ->where('is_available', true)
                ->take(3)
                ->get();
        @endphp

        @if($similarDishes->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-thumbs-up me-2"></i>Похожие блюда
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($similarDishes as $similarDish)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <a href="{{ route('dishes.show', $similarDish) }}" class="text-decoration-none">
                                    <strong>{{ $similarDish->name }}</strong>
                                </a>
                                <div class="text-success">{{ number_format($similarDish->price, 2, ',', ' ') }} BYN</div>
                            </div>
                            <a href="{{ route('dishes.show', $similarDish) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка формы добавления в корзину
    const form = document.querySelector('.add-to-cart-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            // Показываем загрузку
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Добавление...';
            button.disabled = true;

            const formData = new FormData(this);

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
                    // Показываем уведомление
                    showNotification(data.message, 'success');

                    // Обновляем счетчик корзины
                    updateCartCounter(data.cart_count);
                } else {
                    showNotification('Ошибка при добавлении в корзину', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Произошла ошибка', 'danger');
            })
            .finally(() => {
                // Восстанавливаем кнопку
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

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