@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item active">Заказ #{{ $order->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt"></i> Заказ #{{ $order->id }}
                    </h4>
                    <span class="badge
                        @if($order->status === 'pending') bg-warning
                        @elseif($order->status === 'confirmed') bg-info
                        @elseif($order->status === 'preparing') bg-primary
                        @elseif($order->status === 'ready') bg-success
                        @elseif($order->status === 'delivered') bg-success
                        @else bg-danger
                        @endif">
                        @switch($order->status)
                            @case('pending') Ожидает подтверждения @break
                            @case('confirmed') Подтвержден @break
                            @case('preparing') Готовится @break
                            @case('ready') Готов @break
                            @case('delivered') Доставлен @break
                            @case('cancelled') Отменен @break
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-user"></i> Данные заказчика
                        </h5>
                        <p class="mb-1"><strong>Имя:</strong> {{ $order->customer_name }}</p>
                        <p class="mb-1"><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p class="mb-1"><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                        @if($order->delivery_time)
                            <p class="mb-1"><strong>Время доставки:</strong> {{ $order->delivery_time->format('d.m.Y H:i') }}</p>
                        @endif
                        @if($order->notes)
                            <p class="mb-1"><strong>Пожелания:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-calendar"></i> Информация о заказе
                        </h5>
                        <p class="mb-1"><strong>Дата заказа:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                        <p class="mb-1"><strong>Общая сумма:</strong> <span class="text-primary h5">{{ number_format($order->total_amount, 2, ',', ' ') }} BYN</span></p>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">
                    <i class="fas fa-shopping-bag"></i> Состав заказа
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Блюдо</th>
                                <th>Ресторан</th>
                                <th class="text-center">Количество</th>
                                <th class="text-end">Цена</th>
                                <th class="text-end">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->dish->name }}</strong>
                                        @if($item->special_instructions)
                                            <br><small class="text-muted">
                                                <i class="fas fa-sticky-note"></i> {{ $item->special_instructions }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $item->dish->restaurant->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 2, ',', ' ') }} BYN</td>
                                    <td class="text-end"><strong>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} BYN</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Итого:</strong></td>
                                <td class="text-end"><strong class="text-primary h5">{{ number_format($order->total_amount, 2, ',', ' ') }} BYN</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 text-center">
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Вернуться на главную
        </a>
    </div>
</div>
@endsection
