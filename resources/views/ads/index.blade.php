@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3">Добро пожаловать на QuickSell!</h1>
            <p class="lead mb-4">Найдите то, что ищете, или разместите свое объявление</p>
            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ route('ads.create') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-plus me-2"></i>Разместить объявление
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-sign-in-alt me-2"></i>Войти
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Зарегистрироваться
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Поиск объявлений</h2>
                    <form action="{{ route('ads.search') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="query" class="form-control form-control-lg" placeholder="Что ищете?" value="{{ request('query') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="condition" class="form-select form-select-lg">
                                <option value="">Состояние</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Новое</option>
                                <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>Б/У</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="number" name="min_price" class="form-control form-control-lg" placeholder="Цена от" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="form-control form-control-lg" placeholder="Цена до" value="{{ request('max_price') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-search me-2"></i>Найти
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Популярные категории</h2>
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-3">
                        <a href="{{ route('ads.category', $category->id) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm hover-shadow">
                                <div class="card-body text-center">
                                    <i class="fas fa-{{ $category->icon }} fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                    <p class="text-muted small">{{ $category->ads_count }} объявления</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Ads Grid -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4">Последние объявления</h2>
        </div>
    </div>

    <div class="row g-4">
        @foreach($ads as $ad)
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    <div class="position-relative">
                        @if($ad->image)
                            <img src="/storage/{{ $ad->image }}" class="card-img-top" alt="{{ $ad->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-primary d-flex align-items-center gap-1">
                                <i class="fas fa-{{ $ad->category->icon ?? 'folder' }}"></i>
                                {{ $ad->category->getFullPath() ?? 'Без категории' }}
                            </span>
                        </div>
                        <div class="position-absolute bottom-0 start-0 m-2">
                            <span class="badge bg-success fs-6">{{ number_format($ad->price, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-truncate">{{ $ad->title }}</h5>
                        <p class="card-text text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $ad->location }}
                        </p>
                        <p class="card-text">{{ Str::limit($ad->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user me-2 text-muted"></i>
                            <span class="text-truncate">{{ $ad->user->name }}</span>
                        </div>
                        <a href="{{ route('ads.show', $ad) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-2"></i>Подробнее
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($ads->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $ads->previousPageUrl() }}" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($ads->getUrlRange(1, $ads->lastPage()) as $page => $url)
                    @if ($page == $ads->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($ads->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $ads->nextPageUrl() }}" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>

<style>

    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(4px);
        background-color: rgba(13, 110, 253, 0.9) !important;
    }

    .badge i {
        font-size: 0.9em;
    }

    /* Стили для пагинации */
    .pagination {
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
    }

    .page-link:hover {
        background-color: #f7fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    .page-item.active .page-link {
        background-color: #4299e1;
        border-color: #4299e1;
        color: #fff;
    }

    .page-item.disabled .page-link {
        background-color: #f7fafc;
        border-color: #e2e8f0;
        color: #a0aec0;
        cursor: not-allowed;
    }

    .page-link i {
        font-size: 0.875rem;
    }

    @media (max-width: 576px) {
        .pagination {
            gap: 0.25rem;
        }

        .page-link {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
    }
</style>
@endsection 