@extends('admins.layouts.master')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách liên hệ</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>SĐT</th>
                <th>Email</th>
                <!-- <th>Tin nhắn</th> -->
                <th>Ngày gửi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $index => $contact)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ $contact->email }}</td>
                    <!-- <td>{{ Str::limit($contact->message, 50) }}</td> -->
                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-info btn-sm">Xem</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
    {!! $contacts->links('pagination::bootstrap-4') !!}
    </div>

</div>
@endsection
