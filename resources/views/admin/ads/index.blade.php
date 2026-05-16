@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">
                <i class="fas fa-shield-alt me-2"></i>Панель администратора
            </h1>
            <p class="text-muted">Модерация объявлений</p>
        </div>
    </div>

    <!-- Статистика -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-clock text-warning me-2"></i>Ожидают модерации
                    </h5>
                    <h2 class="text-warning">{{ $pendingCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-check-circle text-success me-2"></i>Одобрено
                    </h5>
                    <h2 class="text-success">{{ $approvedCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-times-circle text-danger me-2"></i>Отклонено
                    </h5>
                    <h2 class="text-danger">{{ $rejectedCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.ads.index', ['status' => 'all']) }}" 
                           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Все
                        </a>
                        <a href="{{ route('admin.ads.index', ['status' => 'pending']) }}" 
                           class="btn {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                            Ожидают модерации ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('admin.ads.index', ['status' => 'approved']) }}" 
                           class="btn {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">
                            Одобрено
                        </a>
                        <a href="{{ route('admin.ads.index', ['status' => 'rejected']) }}" 
                           class="btn {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Отклонено
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Список объявлений -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($ads->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Автор</th>
                                        <th>Категория</th>
                                        <th>Цена</th>
                                        <th>Статус</th>
                                        <th>Дата создания</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ads as $ad)
                                        <tr>
                                            <td>{{ $ad->id }}</td>
                                            <td>
                                                <a href="{{ route('admin.ads.show', $ad) }}" class="text-decoration-none">
                                                    {{ Str::limit($ad->title, 50) }}
                                                </a>
                                            </td>
                                            <td>{{ $ad->user->name }}</td>
                                            <td>{{ $ad->category->name }}</td>
                                            <td>{{ number_format($ad->price, 0, ',', ' ') }} ₽</td>
                                            <td>
                                                @if($ad->status === 'pending')
                                                    <span class="badge bg-warning">Ожидает модерации</span>
                                                @elseif($ad->status === 'approved')
                                                    <span class="badge bg-success">Одобрено</span>
                                                @elseif($ad->status === 'rejected')
                                                    <span class="badge bg-danger">Отклонено</span>
                                                @endif
                                            </td>
                                            <td>{{ $ad->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.ads.show', $ad) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($ad->status === 'pending')
                                                        <form action="{{ route('admin.ads.approve', $ad) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success" onclick="return confirm('Одобрить объявление?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.ads.reject', $ad) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Отклонить объявление?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить объявление?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
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
                                            <a class="page-link" href="{{ $ads->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($ads->appends(request()->query())->getUrlRange(1, $ads->lastPage()) as $page => $url)
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
                                            <a class="page-link" href="{{ $ads->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
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
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Нет объявлений для отображения</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
