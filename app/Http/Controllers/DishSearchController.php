<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class DishSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return view('search.results', [
                'query' => $query,
                'restaurants' => collect(),
                'totalResults' => 0,
            ]);
        }

        // Поиск блюд по названию или описанию
        $dishes = Dish::with('restaurant')
            ->where('is_available', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%'.$query.'%')
                    ->orWhere('description', 'LIKE', '%'.$query.'%')
                    ->orWhere('category', 'LIKE', '%'.$query.'%');
            })
            ->get();

        // Группировка по ресторанам
        $restaurants = $dishes->groupBy('restaurant_id')->map(function ($dishes, $restaurantId) {
            $restaurant = $dishes->first()->restaurant;

            return [
                'restaurant' => $restaurant,
                'dishes' => $dishes,
                'dish_count' => $dishes->count(),
            ];
        });

        return view('search.results', [
            'query' => $query,
            'restaurants' => $restaurants,
            'totalResults' => $dishes->count(),
        ]);
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Поиск популярных блюд для автодополнения
        $dishes = Dish::where('is_available', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', $query.'%')
                    ->orWhere('category', 'LIKE', $query.'%');
            })
            ->limit(10)
            ->get(['name', 'category'])
            ->unique('name')
            ->map(function ($dish) {
                return [
                    'name' => $dish->name,
                    'category' => $dish->category,
                    'label' => $dish->name.' ('.$dish->category.')',
                ];
            });

        return response()->json($dishes);
    }
}
