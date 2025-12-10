@extends('layouts.app')

@section('title', 'Найденные заказы')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-receipt text-primary"></i> Найденные заказы</h1>
                <div>
                    <a href="{{ route('orders.track') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Новый поиск
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-utensils"></i> Меню
                    </a>
                </div>
            </div>

            @if($orders->count() > 1)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Найдено {{ $orders->count() }} заказов по номеру телефона {{ $phone }}
                </div>
            @endif

            @foreach($orders as $order)

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Состав заказа #{{ $order->id }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->orderItems as $item)
                                <div class="row align-items-center mb-3 pb-3 border-bottom">
                                    <div class="col-md-2">
                                        <div class="bg-light rounded p-2 text-center">
                                            <i class="fas fa-utensils fa-2x text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-1">{{ $item->dish->name }}</h6>
                                        <p class="text-muted mb-1">{{ $item->dish->restaurant->name }}</p>
                                        @if($item->special_instructions)
                                            <small class="text-info">
                                                <i class="fas fa-sticky-note"></i> {{ $item->special_instructions }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <span class="badge bg-secondary">{{ $item->quantity }} шт.</span>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong class="text-primary">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} BYN</strong>
                                        <br>
                                        <small class="text-muted">{{ number_format($item->price, 2, ',', ' ') }} BYN/шт</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Информация о заказе</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Дата заказа:</strong><br>
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </div>

                            <div class="mb-3">
                                <strong>Статус:</strong><br>
                                <span class="badge status-badge status-{{ $order->status }}">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Ожидает подтверждения',
                                            'confirmed' => 'Подтвержден',
                                            'preparing' => 'Готовится',
                                            'delivering' => 'Доставляется',
                                            'delivered' => 'Доставлен',
                                            'cancelled' => 'Отменен'
                                        ];
                                        echo $statusLabels[$order->status] ?? 'Неизвестный статус';
                                    @endphp
                                </span>
                            </div>

                            <div class="mb-3">
                                <strong>Получатель:</strong><br>
                                {{ $order->customer_name }}
                            </div>

                            <div class="mb-3">
                                <strong>Телефон:</strong><br>
                                <i class="fas fa-phone text-muted"></i> {{ $order->customer_phone }}
                            </div>

                            @if($order->customer_email)
                                <div class="mb-3">
                                    <strong>Email:</strong><br>
                                    <i class="fas fa-envelope text-muted"></i> {{ $order->customer_email }}
                                </div>
                            @endif

                            @if($order->delivery_time)
                                <div class="mb-3">
                                    <strong>Время доставки:</strong><br>
                                    {{ $order->delivery_time->format('d.m.Y H:i') }}
                                </div>
                            @endif

                            @if($order->notes)
                                <div class="mb-3">
                                    <strong>Комментарий:</strong><br>
                                    {{ $order->notes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Адрес доставки</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $order->delivery_address }}</p>
                        </div>
                    </div>

                    <div class="card shadow mt-3">
                        <div class="card-body text-center">
                            <h4 class="text-primary mb-0">
                                <strong>Итого: {{ number_format($order->total_amount, 2, ',', ' ') }} BYN</strong>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$loop->last)
        <hr class="my-5">
    @endif
@endforeach
</div>

<style>
.status-badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}

.status-pending { background-color: #ffc107; color: #000; }
.status-confirmed { background-color: #17a2b8; color: #fff; }
.status-preparing { background-color: #fd7e14; color: #fff; }
.status-delivering { background-color: #007bff; color: #fff; }
.status-delivered { background-color: #28a745; color: #fff; }
.status-cancelled { background-color: #dc3545; color: #fff; }
</style>
@endsection
