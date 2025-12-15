<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
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

// Заказы (доступны без авторизации) с rate limiting
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:10,10') // 10 заказов в 10 минут
    ->name('orders.store');
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');

// Заказы пользователя (требуют авторизации)
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Аутентификация с rate limiting
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
    ->middleware('throttle:10,1'); // 10 попыток в минуту
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware('throttle:3,10'); // 3 регистрации в 10 минут

// Тестовые маршруты удалены для production безопасности

// Заказы пользователей
Route::middleware('auth')->group(function () {
    Route::get('/user/orders', [App\Http\Controllers\UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/user/orders/{order}', [App\Http\Controllers\UserOrderController::class, 'show'])->name('user.orders.show');

    // Профиль пользователя
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [App\Http\Controllers\ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Админка (только для администраторов)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Управление заказами
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Управление пользователями
    Route::resource('users', \App\Http\Controllers\AdminUserController::class);
    Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');

    // Управление ресторанами
    Route::resource('restaurants', \App\Http\Controllers\Admin\RestaurantController::class);

    // Управление блюдами
    Route::resource('dishes', \App\Http\Controllers\Admin\DishController::class);
});

// Маршруты для создания тестовых пользователей удалены для безопасности

// Telegram webhook и тестирование удалены - регистрация больше не требует Telegram

// Маршрут статуса сервера (только базовая информация)
Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'server' => 'Laravel FoodOrder',
        'time' => now()->toISOString(),
    ]);
})->name('status');

// Маршруты отладки удалены для безопасности production

// Все тестовые маршруты удалены для безопасности

// Тестовые маршруты удалены
