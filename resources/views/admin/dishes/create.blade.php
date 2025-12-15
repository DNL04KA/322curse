@extends('layouts.app')

@section('title', 'Добавить блюдо')

@section('content')
<div class="container mt-4">
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dishes.index') }}">Блюда</a></li>
                <li class="breadcrumb-item active">Добавить</li>
            </ol>
        </nav>
        <h1><i class="fas fa-plus"></i> Добавить блюдо</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.dishes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="restaurant_id" class="form-label">Ресторан <span class="text-danger">*</span></label>
                        <select class="form-select @error('restaurant_id') is-invalid @enderror" 
                                id="restaurant_id" name="restaurant_id" required>
                            <option value="">Выберите ресторан</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ old('restaurant_id', request('restaurant_id')) == $restaurant->id ? 'selected' : '' }}>
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
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                <option value="Супы" {{ old('category') == 'Супы' ? 'selected' : '' }}>Супы</option>
                                <option value="Салаты" {{ old('category') == 'Салаты' ? 'selected' : '' }}>Салаты</option>
                                <option value="Горячие блюда" {{ old('category') == 'Горячие блюда' ? 'selected' : '' }}>Горячие блюда</option>
                                <option value="Гарниры" {{ old('category') == 'Гарниры' ? 'selected' : '' }}>Гарниры</option>
                                <option value="Закуски" {{ old('category') == 'Закуски' ? 'selected' : '' }}>Закуски</option>
                                <option value="Напитки" {{ old('category') == 'Напитки' ? 'selected' : '' }}>Напитки</option>
                                <option value="Десерты" {{ old('category') == 'Десерты' ? 'selected' : '' }}>Десерты</option>
                                <option value="Выпечка" {{ old('category') == 'Выпечка' ? 'selected' : '' }}>Выпечка</option>
                                <option value="Другое" {{ old('category') == 'Другое' ? 'selected' : '' }}>Другое</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Цена (BYN) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" 
                                   step="0.01" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Изображение</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_available" value="0">
                        <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" 
                               {{ old('is_available', true) ? 'checked' : '' }}>
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

