<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\TelegramService;
use App\Helpers\PhoneHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $dishId => $item) {
            $dish = Dish::find($dishId);
            if ($dish) {
                $subtotal = $dish->price * $item['quantity'];
                $total += $subtotal;
                $cartItems[] = [
                    'dish' => $dish,
                    'quantity' => $item['quantity'],
                    'special_instructions' => $item['special_instructions'] ?? '',
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('orders.create', compact('cartItems', 'total'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Обработка времени доставки
        $deliveryTime = null;
        if ($request->filled(['delivery_date', 'delivery_time_select'])) {
            $deliveryTime = $request->delivery_date.' '.$request->delivery_time_select.':00';
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'country_code' => 'required|string|regex:/^\+[1-9]\d{0,3}(\s?\d{0,3})?$/',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'street' => 'required|string|max:255',
            'entrance' => 'nullable|string|max:10',
            'floor' => 'nullable|string|max:10',
            'apartment' => 'nullable|string|max:10',
            'additional_address' => 'nullable|string|max:500',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'delivery_time_select' => ['nullable', 'string', 'regex:/^([01]?\d|2[0-3]):[0-5]\d$/'],
            'notes' => 'nullable|string|max:1000',
        ]);

        // Дополнительная валидация времени доставки
        if ($deliveryTime) {
            $deliveryDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $deliveryTime);
            $now = now();

            // Если заказ на сегодня, проверяем что время не раньше чем через 1 час
            if ($deliveryDateTime->isToday()) {
                if ($deliveryDateTime->isBefore($now->addHour())) {
                    return back()->withErrors(['delivery_time' => 'Время доставки на сегодня должно быть не раньше чем через 1 час от текущего времени.'])->withInput();
                }
            } else {
                // Для будущих дат проверяем что время не в прошлом
                if ($deliveryDateTime->isBefore($now)) {
                    return back()->withErrors(['delivery_time' => 'Нельзя выбрать время в прошлом.'])->withInput();
                }
            }

            // Проверяем рабочее время (9:00 - 23:00)
            $hour = $deliveryDateTime->hour;
            if ($hour < 9 || $hour >= 23) {
                return back()->withErrors(['delivery_time' => 'Доставка осуществляется только с 9:00 до 23:00.'])->withInput();
            }
        }

        $userId = auth()->id();

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста!');
        }

        // Рассчитываем общую сумму и проверяем доступность блюд
        $total = 0;
        $unavailableDishes = [];

        foreach ($cart as $dishId => $item) {
            $dish = Dish::with('restaurant')->find($dishId);

            if (! $dish) {
                // Удаляем несуществующее блюдо из корзины
                unset($cart[$dishId]);

                continue;
            }

            // Проверяем доступность блюда и ресторана
            if (! $dish->is_available || ! $dish->restaurant->is_active) {
                $unavailableDishes[] = $dish->name;
                unset($cart[$dishId]);

                continue;
            }

            $total += $dish->price * $item['quantity'];
        }

        // Обновляем корзину после проверок
        session()->put('cart', $cart);

        // Если есть недоступные блюда, уведомляем пользователя
        if (! empty($unavailableDishes)) {
            return back()->withErrors([
                'dishes' => 'Следующие блюда больше не доступны и были удалены из корзины: '.implode(', ', $unavailableDishes),
            ])->withInput();
        }

        // Если корзина пуста после проверок
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста или все блюда недоступны!');
        }

        // Форматируем номер телефона правильно
        $formattedPhone = PhoneHelper::format($request->country_code, $request->phone);

        // Собираем полный адрес доставки
        $deliveryAddress = $request->city.', '.$request->street;
        if ($request->entrance) {
            $deliveryAddress .= ', подъезд '.$request->entrance;
        }
        if ($request->floor) {
            $deliveryAddress .= ', этаж '.$request->floor;
        }
        if ($request->apartment) {
            $deliveryAddress .= ', кв. '.$request->apartment;
        }
        if ($request->additional_address) {
            $deliveryAddress .= ', '.$request->additional_address;
        }

        $order = DB::transaction(function () use ($request, $cart, $total, $deliveryAddress, $userId, $formattedPhone, $deliveryTime) {
            // Создаем заказ
            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email ?: null,
                'customer_phone' => $formattedPhone,
                'delivery_address' => $deliveryAddress,
                'total_amount' => $total,
                'delivery_time' => $deliveryTime,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Создаем элементы заказа
            foreach ($cart as $dishId => $item) {
                $dish = Dish::find($dishId);
                if ($dish) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'dish_id' => $dish->id,
                        'quantity' => $item['quantity'],
                        'price' => $dish->price,
                        'special_instructions' => $item['special_instructions'] ?? '',
                    ]);
                }
            }

            return $order; // Возвращаем объект заказа
        });

        // Отправляем уведомления
        $telegramService = app(TelegramService::class);

        // Уведомление админу
        $telegramService->sendNewOrderNotification([
            'id' => $order->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $formattedPhone,
            'total' => number_format($total, 2, ',', ' '),
            'address' => $deliveryAddress,
        ]);

        // Уведомления пользователям отключены - бот теперь только для администрации

        // Очищаем корзину
        session()->forget('cart');

        return redirect()->route('orders.success')->with('success', 'Заказ успешно оформлен!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('orderItems.dish')->findOrFail($id);

        // Проверка прав доступа: только владелец заказа или админ
        if ($order->user_id !== auth()->id() && ! auth()->user()->is_admin) {
            abort(403, 'У вас нет доступа к этому заказу');
        }

        return view('orders.show', compact('order'));
    }

    public function success()
    {
        return view('orders.success');
    }

    /**
     * Display a listing of orders for admin.
     */
    public function adminIndex()
    {
        // Eager loading для избежания N+1
        $orders = Order::with(['user', 'orderItems.dish.restaurant'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,delivering,delivered,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Отправляем уведомление администратору
        $this->sendAdminStatusNotification($order, $oldStatus);

        return response()->json(['success' => true, 'status' => $order->status]);
    }

    /**
     * Отправить уведомление администратору об изменении статуса заказа
     */
    protected function sendAdminStatusNotification(Order $order, string $oldStatus)
    {
        $statusText = $this->getStatusText($order->status);

        $orderData = [
            'id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'status' => $order->status,
            'status_text' => $statusText,
        ];

        $telegramService = app(TelegramService::class);
        $telegramService->sendOrderStatusUpdate($orderData);
    }

    /**
     * Получить текстовое описание статуса
     */
    protected function getStatusText(string $status): string
    {
        return match ($status) {
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтвержден',
            'preparing' => 'Готовится',
            'delivering' => 'Доставляется',
            'delivered' => 'Доставлен',
            'cancelled' => 'Отменен',
            default => 'Неизвестен'
        };
    }

    /**
     * Получить emoji для статуса
     */
    protected function getStatusEmoji(string $status): string
    {
        return match ($status) {
            'pending' => '⏳',
            'confirmed' => '✅',
            'preparing' => '👨‍🍳',
            'delivering' => '🚚',
            'delivered' => '🎉',
            'cancelled' => '❌',
            default => '❓'
        };
    }

    /**
     * Get status label in Russian.
     */
    private function getStatusLabel($status)
    {
        return match ($status) {
            'pending' => 'Ожидает подтверждения',
            'confirmed' => 'Подтвержден',
            'preparing' => 'Готовится',
            'delivering' => 'Доставляется',
            'delivered' => 'Доставлен',
            'cancelled' => 'Отменен',
            default => 'Неизвестный статус'
        };
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
