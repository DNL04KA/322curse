<?php

namespace App\Http\Controllers;

use App\Models\Order;

class UserOrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with('orderItems.dish.restaurant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== auth()->id()) {
            abort(403, 'У вас нет доступа к этому заказу.');
        }

        $order->load('orderItems.dish.restaurant');

        return view('user.orders.show', compact('order'));
    }
}
