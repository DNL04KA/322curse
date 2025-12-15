<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Список всех ресторанов
     */
    public function index()
    {
        $restaurants = Restaurant::withCount('dishes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Форма создания ресторана
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }

    /**
     * Сохранение нового ресторана
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Restaurant::create($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Ресторан успешно создан!');
    }

    /**
     * Просмотр ресторана
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load('dishes');
        
        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Форма редактирования ресторана
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Обновление ресторана
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        // Загрузка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            $validated['image'] = $request->file('image')->store('restaurants', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', $restaurant->is_active);

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Ресторан успешно обновлен!');
    }

    /**
     * Удаление ресторана
     */
    public function destroy(Restaurant $restaurant)
    {
        // Удаляем изображение
        if ($restaurant->image) {
            Storage::disk('public')->delete($restaurant->image);
        }

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'Ресторан успешно удален!');
    }
}
