<?php

namespace App\Http\Controllers;

use App\Models\Dish;

class DishController extends Controller
{
    public function show($id)
    {
        $dish = Dish::with('restaurant')->findOrFail($id);

        return view('dishes.show', compact('dish'));
    }
}
