@extends('clients.layouts.master')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container py-4 orders-page">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="mb-0 fw-bold">Đơn hàng của tôi</h2>
            <div class="text-muted small">Quản lý và theo dõi tình trạng đơn hàng của bạn</div>
        </div>
        @if(isset($orders) && method_exists($orders,'total'))
            <span class="badge rounded-pill bg-dark-subtle text-dark fw-semibold px-3 py-2">
                Tổng: {{ number_format($orders->total(), 0, ',', '.') }}
            </span>
        @endif
    </div>

    <div class="row">
        <!-- FILTER -->
        <div class="col-lg-3 order-2 order-lg-1">
            <div class="card filter-card border-0 shadow-sm mb-4 position-sticky" style="top: 24px;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Bộ lọc</h5>
                        <i class="fa-regular fa-sliders-up"></i>
                    </div>

                    <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                        <!-- Tìm kiếm -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tìm kiếm</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-regular fa-magnifying-glass"></i></span>
                                <input type="text" name="keyword" value="{{ request('keyword') }}"
                                    class="form-control border-start-0 rounded-end"
                                    placeholder="Mã đơn hoặc tên sản phẩm">
                            </div>
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Tất cả</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang giao</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Hoàn tất</option>
                                {{-- Nếu bạn có thêm các trạng thái khác, front sẽ vẫn hoạt động bình thường --}}
                            </select>
                        </div>

                        <!-- Khoảng giá -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Khoảng giá (VNĐ)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                       class="form-control" placeholder="Từ">
                                <span class="input-group-text">—</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                       class="form-control" placeholder="Đến">
                            </div>
                        </div>

                        <!-- Ngày đặt -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ngày đặt</label>
                            <div class="d-flex flex-column gap-2">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                       class="form-control form-control-sm">
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                       class="form-control form-control-sm">
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-12 pt-1">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark btn-sm flex-fill rounded-pill">
                                    <i class="fa-regular fa-filter-list"></i> Lọc
                                </button>
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm flex-fill rounded-pill">
                                    <i class="fa-regular fa-rotate-left"></i> Reset
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- ORDER LIST -->
        <div class="col-lg-9 order-1 order-lg-2">
            @if($orders->isEmpty())
                <div class="empty-state text-center py-5">
                    <img src="https://cdn.jsdelivr.net/gh/tabler/tabler-icons/icons-bundled/package.svg" alt="" width="56" class="mb-3 opacity-50">
                    <h5 class="fw-bold">Bạn chưa có đơn hàng nào</h5>
                    <div class="text-muted">Bắt đầu mua sắm để thấy đơn hàng xuất hiện tại đây.</div>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="order-card card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                        <!-- Header -->
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="order-id text-uppercase fw-bold">#{{ $order->id }}</div>
                                <div class="text-muted small">
                                    Ngày đặt: <strong>{{ $order->created_at->format('d/m/Y H:i') }}</strong>
                                </div>
                            </div>

                            @php
                                // Fallback nếu không có accessor trong Model
                                $__badge = $order->status_badge_class ?? null;
                                $__text  = $order->status_text ?? null;

                                if (! $__badge) {
                                    $__badge = match((int)$order->status) {
                                        0 => 'bg-warning',
                                        1 => 'bg-info',
                                        2 => 'bg-success',
                                        3 => 'bg-primary',
                                        4 => 'bg-success',
                                        6 => 'bg-secondary',
                                        default => 'bg-dark'
                                    };
                                }
                                if (! $__text) {
                                    $__text = match((int)$order->status) {
                                        0 => 'Đang xử lý',
                                        1 => 'Đang giao',
                                        2 => 'Hoàn tất',
                                        3 => 'Đang giao',
                                        4 => 'Đã giao',
                                        6 => 'Đã yêu cầu trả hàng',
                                        default => 'Không xác định'
                                    };
                                }
                            @endphp

                            <span class="badge status-badge {{ $__badge }}">{{ $__text }}</span>
                        </div>

                        <!-- Body -->
                        <div class="card-body p-0">
                            <!-- Items grid -->
                            <div class="p-3">
                                <div class="row g-3">
                                    @foreach($order->orderDetails as $detail)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="item-card card h-100 border-0 rounded-3 shadow-xs hover-lift">
                                                <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                                                    <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                         class="object-fit-cover w-100 h-100"
                                                         alt="{{ $detail->product->name }}">
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h6 class="card-title fw-semibold mb-1 two-line">
                                                            {{ $detail->product->name }}
                                                        </h6>
                                                        <span class="badge text-bg-light">x{{ $detail->quantity }}</span>
                                                    </div>
                                                    <p class="text-muted small mb-2 one-line">
                                                        {{ Str::limit($detail->product->description, 80) }}
                                                    </p>
                                                    <div class="price fw-bold text-danger">
                                                        {{ number_format($detail->price, 0, ',', '.') }} VNĐ
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer: total + actions -->
                            <div class="order-footer d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 border-top px-3 py-3">
                                <div class="text-muted small">Phương thức: <span class="fw-semibold">{{ $order->payment_text ?? '' }}</span></div>
                                <div class="d-flex align-items-center gap-3 ms-md-auto">
                                    <div class="total d-flex align-items-baseline gap-2">
                                        <span class="text-muted">Thành tiền:</span>
                                        <span class="fw-bold text-danger fs-5">{{ number_format($order->pay_amount, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions by status -->
                            <div class="px-3 pb-3">
                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    @if($order->status == \App\Models\Order::STATUS_SHIPPING)
                                        <form method="POST" action="{{ route('orders.confirmReceived', $order->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill">
                                                <i class="fa-regular fa-badge-check me-1"></i> Đã nhận được hàng
                                            </button>
                                        </form>
                                        <span class="text-muted small">*Nếu không xác nhận, sau 3 ngày hệ thống sẽ tự chuyển "Đã giao".</span>
                                    @endif

                                    @php
                                        $deliveredAt = $order->delivered_at ?? $order->updated_at;
                                        $canRequestReturn = ($order->status == \App\Models\Order::STATUS_DELIVERED)
                                            && $deliveredAt->diffInDays(now()) <= 7;
                                        $daysLeft = max(0, 7 - $deliveredAt->diffInDays(now()));
                                    @endphp

                                    @if($canRequestReturn)
                                        <form method="POST" action="{{ route('orders.requestReturn', $order->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                <i class="fa-regular fa-rotate-left me-1"></i> Yêu cầu trả hàng / Hoàn tiền
                                            </button>
                                        </form>
                                        <span class="text-muted small">Còn {{ $daysLeft }} ngày để yêu cầu.</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->appends(request()->query())->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ======= Tổng thể ======= */
.orders-page { --radius: 14px; }
.shadow-xs { box-shadow: 0 6px 20px rgba(0,0,0,.04); }
.hover-lift { transition: transform .2s ease, box-shadow .2s ease; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.08); }
.two-line {
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden;
}
.one-line { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ======= Filter card ======= */
.filter-card { border-radius: var(--radius); }
.filter-card .form-control,
.filter-card .form-select,
.filter-card .input-group-text {
    border-radius: 10px !important;
}
.filter-card .input-group-text { border-right: 0; }
.filter-card .form-control { border-left: 0; }

/* ======= Order card ======= */
.order-card { border-radius: 20px; }
.order-card .card-header {
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
}
.order-card .status-badge {
    font-size: .78rem;
    padding: .45rem .75rem;
    border-radius: 999px;
}

/* ======= Item card ======= */
.item-card { border-radius: 14px; background: #fff; }
.item-card .ratio { background: #f8f9fa; }

/* ======= Footer ======= */
.order-footer { background: #fff; }

/* ======= Empty ======= */
.empty-state img { filter: grayscale(1); }

/* Misc */
.object-fit-cover { object-fit: cover; }
</style>
@endpush
