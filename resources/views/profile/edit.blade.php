@extends('layouts.app')

@section('title', 'Редактировать профиль')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Заголовок -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-edit text-primary"></i> Редактировать профиль</h1>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Назад
                </a>
            </div>

            <!-- Форма редактирования -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Имя и фамилия -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Имя и фамилия *
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Телефон -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Телефон *
                            </label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Формат: +375291234567
                            </small>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email (опционально)
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Используется для восстановления пароля и уведомлений
                            </small>
                        </div>

                        <hr>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Информационная карточка -->
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle"></i> 
                <strong>Важно:</strong> Телефон используется для входа в систему. Убедитесь, что он указан правильно.
            </div>
        </div>
    </div>
</div>
@endsection
