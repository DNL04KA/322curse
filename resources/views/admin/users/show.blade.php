@extends('layouts.app')

@section('title', 'Пользователь: ' . $user->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Управление пользователями</a></li>
                <li class="breadcrumb-item active">Пользователь #{{ $user->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-user text-primary"></i> Пользователь: {{ $user->name }}</h1>
            <div>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Редактировать
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> К списку пользователей
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Основная информация о пользователе -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-id-card"></i> Основная информация
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID пользователя:</label>
                            <p class="mb-0">#{{ $user->id }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Имя:</label>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Телефон:</label>
                            <p class="mb-0">
                                <i class="fas fa-phone text-muted me-1"></i>{{ $user->phone }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p class="mb-0">
                                @if($user->email)
                                    <i class="fas fa-envelope text-muted me-1"></i>{{ $user->email }}
                                @else
                                    <span class="text-muted">Не указан</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Роль:</label>
                            <p class="mb-0">
                                @if($user->is_admin)
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-crown"></i> Администратор
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-user"></i> Пользователь
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Дата регистрации:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar text-muted me-1"></i>{{ $user->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Последнее обновление:</label>
                            <p class="mb-0">
                                <i class="fas fa-clock text-muted me-1"></i>{{ $user->updated_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика заказов -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Статистика заказов
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="h3 text-primary">{{ $user->orders()->count() }}</div>
                        <div class="text-muted">Всего заказов</div>
                    </div>
                    <div class="col-md-3">
                        <div class="h3 text-success">{{ $user->orders()->where('status', 'delivered')->count() }}</div>
                        <div class="text-muted">Доставлено</div>
                    </div>
                    <div class="col-md-3">
                        <div class="h3 text-warning">{{ $user->orders()->where('status', 'pending')->count() }}</div>
                        <div class="text-muted">Ожидают</div>
                    </div>
                    <div class="col-md-3">
                        <div class="h3 text-danger">{{ $user->orders()->where('status', 'cancelled')->count() }}</div>
                        <div class="text-muted">Отменено</div>
                    </div>
                </div>
                @if($user->orders()->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Общая сумма заказов:</strong>
                            <div class="h4 text-primary">
                                {{ number_format($user->orders()->sum('total_amount'), 2, ',', ' ') }} BYN
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Средний чек:</strong>
                            <div class="h4 text-info">
                                {{ number_format($user->orders()->avg('total_amount'), 2, ',', ' ') }} BYN
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Последние заказы -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-bag"></i> Последние заказы
                </h5>
            </div>
            <div class="card-body">
                @if($user->orders()->count() > 0)
                    @foreach($user->orders()->latest()->take(5)->get() as $order)
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                            <div class="flex-grow-1">
                                <div class="fw-bold">#{{ $order->id }}</div>
                                <small class="text-muted">{{ $order->created_at->format('d.m.Y H:i') }}</small>
                                <div class="mt-1">
                                    <span class="badge
                                        @if($order->status === 'pending') bg-warning
                                        @elseif($order->status === 'confirmed') bg-info
                                        @elseif($order->status === 'preparing') bg-primary
                                        @elseif($order->status === 'delivering') bg-warning
                                        @elseif($order->status === 'delivered') bg-success
                                        @else bg-danger
                                        @endif">
                                        @switch($order->status)
                                            @case('pending') Ожидает @break
                                            @case('confirmed') Подтвержден @break
                                            @case('preparing') Готовится @break
                                            @case('delivering') Доставляется @break
                                            @case('delivered') Доставлен @break
                                            @case('cancelled') Отменен @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ number_format($order->total_amount, 2, ',', ' ') }} BYN</div>
                                <small class="text-muted">{{ $order->orderItems->count() }} поз.</small>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center">
                        <a href="{{ route('user.orders.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-list"></i> Все заказы пользователя
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">У пользователя пока нет заказов</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection




