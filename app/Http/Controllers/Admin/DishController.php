<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    /**
     * Список всех блюд
     */
    public function index(Request $request)
    {
        $query = Dish::with('restaurant');

        // Фильтр по ресторану
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $dishes = $query->orderBy('created_at', 'desc')->paginate(20);
        $restaurants = Restaurant::orderBy('name')->get();
        
        // Получаем уникальные категории
        $categories = Dish::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category');

        return view('admin.dishes.index', compact('dishes', 'restaurants', 'categories'));
    }

    /**
     * Форма создания блюда
     */
    public function create()
    {
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.dishes.create', compact('restaurants'));
    }

    /**
     * Сохранение нового блюда
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
        ]);

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('dishes', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);

        Dish::create($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Блюдо успешно создано!');
    }

    /**
     * Просмотр блюда
     */
    public function show(Dish $dish)
    {
        $dish->load('restaurant');
        
        return view('admin.dishes.show', compact('dish'));
    }

    /**
     * Форма редактирования блюда
     */
    public function edit(Dish $dish)
    {
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.dishes.edit', compact('dish', 'restaurants'));
    }

    /**
     * Обновление блюда
     */
    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fats' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'is_available' => 'boolean',
        ]);

        // Загрузка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($dish->image) {
                Storage::disk('public')->delete($dish->image);
            }
            $validated['image'] = $request->file('image')->store('dishes', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', $dish->is_available);

        $dish->update($validated);

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Блюдо успешно обновлено!');
    }

    /**
     * Удаление блюда
     */
    public function destroy(Dish $dish)
    {
        // Удаляем изображение
        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }

        $dish->delete();

        return redirect()->route('admin.dishes.index')
            ->with('success', 'Блюдо успешно удалено!');
    }
}
