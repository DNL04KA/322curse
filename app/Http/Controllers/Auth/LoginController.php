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
        $phoneValue = $request->country_code.$request->phone; // Без пробела
        $phoneWithSpace = $request->country_code.' '.$request->phone; // С пробелом

        // Ищем пользователя по разным вариантам форматирования
        $user = \App\Models\User::where('phone', $phoneValue)->first(); // +37591234567
        if (! $user) {
            $user = \App\Models\User::where('phone', $phoneWithSpace)->first(); // +375 91234567
        }
        if (! $user) {
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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать!');
        }

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
