@extends('admins.layouts.master')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Thông tin đơn hàng -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết đơn hàng #{{ $order->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Thông tin khách hàng -->
                        <div class="col-md-6">
                            <h5>Thông tin khách hàng</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tên:</strong></td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Số điện thoại:</strong></td>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Địa chỉ:</strong></td>
                                    <td>
                                        {{ $order->address }}<br>
                                        <small class="text-muted">{{ $order->full_address }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Ghi chú:</strong></td>
                                    <td>{{ $order->note ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Thông tin đơn hàng -->
                        <div class="col-md-6">
                            <h5>Thông tin đơn hàng</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Mã đơn hàng:</strong></td>
                                    <td>#{{ $order->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        <span class="badge {{ $order->status_badge_class }}">
                                            {{ $order->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Phương thức thanh toán:</strong></td>
                                    <td>
                                        <span class="badge {{ $order->payment_badge_class }}">
                                            {{ $order->payment_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái thanh toán:</strong></td>
                                    <td>
                                        <span class="badge {{ $order->payment_status_badge_class }}">
                                            <i class="fas fa-circle"></i> {{ $order->payment_status_text }}
                                        </span>
                                    </td>
                                </tr>
                                @if($order->vorcher_code)
                                <tr>
                                    <td><strong>Mã giảm giá:</strong></td>
                                    <td>{{ $order->vorcher_code }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Tổng tiền:</strong></td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                </tr>
                                @if($order->sale_price)
                                <tr>
                                    <td><strong>Mã giảm giá:</strong></td>
                                    <td class="text-danger">-{{ number_format($order->sale_price, 0, ',', '.') }}đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Thanh toán thực tế:</strong></td>
                                    <td>{{ number_format($order->pay_amount, 0, ',', '.') }}đ</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="card">
                <div class="card-header"><h4>Chi tiết sản phẩm</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Màu sắc</th>
                                    <th>Kích thước</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @foreach($order->orderDetails as $detail)
                                    @php $itemTotal = $detail->price * $detail->quantity; @endphp
                                    @php $subtotal += $itemTotal; @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($detail->productVariant && $detail->productVariant->image)
                                                    <img src="{{ $detail->productVariant->image }}" 
                                                         alt="Product" 
                                                         class="img-thumbnail me-2" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $detail->productVariant->product->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Mã: {{ $detail->productVariant->product->code ?? 'N/A' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $detail->productVariant->color->code ?? '#ccc' }}">
                                                {{ $detail->productVariant->color->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $detail->productVariant->size->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td><strong>{{ number_format($itemTotal, 0, ',', '.') }}đ</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="5" class="text-right"><strong>Tạm tính:</strong></td>
                                    <td><strong>{{ number_format($subtotal, 0, ',', '.') }}đ</strong></td>
                                </tr>
                                @if($order->sale_price)
                                <tr class="table-light">
                                    <td colspan="5" class="text-right"><strong>Giảm giá:</strong></td>
                                    <td><strong class="text-danger">-{{ number_format($order->sale_price, 0, ',', '.') }}đ</strong></td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                                    <td><strong class="text-primary">{{ number_format($order->pay_amount, 0, ',', '.') }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="row">
                <!-- Cập nhật trạng thái đơn hàng -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-box"></i> Cập nhật trạng thái đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" id="updateStatusForm">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="status">Trạng thái đơn hàng:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Đang giao hàng</option>
                                        <option value="4" {{ $order->status == 4 ? 'selected' : '' }}>Đã giao</option>
                                        <option value="5" {{ $order->status == 5 ? 'selected' : '' }}>Đã hủy</option>
                                        <option value="6" {{ $order->status == 6 ? 'selected' : '' }}>Đã trả hàng</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="note">Ghi chú (tùy chọn):</label>
                                    <textarea name="note" id="note" class="form-control" rows="2" placeholder="Thêm ghi chú về việc cập nhật trạng thái..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" id="updateBtn">
                                    <i class="fas fa-save"></i> Cập nhật trạng thái
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cập nhật trạng thái thanh toán -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-credit-card"></i> Cập nhật trạng thái thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" id="updatePaymentStatusForm">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="payment_status">Trạng thái thanh toán:</label>
                                    <select name="payment_status" id="payment_status" class="form-control">
                                        <option value="0" {{ ($order->payment_status ?? 0) == 0 ? 'selected' : '' }}>
                                            <i class="fas fa-clock"></i> Chưa thanh toán
                                        </option>
                                        <option value="1" {{ ($order->payment_status ?? 0) == 1 ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Đã thanh toán
                                        </option>
                                        <option value="2" {{ ($order->payment_status ?? 0) == 2 ? 'selected' : '' }}>
                                            <i class="fas fa-times-circle"></i> Thanh toán thất bại
                                        </option>
                                        <option value="3" {{ ($order->payment_status ?? 0) == 3 ? 'selected' : '' }}>
                                            <i class="fas fa-undo"></i> Đã hoàn tiền
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="payment_note">Ghi chú thanh toán (tùy chọn):</label>
                                    <textarea name="payment_note" id="payment_note" class="form-control" rows="2" placeholder="Thêm ghi chú về việc thanh toán...">{{ $order->payment_note ?? '' }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block" id="updatePaymentBtn">
                                    <i class="fas fa-money-check-alt"></i> Cập nhật thanh toán
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thao tác khác -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-tools"></i> Thao tác khác</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary btn-block" onclick="window.print()">
                                <i class="fas fa-print"></i> In đơn hàng
                            </button>
                        </div>
                        
                                
                                <form id="deleteForm" method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Update status form
    $('#updateStatusForm').submit(function() {
        var $btn = $('#updateBtn');
        var originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        setTimeout(function() {
            $btn.html(originalHtml).prop('disabled', false);
        }, 3000);
    });

    // Update payment status form
    $('#updatePaymentStatusForm').submit(function() {
        var $btn = $('#updatePaymentBtn');
        var originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        setTimeout(function() {
            $btn.html(originalHtml).prop('disabled', false);
        }, 3000);
    });

    // Status change validation
    $('#status').change(function() {
        var currentStatus = {{ $order->status }};
        var newStatus = parseInt($(this).val());
        
        // Warning for critical status changes
        if ((currentStatus < 4 && newStatus == 5) || newStatus == 6) {
            if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái này không?')) {
                $(this).val(currentStatus);
            }
        }
    });

    // Payment status change validation  
    $('#payment_status').change(function() {
        var currentPaymentStatus = {{ $order->payment_status ?? 0 }};
        var newPaymentStatus = parseInt($(this).val());
        
        // Warning for refund
        if (newPaymentStatus == 3) {
            if (!confirm('Bạn có chắc chắn muốn hoàn tiền cho đơn hàng này không?')) {
                $(this).val(currentPaymentStatus);
            }
        }
    });
});


</script>
@endsection