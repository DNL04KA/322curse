@extends('layouts.app')

@section('title', 'Тест CSRF токена')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Тест CSRF токена</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('test-csrf') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Сообщение</label>
                            <input type="text" class="form-control" id="message" name="message" value="Тестовое сообщение">
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>

                    <hr>

                    <div class="alert alert-info">
                        <strong>Информация о сессии:</strong><br>
                        CSRF токен: <code>{{ csrf_token() }}</code><br>
                        Время жизни сессии: 120 минут<br>
                        Текущий URL: {{ url()->current() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
