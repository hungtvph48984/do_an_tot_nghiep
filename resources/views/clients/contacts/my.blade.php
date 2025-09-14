@extends('clients.layouts.master')

@section('title', 'Liên hệ của tôi')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">📩 Liên hệ của tôi</h2>
        <p class="text-muted">Theo dõi toàn bộ phản hồi của bạn từ shop</p>
    </div>

    @if($contacts->isEmpty())
        <div class="alert alert-info text-center shadow-sm rounded">
            Bạn chưa có liên hệ nào với shop.
        </div>
    @else
        <div class="timeline">
            @foreach($contacts as $contact)
                <div class="timeline-item mb-4">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0 text-primary">
                                    <i class="bi bi-chat-left-text"></i> Liên hệ #{{ $contact->id }}
                                </h5>
                                @if($contact->reply)
                                    <span class="badge bg-success">Đã phản hồi</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chờ phản hồi</span>
                                @endif
                            </div>

                            <p class="mb-2"><strong>Lời nhắn:</strong> {{ $contact->message }}</p>

                            @if($contact->reply)
                                <div class="p-3 bg-light border rounded mb-2">
                                    <p class="mb-1 text-dark fw-bold">
                                        <i class="bi bi-reply-fill"></i> Phản hồi từ admin:
                                    </p>
                                    <p class="mb-0">{{ $contact->reply }}</p>
                                </div>
                            @else
                                <p class="text-muted">
                                    <i class="bi bi-hourglass-split"></i> Chưa có phản hồi
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    🗓️ {{ $contact->created_at->format('d/m/Y H:i') }}
                                </small>
                                <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.timeline {
    position: relative;
    padding-left: 20px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -2px;
    top: 15px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #0d6efd;
}
</style>
@endsection
