@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="d-flex align-items-center">
                        @if($category->icon)
                            <i class="{{ $category->icon }} fa-2x text-secondary me-3"></i>
                        @endif
                        <h1 class="h3 mb-0">{{ $category->name }}</h1>
                    </div>
                    @if($category->description)
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($category->children->count() > 0)
            <div class="card-body border-top">
                <h2 class="h5 mb-4">Подкатегории</h2>
                <div class="row g-4">
                    @foreach($category->children as $child)
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }} fa-lg text-secondary me-3"></i>
                                        @endif
                                        <div>
                                            <h3 class="h6 mb-1">
                                                <a href="{{ route('categories.show', $child) }}" class="text-decoration-none">{{ $child->name }}</a>
                                            </h3>
                                            @if($child->description)
                                                <p class="text-muted mb-0 small">{{ $child->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card-body border-top">
            <h2 class="h5 mb-4">Объявления в категории</h2>
            @if($category->children->count() > 0)
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Показаны объявления из категории "{{ $category->name }}" и всех её подкатегорий.
                </div>
            @endif
            <div class="row g-4">
                @forelse($ads as $ad)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        @if($ad->image)
                                            <img src="/storage/{{ $ad->image }}" alt="{{ $ad->title }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                                                <i class="fas fa-image text-secondary fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="h6 mb-1">
                                            <a href="{{ route('ads.show', $ad) }}" class="text-decoration-none">{{ $ad->title }}</a>
                                        </h3>
                                        <p class="text-muted small mb-2">{{ Str::limit($ad->description, 100) }}</p>
                                        <span class="badge bg-success">{{ $ad->condition }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-between text-muted small">
                                    <span>
                                        <i class="fas fa-user me-1"></i>
                                        {{ $ad->user->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $ad->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>В этой категории пока нет объявлений</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($ads->hasPages())
                <div class="mt-4">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 