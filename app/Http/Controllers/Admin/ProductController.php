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
        $products = Product::with(['category','variants.size','variants.color'])
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
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

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
            'price'       => $validated['price'] ?? 0,
        ]);

        // Lưu biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                $variantData = [
                    'product_id' => $product->id,
                    'size_id'    => $variant['size_id'] ?? null,
                    'color_id'   => $variant['color_id'] ?? null,
                    'sku'        => $variant['sku'] ?? null,
                    'price'      => $variant['price'] ?? 0,
                    'sale_price' => $variant['sale_price'] ?? 0,
                    'stock'      => $variant['stock'] ?? 0,
                ];

                // Ảnh biến thể
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

        $product->load('variants.color','variants.size');

        return view('admins.product.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    /** ================== UPDATE ================== */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric|min:0',
            'status'      => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        /* ================== ẢNH ================== */
        // 1. Ảnh chính
        $image = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $image = $request->file('image')->store('products', 'public');
        }
    
        // 2. Album ảnh
        $images = $product->images ? json_decode($product->images, true) : [];
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ
            foreach ($images as $oldImg) {
                Storage::disk('public')->delete($oldImg);
            }
            $images = [];
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('products', 'public');
            }
        }
    
        /* ================== UPDATE SẢN PHẨM ================== */
        $product->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? '',
            'price'       => $validated['price'] ?? 0,
            'status'      => $validated['status'],
            'category_id' => $validated['category_id'],
            'image'       => $image,
            'images'      => json_encode($images),
        ]);
    
        /* ================== CẬP NHẬT BIẾN THỂ ================== */
        // Xóa toàn bộ biến thể cũ + ảnh
        foreach ($product->variants as $variant) {
            if ($variant->image) Storage::disk('public')->delete($variant->image);
        }
        $product->variants()->delete();
    
        // Thêm mới biến thể từ form
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
                    'sku'        => $variant['sku'] ?? null,
                    'price'      => $variant['price'] ?? 0,
                    'sale_price' => $variant['sale_price'] ?? 0,
                    'stock'      => $variant['stock'] ?? 0,
                    'image'      => $variantImage,
                ]);
            }
        }
    
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật!');
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
}
