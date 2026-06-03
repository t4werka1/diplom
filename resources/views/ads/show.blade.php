@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('ads.index') }}">Объявления</a></li>
            <li class="breadcrumb-item active">{{ $ad->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                @if($ad->image)
                    <img src="/storage/{{ $ad->image }}" 
                         class="card-img-top" 
                         alt="{{ $ad->title }}" 
                         style="width: 100%; height: 400px; object-fit: cover; cursor: zoom-in;"
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal"
                         title="Нажмите, чтобы увеличить">
                @else
                    <div class="card-img-top bg-light d-flex flex-column align-items-center justify-content-center text-muted" style="height: 400px;">
                        <i class="fas fa-image fa-3x mb-2"></i>
                        <span>Фото нет</span>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="card-title h2 mb-0">{{ $ad->title }}</h1>
                        @if(Auth::id() === $ad->user_id)
                            @if($ad->isSold())
                                <span class="badge bg-secondary">Продано</span>
                            @elseif($ad->status === 'pending')
                                <span class="badge bg-warning">Ожидает модерации</span>
                            @elseif($ad->status === 'approved')
                                <span class="badge bg-success">Одобрено</span>
                            @elseif($ad->status === 'rejected')
                                <span class="badge bg-danger">Отклонено</span>
                            @endif
                        @endif
                    </div>
                    <p class="h2 text-primary mb-4">{{ number_format($ad->price, 0, ',', ' ') }} ₽</p>
                    
                    @if(Auth::id() === $ad->user_id && $ad->status === 'rejected' && $ad->rejection_reason)
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Объявление отклонено.</strong> Причина: {{ $ad->rejection_reason }}
                        </div>
                    @endif
                    
                    @if(Auth::id() === $ad->user_id && $ad->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            Ваше объявление находится на модерации. После проверки администратором оно будет опубликовано.
                        </div>
                    @endif
                    
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
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Продавец</h5>
                    <p class="card-text">
                        <i class="fas fa-user me-2"></i>
                        <a href="{{ route('profile.show', $ad->user) }}" class="text-decoration-none">
                            {{ $ad->user->name }}
                        </a>
                    </p>
                    <p class="card-text">
                        <i class="fas fa-clock me-2"></i>На сайте с {{ $ad->user->created_at->format('d.m.Y') }}
                    </p>
                    <hr>
                    <div class="d-grid gap-2">
                        @if($ad->user->phone)
                            <button id="phoneButton" onclick="showPhone(this, '{{ $ad->user->phone }}')" class="btn btn-primary">
                                <i class="fas fa-phone me-2"></i>Показать телефон
                            </button>
                        @else
                            <button class="btn btn-primary" disabled>
                                <i class="fas fa-phone me-2"></i>Телефон не указан
                            </button>
                        @endif
                        @auth
                            @if(Auth::id() !== $ad->user_id)
                                @if(!Auth::user()->isAdmin() && !$ad->user->isAdmin())
                                    <a href="{{ route('messages.show', $ad->user) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-envelope me-2"></i>Написать продавцу
                                    </a>
                                @endif
                                @if(Auth::user()->favorites()->where('ad_id', $ad->id)->exists())
                                    <form action="{{ route('favorites.remove', $ad) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-heart me-2"></i>Удалить из избранного
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('favorites.add', $ad) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="fas fa-heart me-2"></i>Добавить в избранное
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            @if(Auth::id() === $ad->user_id)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Управление объявлением</h5>
                        <div class="d-grid gap-2">
                            @if($ad->status === 'approved' && !$ad->isSold())
                                <form action="{{ route('ads.sold', $ad) }}" method="POST" onsubmit="return confirm('Отметить объявление как проданное?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check-circle me-2"></i>Отметить как проданное
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('ads.edit', $ad) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Редактировать
                            </a>
                            @if($ad->status === 'rejected')
                                <form action="{{ route('ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Удалить
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Удалить
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if($ad->image)
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="/storage/{{ $ad->image }}" 
                     class="img-fluid rounded" 
                     alt="{{ $ad->title }}" 
                     style="max-height: 85vh; cursor: zoom-out;"
                     data-bs-dismiss="modal">
            </div>
        </div>
    </div>
</div>
@endif

<script>
function showPhone(button, phone) {
    button.innerHTML = '<i class="fas fa-phone me-2"></i>' + phone;
}
</script>

<style>
#imageModal .modal-content {
    background: rgba(0, 0, 0, 0.9) !important;
}
#imageModal .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
@endsection