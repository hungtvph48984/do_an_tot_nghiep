@extends('clients.layouts.master')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Danh sách yêu thích ❤️</h2>

    @if (count($wishlist) > 0)
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($wishlist as $item)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ Storage::url($item['image']) }}" class="card-img-top" alt="{{ $item['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['name'] }}</h5>
                            <p class="card-text fw-bold text-danger">{{ $item['price'] }} $</p>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.show', $item['id']) }}" class="btn btn-primary btn-sm">
                                    Xem chi tiết
                                </a>

<form action="{{ route('wishlist.remove') }}" method="POST" class="wishlist-remove-form d-inline">
    @csrf
    <input type="hidden" name="product_id" value="{{ $item['id'] }}">
    <button type="button" class="btn btn-sm btn-danger btn-remove-wishlist" data-id="{{ $item['id'] }}">
        Xóa
    </button>
</form>


                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center">Danh sách yêu thích của bạn đang trống.</p>
    @endif
</div>
@endsection
