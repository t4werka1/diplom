@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.ads.index') }}">Панель администратора</a></li>
            <li class="breadcrumb-item active">{{ $ad->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                @if($ad->image)
                    <img src="/storage/{{ $ad->image }}" class="card-img-top" alt="{{ $ad->title }}" style="width: 100%; height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="card-title h2 mb-0">{{ $ad->title }}</h1>
                        <span class="badge 
                            @if($ad->status === 'pending') bg-warning
                            @elseif($ad->status === 'approved') bg-success
                            @elseif($ad->status === 'rejected') bg-danger
                            @endif">
                            @if($ad->status === 'pending')
                                Ожидает модерации
                            @elseif($ad->status === 'approved')
                                Одобрено
                            @elseif($ad->status === 'rejected')
                                Отклонено
                            @endif
                        </span>
                    </div>
                    
                    <p class="h2 text-primary mb-4">{{ number_format($ad->price, 0, ',', ' ') }} ₽</p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Категория</h5>
                            <p class="card-text">
                                <i class="fas fa-{{ $ad->category->icon }} me-2"></i>{{ $ad->category->name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Состояние</h5>
                            <p class="card-text">
                                <i class="fas fa-{{ $ad->condition == 'new' ? 'star' : 'check-circle' }} me-2"></i>
                                {{ $ad->condition == 'new' ? 'Новое' : 'Б/У' }}
                            </p>
                        </div>
                    </div>

                    <h5 class="mb-3">Подробное описание</h5>
                    <p class="card-text">{{ $ad->description }}</p>

                    @if($ad->reason_for_sale)
                        <h5 class="mb-3 mt-4">Причина продажи</h5>
                        <p class="card-text">{{ $ad->reason_for_sale }}</p>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Местоположение</h5>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $ad->location }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Дата создания</h5>
                            <p class="card-text">
                                <i class="fas fa-calendar me-2"></i>{{ $ad->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Автор объявления</h5>
                    <p class="card-text">
                        <i class="fas fa-user me-2"></i>
                        <a href="{{ route('profile.show', $ad->user) }}" class="text-decoration-none">
                            {{ $ad->user->name }}
                        </a>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-envelope me-2"></i>{{ $ad->user->email }}
                    </p>
                    @if($ad->user->phone)
                        <p class="card-text">
                            <i class="fas fa-phone me-2"></i>{{ $ad->user->phone }}
                        </p>
                    @endif
                    <p class="card-text">
                        <i class="fas fa-clock me-2"></i>На сайте с {{ $ad->user->created_at->format('d.m.Y') }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Действия модератора</h5>
                    @if($ad->status === 'pending')
                        <form action="{{ route('admin.ads.approve', $ad) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Одобрить объявление?')">
                                <i class="fas fa-check me-2"></i>Одобрить и опубликовать
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.ads.reject', $ad) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Причина отклонения (необязательно)</label>
                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" placeholder="Укажите причину отклонения объявления"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Отклонить объявление?')">
                                <i class="fas fa-times me-2"></i>Отклонить
                            </button>
                        </form>
                    @elseif($ad->status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>Объявление одобрено и опубликовано
                        </div>
                    @elseif($ad->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>Объявление отклонено
                            @if($ad->rejection_reason)
                                <p class="mb-0 mt-2"><strong>Причина:</strong> {{ $ad->rejection_reason }}</p>
                            @endif
                        </div>
                    @endif
                    
                    <hr>
                    
                    <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это объявление?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Удалить объявление
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.ads.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i>Вернуться к списку
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
