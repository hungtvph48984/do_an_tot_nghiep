@extends('admins.layouts.master')

@section('content')
<div class="container">
    <h2 class="mb-4">Chi tiết liên hệ #{{ $contact->id }}</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $contact->name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
            <p><strong>Email:</strong> {{ $contact->email }}</p>
            <p><strong>Tin nhắn:</strong></p>
            <p class="border rounded p-2 bg-light">{{ $contact->message }}</p>
            <p><strong>Ngày gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Form phản hồi --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Trả lời khách hàng</h5>
        </div>
        <div class="card-body">
<form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="reply" class="form-label">Nội dung phản hồi</label>
                    <textarea name="reply" id="reply" rows="4" class="form-control" required>{{ old('reply') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
