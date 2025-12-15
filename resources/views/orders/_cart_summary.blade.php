<div class="card shadow-sm sticky-top" style="top: 20px;">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart"></i> Ваш заказ
        </h5>
    </div>
    <div class="card-body">
        @if(count($cartItems) > 0)
            <div class="list-group list-group-flush mb-3">
                @foreach($cartItems as $item)
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item['dish']->name }}</h6>
                                <small class="text-muted">{{ $item['quantity'] }} x {{ number_format($item['dish']->price, 2, ',', ' ') }} BYN</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ number_format($item['subtotal'], 2, ',', ' ') }} BYN
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Товары ({{ array_sum(array_column($cartItems, 'quantity')) }} шт.)</span>
                <span>{{ number_format($total, 2, ',', ' ') }} BYN</span>
            </div>

            <hr class="my-3">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Итого:</h5>
                <h5 class="mb-0 text-primary">{{ number_format($total, 2, ',', ' ') }} BYN</h5>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="fas fa-exclamation-triangle"></i> Корзина пуста
            </div>
        @endif
    </div>
</div>

