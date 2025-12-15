<?php

namespace App\Http\Controllers;

use App\Helpers\PhoneHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Показать профиль пользователя
     */
    public function show()
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }

    /**
     * Показать форму редактирования профиля
     */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Обновить профиль пользователя
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:50|unique:users,phone,'.$user->id,
        ]);

        // Телефон уже приходит отформатированным от пользователя
        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Показать форму изменения пароля
     */
    public function editPassword()
    {
        return view('profile.edit-password');
    }

    /**
     * Обновить пароль
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Проверка текущего пароля
        if (! Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Неверный текущий пароль.'])->withInput();
        }

        // Обновление пароля
        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Пароль успешно изменен!');
    }
}
