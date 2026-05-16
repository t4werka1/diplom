@extends('layouts.app')

@section('title', 'Категории')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Категории</h1>
                    <p class="text-muted mb-0">Список категорий предметов</p>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($categories as $category)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} fa-2x text-secondary me-3"></i>
                                @endif
                                <div>
                                    <h5 class="mb-1">
                                        <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">{{ $category->name }}</a>
                                    </h5>
                                    @if($category->description)
                                        <p class="text-muted mb-0">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($category->children->count() > 0)
                            <div class="mt-3 ms-4">
                                <div class="list-group list-group-flush">
                                    @foreach($category->children as $child)
                                        <div class="list-group-item border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }} fa-lg text-secondary me-3"></i>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('categories.show', $child) }}" class="text-decoration-none">{{ $child->name }}</a>
                                                        </h6>
                                                        @if($child->description)
                                                            <p class="text-muted mb-0 small">{{ $child->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="list-group-item text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">Категории пока не созданы</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 