@extends('layouts.app')

@section('title', 'Управление блюдами')

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-utensils"></i> Управление блюдами</h1>
            <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus fa-sm"></i> Добавить блюдо
            </a>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.dishes.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="restaurant_id" class="form-label">Ресторан</label>
                <select name="restaurant_id" id="restaurant_id" class="form-select">
                    <option value="">Все рестораны</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                            {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="category" class="form-label">Категория</label>
                <select name="category" id="category" class="form-select">
                    <option value="">Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter fa-sm"></i> Фильтровать
                </button>
                <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times fa-sm"></i> Сбросить
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Блюдо</th>
                        <th>Ресторан</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dishes as $dish)
                        <tr>
                            <td>{{ $dish->id }}</td>
                            <td>
                                @if($dish->image)
                                    <img src="{{ asset('storage/' . $dish->image) }}" 
                                         alt="{{ $dish->name }}" 
                                         class="rounded me-2" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <strong>{{ $dish->name }}</strong>
                            </td>
                            <td>{{ $dish->restaurant->name }}</td>
                            <td><span class="badge bg-secondary">{{ $dish->category }}</span></td>
                            <td><strong>{{ number_format($dish->price, 2, ',', ' ') }} BYN</strong></td>
                            <td>
                                @if($dish->is_available)
                                    <span class="badge bg-success">Доступно</span>
                                @else
                                    <span class="badge bg-danger">Недоступно</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.dishes.show', $dish) }}" 
                                       class="btn btn-info btn-sm" title="Просмотр">
                                        <i class="fas fa-eye fa-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.dishes.edit', $dish) }}" 
                                       class="btn btn-warning btn-sm" title="Редактировать">
                                        <i class="fas fa-edit fa-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.dishes.destroy', $dish) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Удалить блюдо?')" 
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
                                <i class="fas fa-utensils fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Нет блюд</p>
                                <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">
                                    Добавить первое блюдо
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dishes->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $dishes->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
</div>
@endsection

