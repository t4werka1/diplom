@extends('layouts.app')

@section('title', 'Диалог')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Диалог с {{ $partner->name }}</h5>
                    </div>
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-outline-secondary">
                        Назад к диалогам
                    </a>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @forelse($messages as $message)
                        <div class="d-flex mb-3 {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                            <div class="p-3 rounded {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 75%;">
                                <div class="small mb-1 {{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message->sender_id === auth()->id() ? 'Вы' : $partner->name }}
                                </div>
                                <div>{{ $message->body }}</div>
                                <div class="small mt-1 {{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message->created_at->format('d.m.Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Сообщений пока нет. Начните диалог.</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('messages.store', $partner) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="body" class="form-label">Сообщение</label>
                            <textarea
                                name="body"
                                id="body"
                                rows="4"
                                class="form-control @error('body') is-invalid @enderror"
                                maxlength="1000"
                                required
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Отправить
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
