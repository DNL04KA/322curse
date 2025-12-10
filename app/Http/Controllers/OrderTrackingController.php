<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function showForm()
    {
        return view('orders.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|regex:/^\+\d{1,4}$/',
            'phone' => 'required|string|max:20',
        ]);

        $phone = $request->country_code.' '.$request->phone;

        // Ищем заказы по номеру телефона
        $orders = Order::where('customer_phone', $phone)
            ->with('orderItems.dish.restaurant')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return back()->withErrors(['phone' => 'Заказы не найдены. Проверьте правильность введенных данных.'])->withInput();
        }

        return view('orders.track-result', compact('orders', 'phone'));
    }
}
