<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => $request->boolean('is_admin'),
            'phone_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно создан');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'is_admin' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_admin' => $request->boolean('is_admin'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно обновлен');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Нельзя удалить самого себя');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удален');
    }

    /**
     * Toggle admin status for a user.
     */
    public function toggleAdmin(Request $request, User $user)
    {
        // Prevent removing admin status from self
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Нельзя изменить статус администратора у самого себя');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $status = $user->is_admin ? 'назначен администратором' : 'снят с роли администратора';

        // Если это AJAX запрос, возвращаем JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Пользователь {$status}",
                'is_admin' => $user->is_admin
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Пользователь {$status}");
    }
}
