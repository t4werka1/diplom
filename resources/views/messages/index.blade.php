@extends('layouts.app')

@section('title', 'Сообщения')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Мои диалоги</h4>
                </div>

                <div class="card-body">
                    @forelse($conversations as $conversation)
                        @php
                            $partner = $conversation->sender_id === auth()->id() ? $conversation->recipient : $conversation->sender;
                            $isOwnMessage = $conversation->sender_id === auth()->id();
                        @endphp
                        <a href="{{ route('messages.show', $partner) }}" class="list-group-item list-group-item-action mb-3 border rounded p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $partner->name }}</h6>
                                    <p class="mb-1 text-muted">
                                        @if($isOwnMessage)
                                            <span class="me-1">Вы:</span>
                                        @endif
                                        {{ \Illuminate\Support\Str::limit($conversation->body, 80) }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">{{ $conversation->created_at->diffForHumans() }}</small>
                                    @if(isset($unreadCounts[$partner->id]) && $unreadCounts[$partner->id] > 0)
                                        <span class="badge bg-danger mt-1">{{ $unreadCounts[$partner->id] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted mb-0">Пока нет диалогов.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
