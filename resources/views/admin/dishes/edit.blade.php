@extends('layouts.app')

@section('title', 'Редактировать блюдо')

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Блюда</a></li>
                <li class="breadcrumb-item active">Редактировать</li>
            </ol>
        </nav>
        <h1><i class="fas fa-edit"></i> Редактировать: {{ $dish->name }}</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.dishes.update', $dish) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="restaurant_id" class="form-label">Ресторан <span class="text-danger">*</span></label>
                        <select class="form-select @error('restaurant_id') is-invalid @enderror" 
                                id="restaurant_id" name="restaurant_id" required>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ old('restaurant_id', $dish->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('restaurant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Название блюда <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $dish->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $dish->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Категория <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Выберите категорию</option>
                                <option value="Супы" {{ old('category', $dish->category) == 'Супы' ? 'selected' : '' }}>Супы</option>
                                <option value="Салаты" {{ old('category', $dish->category) == 'Салаты' ? 'selected' : '' }}>Салаты</option>
                                <option value="Горячие блюда" {{ old('category', $dish->category) == 'Горячие блюда' ? 'selected' : '' }}>Горячие блюда</option>
                                <option value="Гарниры" {{ old('category', $dish->category) == 'Гарниры' ? 'selected' : '' }}>Гарниры</option>
                                <option value="Закуски" {{ old('category', $dish->category) == 'Закуски' ? 'selected' : '' }}>Закуски</option>
                                <option value="Напитки" {{ old('category', $dish->category) == 'Напитки' ? 'selected' : '' }}>Напитки</option>
                                <option value="Десерты" {{ old('category', $dish->category) == 'Десерты' ? 'selected' : '' }}>Десерты</option>
                                <option value="Выпечка" {{ old('category', $dish->category) == 'Выпечка' ? 'selected' : '' }}>Выпечка</option>
                                <option value="Другое" {{ old('category', $dish->category) == 'Другое' ? 'selected' : '' }}>Другое</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Цена (BYN) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $dish->price) }}" 
                                   step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение</label>
                        @if($dish->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $dish->image) }}" 
                                     alt="{{ $dish->name }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Оставьте пустым, чтобы сохранить текущее изображение</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" 
                               {{ old('is_available', $dish->is_available) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_available">
                            Доступно для заказа
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save fa-sm"></i> Сохранить
                        </button>
                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times fa-sm"></i> Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

