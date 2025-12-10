<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];

        foreach ($cart as $dishId => $item) {
            $dish = Dish::find($dishId);
            if ($dish) {
                $cartItems[] = [
                    'dish' => $dish,
                    'quantity' => $item['quantity'],
                    'special_instructions' => $item['special_instructions'] ?? '',
                ];
            }
        }

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request, Dish $dish)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$dish->id])) {
            $cart[$dish->id]['quantity'] += $request->quantity;
            if ($request->special_instructions) {
                $cart[$dish->id]['special_instructions'] = $request->special_instructions;
            }
        } else {
            $cart[$dish->id] = [
                'quantity' => $request->quantity,
                'special_instructions' => $request->special_instructions ?? '',
            ];
        }

        session()->put('cart', $cart);

        // Если это AJAX запрос, возвращаем JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Блюдо добавлено в корзину!',
                'cart_count' => count($cart),
                'dish_name' => $dish->name
            ]);
        }

        return redirect()->back()->with('success', 'Блюдо добавлено в корзину!');
    }

    public function update(Request $request, $dishId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$dishId])) {
            $cart[$dishId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Количество обновлено!',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Количество обновлено!');
    }

    public function remove(Request $request, $dishId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$dishId])) {
            unset($cart[$dishId]);
            session()->put('cart', $cart);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Блюдо удалено из корзины!',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Блюдо удалено из корзины!');
    }

    public function clear(Request $request)
    {
        session()->forget('cart');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Корзина очищена!',
                'cart_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'Корзина очищена!');
    }
}
