@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Заголовок -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-user-circle text-primary"></i> Мой профиль</h1>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Редактировать
                </a>
            </div>

            <!-- Карточка профиля -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Аватар и имя -->
                    <div class="text-center mb-4">
                        <div class="profile-avatar mx-auto mb-3" style="width: 120px; height: 120px;">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="fas fa-user text-white" style="font-size: 3.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        @if($user->is_admin)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown"></i> Администратор
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-user"></i> Пользователь
                            </span>
                        @endif
                    </div>

                    <hr>

                    <!-- Информация о профиле -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone text-primary fa-lg me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Телефон</h6>
                                    <p class="mb-0">{{ $user->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope text-primary fa-lg me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Email</h6>
                                    <p class="mb-0">{{ $user->email ?? 'Не указан' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar text-primary fa-lg me-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Дата регистрации</h6>
                                    <p class="mb-0">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($user->email_verified_at)
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle text-success fa-lg me-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-muted">Статус верификации</h6>
                                        <p class="mb-0 text-success">
                                            <i class="fas fa-check"></i> Email подтвержден
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <!-- Действия -->
                    <div class="row g-2">
                        <div class="col-md-6">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-edit"></i> Редактировать профиль
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('profile.password') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-key"></i> Изменить пароль
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('user.orders.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-list"></i> Мои заказы
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика (опционально) -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-line text-primary"></i> Статистика
                    </h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-shopping-bag text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0">{{ $user->orders->count() }}</h4>
                            <small class="text-muted">Заказов</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0">{{ $user->orders->where('status', 'delivered')->count() }}</h4>
                            <small class="text-muted">Доставлено</small>
                        </div>
                        <div class="col-4">
                            <div class="mb-2">
                                <i class="fas fa-ruble-sign text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h4 class="mb-0">{{ number_format($user->orders->where('status', 'delivered')->sum('total_amount'), 0, ',', ' ') }}</h4>
                            <small class="text-muted">BYN потрачено</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
