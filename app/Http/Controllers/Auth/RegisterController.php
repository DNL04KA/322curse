<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\PhoneHelper;
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
            'country_code' => 'required|string|regex:/^\+[1-9]\d{0,3}(\s?\d{0,3})?$/',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Минимум 1 прописная, 1 заглавная, 1 цифра
            ],
        ], [
            'password.regex' => 'Пароль должен содержать минимум одну прописную букву, одну заглавную букву и одну цифру.',
        ]);

        // Форматируем телефон правильно
        $formattedPhone = PhoneHelper::format($request->country_code, $request->phone);

        // Проверяем уникальность телефона
        $existingUser = User::where('phone', $formattedPhone)->first();
        if ($existingUser) {
            return back()->withErrors(['phone' => 'Пользователь с таким номером телефона уже зарегистрирован.'])->withInput();
        }

        // Создаем пользователя
        $user = User::create([
            'name' => $request->name,
            'phone' => $formattedPhone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_verified_at' => now(),
        ]);

        // Автоматически авторизуем пользователя
        auth()->login($user);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно! Добро пожаловать в FoodOrder.');
    }
}
