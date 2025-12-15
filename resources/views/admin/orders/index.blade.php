@extends('layouts.app')

@section('title', 'Управление заказами')

@section('content')
<div class="row">
    <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-clipboard-list text-primary"></i> Управление заказами</h1>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-users"></i> Управление пользователями
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> На главную
                    </a>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Список заказов</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Клиент</th>
                                        <th>Телефон</th>
                                        <th>Адрес доставки</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th>Дата заказа</th>
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
                                                {{ $order->customer_name }}<br>
                                                <small class="text-muted">{{ $order->customer_email }}</small>
                                            </td>
                                            <td>
                                                <i class="fas fa-phone text-muted"></i> {{ $order->customer_phone }}
                                            </td>
                                            <td>
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                                <small>{{ Str::limit($order->delivery_address, 50) }}</small>
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
                                                {{ $order->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('orders.show', $order) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Просмотр">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><h6 class="dropdown-header">Изменить статус</h6></li>
                                                            <li><a class="dropdown-item status-change"
                                                                   href="#" data-order-id="{{ $order->id }}"
                                                                   data-status="confirmed">
                                                                <i class="fas fa-check text-success"></i> Подтвержден
                                                            </a></li>
                                                            <li><a class="dropdown-item status-change"
                                                                   href="#" data-order-id="{{ $order->id }}"
                                                                   data-status="preparing">
                                                                <i class="fas fa-utensils text-warning"></i> Готовится
                                                            </a></li>
                                                            <li><a class="dropdown-item status-change"
                                                                   href="#" data-order-id="{{ $order->id }}"
                                                                   data-status="delivering">
                                                                <i class="fas fa-truck text-info"></i> Доставляется
                                                            </a></li>
                                                            <li><a class="dropdown-item status-change"
                                                                   href="#" data-order-id="{{ $order->id }}"
                                                                   data-status="delivered">
                                                                <i class="fas fa-check-double text-success"></i> Доставлен
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item status-change text-danger"
                                                                   href="#" data-order-id="{{ $order->id }}"
                                                                   data-status="cancelled">
                                                                <i class="fas fa-times"></i> Отменен
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Заказов пока нет</h5>
                            <p class="text-muted">Новые заказы появятся здесь автоматически</p>
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

/* Исправление обрезания dropdown меню */
.dropdown-menu {
    z-index: 1050;
    min-width: 200px;
}

.dropdown-menu.show {
    display: block;
    position: absolute;
}

/* Уведомления */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 500px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка изменения статуса заказа
    document.querySelectorAll('.status-change').forEach(function(element) {
        element.addEventListener('click', function(e) {
            e.preventDefault();

            const orderId = this.getAttribute('data-order-id');
            const newStatus = this.getAttribute('data-status');

            if (confirm('Изменить статус заказа?')) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Показываем уведомление об успехе
                        showNotification('Статус заказа успешно изменен!', 'success');
                        // Обновляем статус в таблице без перезагрузки
                        updateOrderStatus(orderId, newStatus);
                    } else {
                        showNotification('Ошибка при изменении статуса', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Произошла ошибка при изменении статуса', 'danger');
                });
            }
        });
    });
});

// Функция для показа уведомлений
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show notification`;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    document.body.appendChild(notification);

    // Автоматически скрываем через 3 секунды
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Функция для обновления статуса заказа в таблице без перезагрузки
function updateOrderStatus(orderId, newStatus) {
    // Находим строку заказа по data-order-id в кнопках
    const statusChangeBtn = document.querySelector(`.status-change[data-order-id="${orderId}"]`);
    if (statusChangeBtn) {
        const row = statusChangeBtn.closest('tr');
        const statusBadge = row.querySelector('.badge');

        if (statusBadge) {
            // Удаляем все классы статусов
            statusBadge.className = 'badge status-badge';

            // Добавляем новый класс статуса
            statusBadge.classList.add(`status-${newStatus}`);

            // Обновляем текст статуса
            const statusLabels = {
                'pending': 'Ожидает подтверждения',
                'confirmed': 'Подтвержден',
                'preparing': 'Готовится',
                'delivering': 'Доставляется',
                'delivered': 'Доставлен',
                'cancelled': 'Отменен'
            };

            statusBadge.textContent = statusLabels[newStatus] || 'Неизвестный статус';
        }
    }
}
</script>

@endsection
