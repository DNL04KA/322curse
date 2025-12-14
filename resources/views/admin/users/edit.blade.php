@extends('layouts.app')

@section('title', 'Редактирование пользователя: ' . $user->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Управление пользователями</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $user) }}">Пользователь #{{ $user->id }}</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-user-edit text-warning"></i> Редактирование пользователя: {{ $user->name }}</h1>
            <div>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary me-2">
                    <i class="fas fa-eye"></i> Просмотр
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Обнаружены ошибки валидации:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-edit"></i> Редактирование данных пользователя
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Имя пользователя <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Отображаемое имя пользователя в системе</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Адрес электронной почты (необязательно)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Текущий телефон</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" value="{{ $user->phone }}" readonly>
                                </div>
                                <div class="form-text">Номер телефона изменить нельзя</div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Текущая роль</label>
                                <div>
                                    @if($user->is_admin)
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-crown"></i> Администратор
                                        </span>
                                    @else
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-user"></i> Пользователь
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Информация о пользователе</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">ID пользователя: #{{ $user->id }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            Дата регистрации: {{ $user->created_at->format('d.m.Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted">
                                            Последнее обновление: {{ $user->updated_at->format('d.m.Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Поля отмеченные <span class="text-danger">*</span> обязательны для заполнения
                                    </small>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Сохранить изменения
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Быстрая статистика -->
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Быстрая статистика
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 text-primary mb-1">{{ $user->orders()->count() }}</div>
                        <small class="text-muted">Заказов</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 text-success mb-1">{{ number_format($user->orders()->sum('total_amount'), 0, ',', ' ') }}</div>
                        <small class="text-muted">BYN</small>
                    </div>
                </div>
            </div>
        </div>

        
        </div>
    </div>
</div>

@endsection