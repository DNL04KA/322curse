@extends('layouts.app')

@section('title', 'Управление ресторанами')

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-store"></i> Управление ресторанами</h1>
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
                <i class="fas fa-plus fa-sm"></i> Добавить ресторан
            </a>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Адрес</th>
                        <th>Телефон</th>
                        <th>Блюд</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                        <tr>
                            <td>{{ $restaurant->id }}</td>
                            <td>
                                @if($restaurant->image)
                                    <img src="{{ asset('storage/' . $restaurant->image) }}" 
                                         alt="{{ $restaurant->name }}" 
                                         class="rounded me-2" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <strong>{{ $restaurant->name }}</strong>
                            </td>
                            <td>{{ $restaurant->address }}</td>
                            <td>{{ $restaurant->phone ?? '—' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $restaurant->dishes_count }}</span>
                            </td>
                            <td>
                                @if($restaurant->is_active)
                                    <span class="badge bg-success">Активен</span>
                                @else
                                    <span class="badge bg-secondary">Неактивен</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" 
                                       class="btn btn-info btn-sm" title="Просмотр">
                                        <i class="fas fa-eye fa-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}" 
                                       class="btn btn-warning btn-sm" title="Редактировать">
                                        <i class="fas fa-edit fa-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Удалить ресторан?')" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Удалить">
                                            <i class="fas fa-trash fa-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Нет ресторанов</p>
                                <a href="{{ route('admin.restaurants.create') }}" class="btn btn-primary">
                                    Добавить первый ресторан
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($restaurants->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $restaurants->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
</div>
@endsection

