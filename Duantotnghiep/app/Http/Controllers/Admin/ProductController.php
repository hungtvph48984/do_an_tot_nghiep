<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admins.product.index', compact('products'));
    }


    public function create()
    {
        $categories = \App\Models\Category::all(); // Lấy tất cả danh mục
        return view('admins.product.create', compact('categories'));
    }


    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants.*.name' => 'required|string',
            'variants.*.values' => 'required|array',
            'variants_prices' => 'required|array', // giá từng biến thể
            'variants_prices.*' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'category_id' => 'required|exists:categories,id',


        ]);

        // Lưu ảnh sản phẩm
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
        }

        // Tạo product với code tự động
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'images' => $images,
            'code' => 'auto_' . Str::random(6),
            'category_id' => $validated['category_id'], // thêm dòng này
            'status' => 1, // mặc định active

        ]);

        // Tạo biến thể
        $variants = $request->input('variants');
        $variantsPrices = $request->input('variants_prices');

        foreach ($variants as $index => $variant) {
            if (!empty($variant['name']) && !empty($variant['values'])) {
                foreach ($variant['values'] as $valueIndex => $value) {
                    $product->variants()->create([
                        'name' => $variant['name'],
                        'value' => $value,
                        'price' => $variantsPrices[$index][$valueIndex] ?? 0,
                        'attributes' => json_encode([
                            $variant['name'] => $value
                        ])
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo.');
    }
    public function edit(Product $product)
    {
        $options = $this->extractOptionsFromVariants($product->variants);
        return view('admin.products.edit', compact('product', 'options'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants.*.name' => 'required|string',
            'variants.*.values' => 'required|array',
            'variants_prices' => 'required|array',
            'variants_prices.*' => 'required|numeric|min:0',
        ]);

        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
        }
        if ($request->has('deleted_images')) {
            foreach ($request->deleted_images as $del) {
                $key = array_search($del, $images);
                if ($key !== false) {
                    Storage::disk('public')->delete($del);
                    unset($images[$key]);
                }
            }
            $images = array_values($images);
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'images' => $images,
        ]);

        $product->variants()->delete();
        $this->generateVariants($product, $request->input('variants'), $request->input('variants_prices'));

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }
        $product->variants()->delete();
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa.');
    }

    private function generateVariants(Product $product, array $options, array $prices)
    {
        $optionNames = array_column($options, 'name');
        $optionValues = array_column($options, 'values');
        $combinations = $this->cartesianProduct($optionValues);

        foreach ($combinations as $index => $combo) {
            $attributes = array_combine($optionNames, $combo);
            Variant::create([
                'product_id' => $product->id,
                'attributes' => $attributes,
                'price' => $prices[$index] ?? 0,
                'stock' => 0,
                'sku' => implode('-', $combo),
            ]);
        }
    }

    private function cartesianProduct($arrays)
    {
        $result = [[]];
        foreach ($arrays as $array) {
            $append = [];
            foreach ($result as $product) {
                foreach ($array as $item) {
                    $append[] = array_merge($product, [$item]);
                }
            }
            $result = $append;
        }
        return $result;
    }

    private function extractOptionsFromVariants($variants)
    {
        if ($variants->isEmpty()) return [];
        $firstAttributes = $variants->first()->attributes;
        $optionNames = array_keys($firstAttributes);
        $options = [];
        foreach ($optionNames as $index => $name) {
            $values = $variants->pluck('attributes.' . $name)->unique()->toArray();
            $options[] = ['name' => $name, 'values' => $values];
        }
        return $options;
    }
}
