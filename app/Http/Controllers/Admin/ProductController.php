<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;

class ProductController extends Controller
{
    /** ================== INDEX ================== */
    public function index()
    {
        $products = Product::with(['category', 'variants.size', 'variants.color'])
            ->where('status', 1)   // 👉 chỉ lấy sản phẩm đang hiển thị
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admins.product.index', compact('products'));
    }


    public function show(Product $product)
    {
        $product->load(['category', 'variants.size', 'variants.color']);
        return view('admins.product.show', compact('product'));
    }


    /** ================== CREATE ================== */
    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admins.product.create', compact('categories', 'colors', 'sizes'));
    }

  /** ================== STORE ================== */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images'      => 'nullable|array|max:20',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $makeSku = function () {
            do {
                $sku = 'SKU-' . Str::upper(Str::random(8));
            } while (ProductVariant::where('sku', $sku)->exists());
            return $sku;
        };

        // Ảnh chính
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        // Album ảnh
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('products', 'public');
            }
        }

        // Tạo sản phẩm
        $product = Product::create([
            'code'        => 'PRD_' . Str::upper(Str::random(6)),
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? '',
            'status'      => $validated['status'],
            'category_id' => $validated['category_id'],
            'image'       => $image,
            'images'      => json_encode($images),
        ]);

        // Lưu biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $variantData = [
                    'product_id' => $product->id,
                    'size_id'    => $variant['size_id'] ?? null,
                    'color_id'   => $variant['color_id'] ?? null,
                    'sku'        => !empty($variant['sku']) ? $variant['sku'] : $makeSku(),
                    'price'      => max(0, $variant['price'] ?? 0),
                    'sale_price' => max(0, $variant['sale_price'] ?? 0),
                    'stock'      => $variant['stock'] ?? 0,
                ];

                if (isset($variant['image']) && $variant['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantData['image'] = $variant['image']->store('variants', 'public');
                }

                ProductVariant::create($variantData);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }


    /** ================== EDIT ================== */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        $product->load('variants.color', 'variants.size');

        return view('admins.product.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    /** ================== UPDATE ================== */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $makeSku = function () {
            do {
                $sku = 'SKU-' . Str::upper(Str::random(8));
            } while (ProductVariant::where('sku', $sku)->exists());
            return $sku;
        };

        // Ảnh chính
        $image = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $image = $request->file('image')->store('products', 'public');
        }

        // Album ảnh
        $images = is_string($product->images) ? json_decode($product->images, true) : ($product->images ?? []);
        if ($request->hasFile('images')) {
            foreach ($images as $oldImg) {
                Storage::disk('public')->delete($oldImg);
            }
            $images = [];
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('products', 'public');
            }
        }

        // Update product
        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? '',
            'status'      => $validated['status'],
            'category_id' => $validated['category_id'],
            'image'       => $image,
            'images'      => json_encode($images),
        ]);

        // Xóa biến thể cũ + ảnh
        foreach ($product->variants as $variant) {
            if ($variant->image) Storage::disk('public')->delete($variant->image);
        }
        $product->variants()->delete();

        // Thêm biến thể mới
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $variantImage = null;
                if (isset($variant['image']) && $variant['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImage = $variant['image']->store('variants', 'public');
                }

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id'    => $variant['size_id'] ?? null,
                    'color_id'   => $variant['color_id'] ?? null,
                    'sku'        => !empty($variant['sku']) ? $variant['sku'] : $makeSku(),
                    'price'      => max(0, $variant['price'] ?? 0),
                    'sale_price' => max(0, $variant['sale_price'] ?? 0),
                    'stock'      => $variant['stock'] ?? 0,
                    'image'      => $variantImage,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    /** ================== DESTROY ================== */
    public function destroy(Product $product)
    {
        // Xoá ảnh chính
        if ($product->image) Storage::disk('public')->delete($product->image);

        // Xoá album
        if ($product->images) {
            foreach (json_decode($product->images, true) as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        // Xoá biến thể & ảnh biến thể
        foreach ($product->variants as $variant) {
            if ($variant->image) Storage::disk('public')->delete($variant->image);
        }
        $product->variants()->delete();

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã bị xóa.');
    }

    // ProductController.php

    /**
     * Toggle trạng thái hiển thị/ẩn cho product
     * Nếu request AJAX -> trả về JSON { status: 0|1, hidden: true|false }
     * Nếu request normal -> redirect back
     */
    public function toggleStatus(Request $request, Product $product)
    {
        // toggle
        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => (int) $product->status,
                'hidden' => $product->status == 0,
                'message' => $product->status ? 'Sản phẩm đã được hiển thị' : 'Sản phẩm đã bị ẩn'
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái sản phẩm thành công!');
    }


    public function hidden()
    {
        $products = Product::with(['category', 'variants.size', 'variants.color'])
            ->where('status', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admins.product.hidden', compact('products'));
    }
}
