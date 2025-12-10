@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Рестораны</a></li>
                <li class="breadcrumb-item active">Корзина</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="fas fa-shopping-cart text-primary"></i> Корзина
        </h1>
    </div>
</div>

@if(count($cartItems) > 0)
    @guest
        <div class="alert alert-info mb-4">
            <h5><i class="fas fa-info-circle"></i> Оформление заказа без регистрации</h5>
            <p class="mb-2">
                Вы можете оформить заказ без создания аккаунта! Просто заполните форму доставки
                с вашими контактными данными.
            </p>
            <small class="text-muted">
                💡 <strong>Рекомендуем зарегистрироваться</strong> для отслеживания заказов и получения уведомлений.
            </small>
        </div>
    @endguest

    <div class="row">
        <div class="col-lg-8">
            @foreach($cartItems as $item)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($item['dish']->image)
                                    <img src="{{ asset('storage/' . $item['dish']->image) }}" class="img-fluid rounded" alt="{{ $item['dish']->name }}" style="max-height: 80px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px; width: 80px;">
                                        <i class="fas fa-utensils text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('dishes.show', $item['dish']) }}" class="text-decoration-none text-dark">
                                        {{ $item['dish']->name }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-1">
                                    <a href="{{ route('restaurants.show', $item['dish']->restaurant) }}" class="text-decoration-none">
                                        {{ $item['dish']->restaurant->name }}
                                    </a>
                                </p>
                                <small class="text-primary">{{ number_format($item['dish']->price, 2, ',', ' ') }} BYN</small>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('cart.update', $item['dish']->id) }}" method="POST" class="update-cart-form d-flex align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <label class="form-label me-2 mb-0">Кол-во:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10"
                                           class="form-control form-control-sm me-2" style="width: 70px;">
                                </form>
                                @if($item['special_instructions'])
                                    <small class="text-muted">
                                        <i class="fas fa-sticky-note"></i> {{ Str::limit($item['special_instructions'], 50) }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <strong class="text-primary">{{ number_format($item['dish']->price * $item['quantity'], 2, ',', ' ') }} BYN</strong>
                            </div>
                            <div class="col-md-1">
                                <form action="{{ route('cart.remove', $item['dish']->id) }}" method="POST" class="remove-cart-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Продолжить покупки
                </a>
                <form action="{{ route('cart.clear') }}" method="POST" class="clear-cart-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-warning">
                        <i class="fas fa-trash"></i> Очистить корзину
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt"></i> Итого к оплате
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Блюд в корзине:</span>
                        <strong>{{ count($cartItems) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Общая сумма:</span>
                        <strong class="text-primary h5">
                            {{ number_format(collect($cartItems)->sum(function($item) {
                                return $item['dish']->price * $item['quantity'];
                            }), 2, ',', ' ') }} BYN
                        </strong>
                    </div>
                    <a href="{{ route('orders.create') }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-credit-card"></i> Оформить заказ
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h3>Корзина пуста</h3>
                <p class="text-muted mb-4">Добавьте блюда из меню ресторанов</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-utensils"></i> Выбрать ресторан
                </a>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка форм обновления количества
    document.querySelectorAll('.update-cart-form input[name="quantity"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            submitAjaxForm(form, 'Количество обновлено!');
        });
    });

    // Обработка форм удаления из корзины
    document.querySelectorAll('.remove-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Удалить блюдо из корзины?')) {
                submitAjaxForm(form, 'Блюдо удалено из корзины!', true);
            }
        });
    });

    // Обработка формы очистки корзины
    document.querySelectorAll('.clear-cart-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Очистить корзину?')) {
                submitAjaxForm(form, 'Корзина очищена!', true);
            }
        });
    });

    function submitAjaxForm(form, successMessage, reloadPage = false) {
        const formData = new FormData(form);
        const button = form.querySelector('button[type="submit"]');
        const originalText = button ? button.innerHTML : '';

        // Показываем загрузку
        if (button) {
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
        }

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(successMessage, 'success');

                // Обновляем счетчик корзины
                updateCartCounter(data.cart_count);

                if (reloadPage) {
                    // Для удаления и очистки - перезагружаем страницу
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Для обновления количества - обновляем итоговую сумму
                    updateCartTotals();
                }
            } else {
                showNotification('Ошибка при выполнении операции', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Произошла ошибка', 'danger');
        })
        .finally(() => {
            // Восстанавливаем кнопку
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
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

    function updateCartTotals() {
        // Обновляем итоговые суммы в корзине
        location.reload(); // Для простоты перезагружаем страницу
    }
});
</script>
@endpush
