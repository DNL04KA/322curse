@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')
<div class="row">
    <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-users text-primary"></i> Управление пользователями</h1>
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-clipboard-list"></i> Заказы
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> На главную
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Список пользователей</h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Добавить пользователя
                    </a>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Имя</th>
                                        <th>Телефон</th>
                                        <th>Email</th>
                                        <th>Роль</th>
                                        <th>Дата регистрации</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <strong>#{{ $user->id }}</strong>
                                            </td>
                                            <td>
                                                {{ $user->name }}
                                            </td>
                                            <td>
                                                <i class="fas fa-phone text-muted"></i> {{ $user->phone }}
                                            </td>
                                            <td>
                                                @if($user->email)
                                                    <i class="fas fa-envelope text-muted"></i> {{ $user->email }}
                                                @else
                                                    <span class="text-muted">Не указан</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->is_admin)
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-crown"></i> Администратор
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-user"></i> Пользователь
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $user->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.show', $user) }}"
                                                       class="btn btn-sm btn-outline-primary" title="Просмотр">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                       class="btn btn-sm btn-outline-warning" title="Редактировать">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($user->id !== auth()->id())
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                <i class="fas fa-cog"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><h6 class="dropdown-header">Действия</h6></li>
                                                                <li>
                                                                    <form action="{{ route('admin.users.toggle-admin', $user) }}"
                                                                          method="POST" class="toggle-admin-form d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit" class="dropdown-item">
                                                                            @if($user->is_admin)
                                                                                <i class="fas fa-user-minus text-warning"></i> Снять права админа
                                                                            @else
                                                                                <i class="fas fa-user-plus text-success"></i> Назначить админом
                                                                            @endif
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger delete-user"
                                                                            data-user-id="{{ $user->id }}"
                                                                            data-user-name="{{ $user->name }}">
                                                                        <i class="fas fa-trash"></i> Удалить
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <span class="text-muted small">(Это вы)</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $users->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Пользователей пока нет</h5>
                            <p class="text-muted">Новые пользователи появятся здесь автоматически</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Создать первого пользователя
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Вы действительно хотите удалить пользователя <strong id="userName"></strong>?</p>
                <p class="text-danger"><small>Это действие нельзя отменить!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.badge {
    font-size: 0.8em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle admin forms
    document.querySelectorAll('.toggle-admin-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            // Показываем загрузку
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
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
                    // Перезагружаем страницу через 1 секунду
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('Ошибка при изменении статуса', 'danger');
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
    });

    // Handle delete user
    document.querySelectorAll('.delete-user').forEach(function(button) {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');

            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteForm').action = `/admin/users/${userId}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

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
});
</script>

@endsection
