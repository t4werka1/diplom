@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-4">Мои объявления</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('ads.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Разместить объявление
            </a>
        </div>
    </div>

    @if($ads->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>У вас пока нет объявлений
        </div>
    @else
        <div class="row">
            @foreach($ads as $ad)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($ad->image)
                            <img src="/storage/{{ $ad->image }}" class="card-img-top" alt="{{ $ad->title }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="price-tag">{{ number_format($ad->price, 0, ',', ' ') }} ₽</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $ad->title }}</h5>
                                @if($ad->isSold())
                                    <span class="badge bg-secondary">Продано</span>
                                @elseif($ad->status === 'pending')
                                    <span class="badge bg-warning">Ожидает модерации</span>
                                @elseif($ad->status === 'approved')
                                    <span class="badge bg-success">Одобрено</span>
                                @elseif($ad->status === 'rejected')
                                    <span class="badge bg-danger">Отклонено</span>
                                @endif
                            </div>
                            <p class="card-text text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $ad->location }}
                            </p>
                            <p class="card-text">{{ Str::limit($ad->description, 100) }}</p>
                            @if($ad->status === 'rejected' && $ad->rejection_reason)
                                <div class="alert alert-danger alert-sm py-2 px-2 mb-0 mt-2">
                                    <small><i class="fas fa-exclamation-circle me-1"></i><strong>Причина отклонения:</strong> {{ $ad->rejection_reason }}</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('ads.show', $ad) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Просмотреть
                                </a>
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
                                <form action="{{ route('ads.destroy', $ad) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $ads->links() }}
        </div>
    @endif
</div>
@endsection 