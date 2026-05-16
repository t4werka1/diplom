@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Профиль пользователя -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="text-center mb-4">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <i class="fas fa-user-circle fa-5x text-warning"></i>
                        @endif
                    </div>
                    <h4 class="card-title">{{ Auth::user()->name }}</h4>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}
                    </p>
                    @if(Auth::user()->phone)
                        <p class="text-muted">
                            <i class="fas fa-phone me-2"></i>{{ Auth::user()->phone }}
                        </p>
                    @endif
                    <div class="mt-4 d-flex justify-content-center gap-3">
                        <a href="{{ route('profile.edit', Auth::user()->id) }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-edit me-2"></i>Редактировать профиль
                        </a>
                        <a href="#" class="btn btn-outline-secondary" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="col-md-8">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-ad fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-subtitle text-muted">Активные объявления</h6>
                                    <h2 class="card-title mb-0">{{ Auth::user()->ads()->where('is_active', true)->whereNull('sold_at')->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-heart fa-2x text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-subtitle text-muted">Избранные объявления</h6>
                                    <h2 class="card-title mb-0">{{ Auth::user()->favorites()->count() }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Последние действия -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Мои объявления</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse(Auth::user()->ads()->latest()->take(5)->get() as $ad)
                            <a href="{{ route('ads.show', $ad) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $ad->title }}</h6>
                                    <small class="text-muted">{{ $ad->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ Str::limit($ad->description, 100) }}</p>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>У вас пока нет объявлений</p>
                                <a href="{{ route('ads.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Разместить объявление
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Избранные объявления -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Избранные объявления</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse(Auth::user()->favorites()->latest()->take(5)->get() as $ad)
                            <a href="{{ route('ads.show', $ad) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $ad->title }}</h6>
                                    <small class="text-muted">{{ $ad->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ Str::limit($ad->description, 100) }}</p>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-heart fa-3x mb-3"></i>
                                <p>У вас пока нет избранных объявлений</p>
                                <a href="{{ route('ads.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Найти объявления
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Проданные объявления -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Проданные объявления</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse(Auth::user()->ads()->whereNotNull('sold_at')->latest('sold_at')->take(5)->get() as $ad)
                            <a href="{{ route('ads.show', $ad) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $ad->title }}</h6>
                                    <small class="text-muted">Продано {{ $ad->sold_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-muted small">{{ Str::limit($ad->description, 100) }}</p>
                            </a>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p>Пока нет проданных объявлений</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
    }
    .card:hover {
    }
    .list-group-item {
        border: none;
        padding: 1rem 0;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection 