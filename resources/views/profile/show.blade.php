@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">{{ $user->name }}</h2>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Дата регистрации:</strong> {{ $user->created_at->format('d.m.Y') }}</p>
                        </div>
                    </div>

                    @auth
                        @if(auth()->id() !== $user->id && !auth()->user()->isAdmin() && !$user->isAdmin())
                            <div class="mb-4">
                                <a href="{{ route('messages.show', $user) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>Написать сообщение
                                </a>
                            </div>
                        @endif
                    @endauth

                    <h3 class="mb-3">Объявления пользователя</h3>
                    <div class="row">
                        @forelse($ads as $ad)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    @if($ad->image)
                                        <img src="/storage/{{ $ad->image }}" class="card-img-top" alt="{{ $ad->title }}">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $ad->title }}</h5>
                                        <p class="card-text">{{ Str::limit($ad->description, 100) }}</p>
                                        <p class="card-text"><small class="text-muted">{{ $ad->created_at->format('d.m.Y H:i') }}</small></p>
                                        <a href="{{ route('ads.show', $ad) }}" class="btn btn-primary">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center">У пользователя пока нет объявлений</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
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
            </div>
        </div>
    </div>
</div>

<style>
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
    /* Удаляем анимацию для .card */
    .card {
        /* transition: transform 0.2s ease-in-out; */
    }
    .card:hover {
        /* transform: translateY(-5px); */
    }
</style>
@endsection 