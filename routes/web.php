<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [RestaurantController::class, 'index'])->name('home');
Route::resource('restaurants', RestaurantController::class);
Route::get('/dishes/{dish}', [DishController::class, 'show'])->name('dishes.show');

// Поиск блюд
Route::get('/search', [App\Http\Controllers\DishSearchController::class, 'search'])->name('search');
Route::get('/search/suggestions', [App\Http\Controllers\DishSearchController::class, 'suggestions'])->name('search.suggestions');

// Корзина
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{dish}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{dishId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{dishId}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Отслеживание заказов (должно быть выше ресурсных маршрутов)
Route::get('/orders/track', [App\Http\Controllers\OrderTrackingController::class, 'showForm'])->name('orders.track');
Route::post('/orders/track', [App\Http\Controllers\OrderTrackingController::class, 'track'])->name('orders.track.post');

// Заказы (доступны без авторизации)
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');

// Заказы пользователя (требуют авторизации)
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Аутентификация
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Маршруты верификации телефона удалены - регистрация теперь прямая

// Тест CSRF токена
Route::get('/test-csrf', function () {
    return view('test-csrf');
});

Route::post('/test-csrf', function () {
    return 'CSRF токен работает! Форма отправлена успешно.';
});

// Проверка сессии
Route::get('/check-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'phone_verification_code' => session('phone_verification_code'),
        'phone_verification_data' => session('phone_verification_data'),
        'csrf_token' => csrf_token(),
        'all_session' => session()->all(),
    ]);
});

// Быстрая регистрация для тестирования
Route::get('/quick-register', function () {
    // Устанавливаем тестовые данные
    session([
        'phone_verification_code' => '123456',
        'phone_verification_data' => [
            'name' => 'Тестовый Пользователь',
            'phone' => '+375 (29) 123-45-67',
            'email' => 'test@example.com',
            'password' => 'password123',
        ],
    ]);

    return redirect('/phone/verify')->with('info', 'Тестовые данные установлены. Код: 123456');
});

// Заказы пользователей
Route::middleware('auth')->group(function () {
    Route::get('/user/orders', [App\Http\Controllers\UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/user/orders/{order}', [App\Http\Controllers\UserOrderController::class, 'show'])->name('user.orders.show');
});

// Админка (только для администраторов)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Управление заказами
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Управление пользователями
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);
    Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
});

// Создание тестового пользователя для тестирования
Route::get('/create-test-user', function () {
    $user = \App\Models\User::firstOrCreate([
        'phone' => '+375 (29) 123-45-67',
    ], [
        'name' => 'Тестовый Пользователь',
        'email' => 'test@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'phone_verified_at' => now(),
        'is_admin' => true,
    ]);
    // Обновляем права админа в любом случае
    $user->update(['is_admin' => true]);
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect('/')->with('success', 'Тестовый пользователь создан и авторизован как админ');
})->name('create-test-user');

// Telegram webhook и тестирование удалены - регистрация больше не требует Telegram

// Проверка статуса сервера
Route::get('/status', function () {
    $admin = \App\Models\User::where('is_admin', true)->first();
    $allUsers = \App\Models\User::all();

    return response()->json([
        'status' => 'ok',
        'server' => 'Laravel FoodOrder',
        'time' => now()->toISOString(),
        'users_count' => $allUsers->count(),
        'admin_exists' => $admin ? true : false,
        'admin_phone' => $admin ? $admin->phone : null,
        'admin_email' => $admin ? $admin->email : null,
        'all_users' => $allUsers->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'phone' => $u->phone,
                'is_admin' => $u->is_admin
            ];
        }),
        'login_url' => url('/login'),
        'telegram_test_url' => url('/telegram/test'),
        'webhook_url' => url('/telegram/webhook'),
    ]);
})->name('status');

// Просмотр последних логов входа
Route::get('/debug-logs', function () {
    $logFile = storage_path('logs/laravel.log');
    $logs = [];

    if (file_exists($logFile)) {
        $lines = array_slice(file($logFile), -50); // Последние 50 строк
        $loginLogs = array_filter($lines, function($line) {
            return strpos($line, 'Login attempt') !== false ||
                   strpos($line, 'Auth::attempt') !== false ||
                   strpos($line, 'Password check') !== false ||
                   strpos($line, 'User found') !== false ||
                   strpos($line, 'User not found') !== false;
        });
        $logs = array_map('trim', array_slice($loginLogs, -10)); // Последние 10 записей о входе
    }

    return view('debug.logs', compact('logs'));
})->name('debug.logs');

// Очистка логов
Route::post('/debug-clear-logs', function () {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        file_put_contents($logFile, '');
    }
    return back()->with('success', 'Логи очищены!');
})->name('debug.clear-logs');

// Тест форматирования номера
Route::get('/test-phone-formatting', function () {
    return view('test.phone-formatting');
})->name('test.phone-formatting');

// Тест форматирования номера
Route::get('/test-phone-format/{number?}', function ($number = '293709505') {
    // Симулируем JavaScript логику форматирования
    $digits = preg_replace('/\D/', '', $number);
    $formatted = '';

    if (strlen($digits) >= 1) $formatted = $digits[0];
    if (strlen($digits) >= 2) $formatted = substr($digits, 0, 2);
    if (strlen($digits) >= 3) $formatted = '(' . substr($digits, 0, 2) . ') ' . $digits[2];
    if (strlen($digits) >= 4) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 2);
    if (strlen($digits) >= 5) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 3);
    if (strlen($digits) >= 6) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 3) . '-' . $digits[5];
    if (strlen($digits) >= 7) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 3) . '-' . substr($digits, 5, 2);
    if (strlen($digits) >= 8) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 3) . '-' . substr($digits, 5, 2) . '-' . $digits[7];
    if (strlen($digits) >= 9) $formatted = '(' . substr($digits, 0, 2) . ') ' . substr($digits, 2, 3) . '-' . substr($digits, 5, 2) . '-' . substr($digits, 7, 2);

    return response()->json([
        'input' => $number,
        'digits_only' => $digits,
        'formatted' => $formatted,
        'expected' => '(29) 370-95-05',
        'correct' => $formatted === '(29) 370-95-05'
    ]);
})->name('test.phone-format');

// Telegram тестирование удалено - регистрация больше не требует Telegram

// Создать тестовый код верификации
Route::get('/test-create-verification-code', function () {
    Cache::put('phone_verification_+375291234567', '123456', now()->addMinutes(10));
    return response()->json([
        'success' => true,
        'message' => 'Код верификации создан',
        'phone' => '+375291234567',
        'code' => '123456',
        'expires' => now()->addMinutes(10)->toISOString(),
    ]);
})->name('test.create-verification-code');

// Создать тестового админа
Route::get('/create-test-admin', function () {
    $user = \App\Models\User::where('phone', '+375291234567')->first();

    if (!$user) {
        $user = \App\Models\User::create([
            'name' => 'Test Admin',
            'phone' => '+375291234567',
            'email' => 'admin@foodorder.test',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'is_admin' => true,
            'phone_verified_at' => now(),
        ]);
        $message = 'Тестовый админ создан!';
    } else {
        $user->update(['is_admin' => true]);
        $message = 'Пользователь назначен админом!';
    }

    return response()->json([
        'success' => true,
        'message' => $message,
        'admin' => [
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
        ],
        'login_url' => route('login'),
        'password' => 'admin123'
    ]);
})->name('create.test-admin');




// Проверка статуса пользователя
Route::get('/user-status', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->check() ? [
            'id' => auth()->id(),
            'name' => auth()->user()->name,
            'phone' => auth()->user()->phone,
            'is_admin' => auth()->user()->is_admin,
        ] : null,
        'session_id' => session()->getId(),
    ]);
})->name('user-status');

Route::get('/add-test-item', function () {
    $dish = \App\Models\Dish::first();
    if (! $dish) {
        return 'Нет блюд в базе данных';
    }
    session()->put('cart', [
        $dish->id => [
            'quantity' => 1,
            'special_instructions' => '',
        ],
    ]);

    return redirect('/cart')->with('success', 'Товар добавлен в корзину');
})->name('add-test-item');
