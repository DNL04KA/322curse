@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<div class="row">
    <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-list text-primary"></i> Мои заказы</h1>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-utensils"></i> Меню
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> История заказов</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID заказа</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Адрес доставки</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>#{{ $order->id }}</strong>
                                            </td>
                                            <td>
                                                {{ $order->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td>
                                                <strong class="text-primary">{{ number_format($order->total_amount, 2, ',', ' ') }} BYN</strong>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($order->delivery_address, 40) }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.orders.show', $order) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Подробнее
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $orders->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">У вас пока нет заказов</h5>
                            <p class="text-muted">Ваша история заказов будет отображаться здесь</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-utensils"></i> Сделать первый заказ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<style>
.status-badge {
    font-size: 0.8em;
    padding: 0.375em 0.75em;
}

.status-pending { background-color: #ffc107; color: #000; }
.status-confirmed { background-color: #17a2b8; color: #fff; }
.status-preparing { background-color: #fd7e14; color: #fff; }
.status-delivering { background-color: #007bff; color: #fff; }
.status-delivered { background-color: #28a745; color: #fff; }
.status-cancelled { background-color: #dc3545; color: #fff; }
</style>
@endsection
