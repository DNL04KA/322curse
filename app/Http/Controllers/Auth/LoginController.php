<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|regex:/^\+\d{1,4}$/',
            'phone' => 'required|string|max:20',
            'password' => 'required|string',
        ]);

        // Форматируем номер телефона для поиска
        $phoneValue = $request->country_code . $request->phone; // Без пробела
        $phoneWithSpace = $request->country_code . ' ' . $request->phone; // С пробелом

        // Логируем входные данные
        \Illuminate\Support\Facades\Log::info('Login attempt:', [
            'country_code' => $request->country_code,
            'phone_input' => $request->phone,
            'phone_normalized' => $phoneValue,
            'phone_with_space' => $phoneWithSpace,
            'password_length' => strlen($request->password)
        ]);

        // Ищем пользователя по разным вариантам форматирования
        $user = \App\Models\User::where('phone', $phoneValue)->first(); // +37591234567
        if (!$user) {
            $user = \App\Models\User::where('phone', $phoneWithSpace)->first(); // +375 91234567
        }
        if (!$user) {
            $user = \App\Models\User::where('phone', $request->phone)->first(); // 91234567
        }

        if ($user) {
            $credentials = [
                'phone' => $user->phone, // Используем телефон как он хранится в БД
                'password' => $request->password,
            ];
        } else {
            $credentials = [
                'phone' => $phoneValue,
                'password' => $request->password,
            ];
        }

        if ($user) {
            \Illuminate\Support\Facades\Log::info('User found:', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_phone' => $user->phone,
                'is_admin' => $user->is_admin,
                'password_hash_start' => substr($user->password, 0, 10)
            ]);

            // Проверяем пароль вручную
            $passwordCheck = \Illuminate\Support\Facades\Hash::check($request->password, $user->password);
            \Illuminate\Support\Facades\Log::info('Password check result: ' . ($passwordCheck ? 'SUCCESS' : 'FAILED'));
        } else {
            \Illuminate\Support\Facades\Log::info('User not found for phone: ' . $phoneValue . ' or ' . $request->phone);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            \Illuminate\Support\Facades\Log::info('Auth::attempt SUCCESS - user logged in');

            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать!');
        }

        \Illuminate\Support\Facades\Log::info('Auth::attempt FAILED');
        return back()->withErrors([
            'phone' => 'Неверный номер телефона или пароль.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Вы успешно вышли из системы.');
    }
}
