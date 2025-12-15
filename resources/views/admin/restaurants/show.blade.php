@extends('layouts.app')

@section('title', $restaurant->name)

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Рестораны</a></li>
                <li class="breadcrumb-item active">{{ $restaurant->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-store"></i> {{ $restaurant->name }}</h5>
                <div>
                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($restaurant->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $restaurant->image) }}" 
                             alt="{{ $restaurant->name }}" 
                             class="img-fluid rounded"
                             style="max-height: 300px;">
                    </div>
                @endif

                <h6>Описание:</h6>
                <p>{{ $restaurant->description ?? 'Нет описания' }}</p>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Адрес:</strong><br>{{ $restaurant->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Телефон:</strong><br>{{ $restaurant->phone ?? '—' }}</p>
                    </div>
                </div>

                <p>
                    <strong>Статус:</strong>
                    @if($restaurant->is_active)
                        <span class="badge bg-success">Активен</span>
                    @else
                        <span class="badge bg-secondary">Неактивен</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-utensils"></i> Меню ({{ $restaurant->dishes->count() }} блюд)</h5>
                <a href="{{ route('admin.dishes.create', ['restaurant_id' => $restaurant->id]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Добавить блюдо
                </a>
            </div>
            <div class="card-body">
                @if($restaurant->dishes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Категория</th>
                                    <th>Цена</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($restaurant->dishes as $dish)
                                    <tr>
                                        <td>{{ $dish->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $dish->category }}</span></td>
                                        <td>{{ number_format($dish->price, 2, ',', ' ') }} BYN</td>
                                        <td>
                                            @if($dish->is_available)
                                                <span class="badge bg-success">Доступно</span>
                                            @else
                                                <span class="badge bg-danger">Недоступно</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">
                        Нет блюд в меню
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

