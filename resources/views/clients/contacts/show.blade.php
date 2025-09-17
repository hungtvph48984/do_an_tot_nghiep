@extends('clients.layouts.master')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container py-5">
    <div class="mb-4">
    <a href="{{ route('contacts.my') }}" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>

    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body">
            <h4 class="card-title text-primary mb-3">
                <i class="bi bi-chat-dots"></i> Liên hệ #{{ $contact->id }}
            </h4>

            <p><strong>Họ tên:</strong> {{ $contact->name }}</p>
            <p><strong>Email:</strong> {{ $contact->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
            <hr>
            <p><strong>Lời nhắn:</strong></p>
            <p class="p-3 bg-light rounded">{{ $contact->message }}</p>

            <hr>
            @if($contact->reply)
                <p class="fw-bold text-success">
                    <i class="bi bi-reply-fill"></i> Phản hồi từ admin:
                </p>
                <p class="p-3 bg-white border rounded">{{ $contact->reply }}</p>
            @else
                <p class="text-muted">
                    <i class="bi bi-hourglass-split"></i> Chưa có phản hồi
                </p>
            @endif

            <hr>
            <p class="text-muted text-end">
                🗓️ Gửi ngày: {{ $contact->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</div>
@endsection
