@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="text-center py-5">
            <div class="success-checkmark mb-4"></div>
            <h1 class="mb-3 text-success">Заказ оформлен!</h1>
            <p class="lead mb-4">
                Спасибо за заказ! Мы свяжемся с вами в ближайшее время.
            </p>

            @guest
                <div class="alert alert-info mb-4">
                    <h5><i class="fas fa-info-circle"></i> Хотите отслеживать свои заказы?</h5>
                    <p class="mb-3">
                        Зарегистрируйтесь, чтобы видеть историю заказов, отслеживать статус доставки
                        и получать персональные предложения!
                    </p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus"></i> Зарегистрироваться
                        </a>
                        <a href="{{ route('orders.track') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i> Отследить заказ
                        </a>
                    </div>
                </div>
            @endguest

            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fas fa-utensils"></i> Заказать ещё
                </a>
                @auth
                    <a href="{{ route('user.orders.index') }}" class="btn btn-outline-info btn-lg">
                        <i class="fas fa-list"></i> Мои заказы
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
