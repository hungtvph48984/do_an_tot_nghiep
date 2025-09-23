<form action="{{ route('admin.brands.store-link-products', $brand->id) }}" method="POST">
    @csrf
    <!-- Form content -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Tên sản phẩm</th>
                <th>Mã</th>
                <th>Danh mục</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}"
                            {{ $brand->products->contains($product->id) ? 'checked' : '' }}>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Không có sản phẩm nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Lưu liên kết</button>
</form>
