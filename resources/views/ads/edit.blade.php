@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1 justify-content-center align-items-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-primary">
                        <i class="fas fa-edit me-2"></i>Редактирование объявления
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('ads.update', $ad) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="mb-3 text-muted">Основная информация</h5>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Заголовок</label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" 
                                        name="title" value="{{ old('title', $ad->title) }}" required autofocus>
                                    @error('title')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Описание</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" 
                                        name="description" rows="4" required>{{ old('description', $ad->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Цена</label>
                                    <div class="input-group">
                                        <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                            name="price" value="{{ old('price', $ad->price) }}" required>
                                        <span class="input-group-text">₽</span>
                                        @error('price')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3 text-muted">Дополнительная информация</h5>

                                <div class="mb-3">
                                    <label for="condition" class="form-label">Состояние</label>
                                    <select id="condition" class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                                        <option value="new" {{ old('condition', $ad->condition) == 'new' ? 'selected' : '' }}>Новое</option>
                                        <option value="used" {{ old('condition', $ad->condition) == 'used' ? 'selected' : '' }}>Б/у</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Категория</label>
                                    <select id="category_id" class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $ad->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Местоположение</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" 
                                            name="location" value="{{ old('location', $ad->location) }}" required>
                                        @error('location')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="reason_for_sale" class="form-label">Причина продажи</label>
                                    <textarea id="reason_for_sale" class="form-control @error('reason_for_sale') is-invalid @enderror" 
                                        name="reason_for_sale" rows="3">{{ old('reason_for_sale', $ad->reason_for_sale) }}</textarea>
                                    @error('reason_for_sale')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <h5 class="mb-3 text-muted">Изображение</h5>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Загрузить новое изображение</label>
                                    <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" 
                                        name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    @if($ad->image)
                                        <div class="mt-3">
                                            <p class="text-muted mb-2">Текущее изображение:</p>
                                            <img src="/storage/{{ $ad->image }}" alt="Current image" 
                                                class="img-thumbnail" style="max-width: 300px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('ads.show', $ad) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .container-fluid {
        flex: 1;
    }
    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.1);
    }
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    .btn {
        padding: 0.5rem 1.5rem;
        border-radius: 5px;
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    .invalid-feedback {
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection 