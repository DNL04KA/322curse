@extends('layouts.app')

@section('title', $dish->name)

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Блюда</a></li>
                <li class="breadcrumb-item active">{{ $dish->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-utensils"></i> {{ $dish->name }}</h5>
                <div>
                    <a href="{{ route('admin.dishes.edit', $dish) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($dish->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $dish->image) }}" 
                             alt="{{ $dish->name }}" 
                             class="img-fluid rounded"
                             style="max-height: 300px;">
                    </div>
                @endif

                <h6>Описание:</h6>
                <p>{{ $dish->description ?? 'Нет описания' }}</p>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Ресторан:</strong><br>{{ $dish->restaurant->name }}</p>
                        <p><strong>Категория:</strong><br><span class="badge bg-secondary">{{ $dish->category }}</span></p>
                        <p><strong>Цена:</strong><br><span class="h4 text-primary">{{ number_format($dish->price, 2, ',', ' ') }} BYN</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Статус:</strong><br>
                            @if($dish->is_available)
                                <span class="badge bg-success">Доступно</span>
                            @else
                                <span class="badge bg-danger">Недоступно</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

