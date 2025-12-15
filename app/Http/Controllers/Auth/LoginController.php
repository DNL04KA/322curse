<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\PhoneHelper;
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

        // Форматируем телефон так же, как он хранится в БД
        $formattedPhone = PhoneHelper::format($request->country_code, $request->phone);

        // Пробуем найти пользователя с отформатированным номером
        $user = \App\Models\User::where('phone', $formattedPhone)->first();

        // Если не нашли, пробуем другие варианты (для обратной совместимости)
        if (!$user) {
            $phoneDigits = preg_replace('/\D/', '', $request->phone);
            $user = \App\Models\User::where('phone', 'LIKE', '%' . $phoneDigits)->first();
        }

        if ($user) {
            $credentials = [
                'phone' => $user->phone,
                'password' => $request->password,
            ];
        } else {
            return back()->withErrors([
                'phone' => 'Пользователь с таким номером телефона не найден.',
            ])->onlyInput('phone');
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
