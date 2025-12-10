@extends('layouts.app')

@section('title', 'Отладка логов входа')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fas fa-bug"></i> Отладка логов входа</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Последние логи попыток входа:</strong><br>
                        Здесь показаны последние 10 записей о входе в систему.
                    </div>

                    @if(count($logs) > 0)
                        <div class="bg-dark text-light p-3 rounded" style="font-family: monospace; font-size: 0.9em;">
                            @foreach($logs as $log)
                                <div class="mb-1">{{ $log }}</div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Логи входа не найдены. Попробуйте войти в систему, чтобы появились записи.
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Проверить статус системы</h5>
                            <a href="{{ route('status') }}" target="_blank" class="btn btn-info mb-2">
                                <i class="fas fa-server"></i> Статус API
                            </a><br>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Попробовать вход
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h5>Очистить логи</h5>
                            <form method="POST" action="{{ route('debug.clear-logs') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger mb-2">
                                    <i class="fas fa-trash"></i> Очистить логи
                                </button>
                            </form>
                            <small class="text-muted">Очистит файл storage/logs/laravel.log</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

