@extends('layouts.app')

@section('title', 'Изменить пароль')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Заголовок -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-key text-primary"></i> Изменить пароль</h1>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Назад
                </a>
            </div>

            <!-- Форма изменения пароля -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')

                        <!-- Текущий пароль -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock"></i> Текущий пароль *
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required
                                   autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Новый пароль -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-key"></i> Новый пароль *
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required
                                   minlength="8"
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Минимум 8 символов
                            </small>
                        </div>

                        <!-- Подтверждение пароля -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check"></i> Подтверждение пароля *
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   minlength="8"
                                   autocomplete="new-password">
                            <small class="form-text text-muted">
                                Введите новый пароль еще раз
                            </small>
                        </div>

                        <hr>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Изменить пароль
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Информационная карточка -->
            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Внимание:</strong> После изменения пароля вам нужно будет войти в систему заново с новым паролем.
            </div>

            <!-- Советы по безопасности -->
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-shield-alt text-info"></i> Советы по безопасности:
                    </h6>
                    <ul class="mb-0 small">
                        <li>Используйте уникальный пароль для каждого сайта</li>
                        <li>Не используйте личную информацию в пароле</li>
                        <li>Регулярно меняйте пароль (раз в 3-6 месяцев)</li>
                        <li>Не сообщайте пароль другим людям</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;

    form.addEventListener('submit', function(e) {
        // Предотвращаем двойную отправку
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        // Проверяем, что пароли совпадают
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Пароли не совпадают!');
            return false;
        }

        // Проверяем минимальную длину
        if (password.length < 8) {
            e.preventDefault();
            alert('Пароль должен содержать минимум 8 символов!');
            return false;
        }

        // Устанавливаем флаг отправки
        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Сохранение...';
    });
});
</script>
@endpush
@endsection
