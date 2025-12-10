<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|regex:/^\+\d{1,4}$/',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Проверяем уникальность полного номера телефона
        $fullPhone = $request->country_code . $request->phone;
        $existingUser = User::where('phone', $fullPhone)->first();
        if ($existingUser) {
            return back()->withErrors(['phone' => 'Пользователь с таким номером телефона уже зарегистрирован.'])->withInput();
        }

        // Создаем пользователя напрямую без верификации
        $user = User::create([
            'name' => $request->name,
            'phone' => $fullPhone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_verified_at' => now(), // Автоматически верифицируем
        ]);

        // Автоматически авторизуем пользователя
        auth()->login($user);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно! Добро пожаловать в FoodOrder.');
    }
}
