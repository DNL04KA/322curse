@extends('layouts.app')

@section('title', 'Смена пароля')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-key"></i> Смена пароля
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password.update') }}" method="POST" id="passwordForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-lock"></i> Текущий пароль <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required
                               placeholder="Введите текущий пароль">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-key"></i> Новый пароль <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               minlength="8"
                               placeholder="Минимум 8 символов">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Пароль должен содержать минимум 8 символов, включая заглавные и строчные буквы, цифры и специальные символы
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-circle"></i> Подтверждение пароля <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               minlength="8"
                               placeholder="Повторите новый пароль">
                    </div>

                    <!-- Индикатор силы пароля -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Надежность пароля:</small>
                            <small id="strength-text" class="text-muted">-</small>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div id="password-strength" 
                                 class="progress-bar" 
                                 role="progressbar" 
                                 style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Отмена
                        </a>
                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fas fa-save"></i> Изменить пароль
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-shield-alt text-primary"></i> Требования к паролю:
                </h6>
                <ul class="mb-0">
                    <li>Минимум 8 символов</li>
                    <li>Заглавные буквы (A-Z)</li>
                    <li>Строчные буквы (a-z)</li>
                    <li>Цифры (0-9)</li>
                    <li>Специальные символы (!@#$%^&*)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // Обновляем прогресс-бар
            strengthBar.style.width = strength.percent + '%';
            strengthBar.className = 'progress-bar ' + strength.class;
            strengthText.textContent = strength.text;
            strengthText.className = strength.textClass;
        });
    }

    function calculatePasswordStrength(password) {
        let score = 0;
        
        if (password.length >= 8) score += 20;
        if (password.length >= 12) score += 20;
        if (/[a-z]/.test(password)) score += 15;
        if (/[A-Z]/.test(password)) score += 15;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^a-zA-Z0-9]/.test(password)) score += 15;
        
        if (score < 40) {
            return { 
                percent: score, 
                text: 'Слабый', 
                class: 'bg-danger',
                textClass: 'text-danger'
            };
        } else if (score < 70) {
            return { 
                percent: score, 
                text: 'Средний', 
                class: 'bg-warning',
                textClass: 'text-warning'
            };
        } else {
            return { 
                percent: score, 
                text: 'Надежный', 
                class: 'bg-success',
                textClass: 'text-success'
            };
        }
    }

    // Проверка совпадения паролей
    const confirmInput = document.getElementById('password_confirmation');
    const form = document.getElementById('passwordForm');

    if (form) {
        form.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmInput.value) {
                e.preventDefault();
                alert('Пароли не совпадают!');
                confirmInput.focus();
            }
        });
    }
});
</script>
@endpush

