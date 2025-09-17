<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Size;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $id)
    {
        $category = Category::where('is_active', true)->findOrFail($id);

        $query = $category->products()->with('variants.size');

        // 🔹 Lọc theo giá trong product_variants
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->min_price ?? 0;
            $maxPrice = $request->max_price ?? PHP_INT_MAX;

            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [$minPrice, $maxPrice]);
            });
        }

        // 🔹 Lọc theo size
        if ($request->has('sizes')) {
            $sizeIds = $request->sizes;
            $query->whereHas('variants', function ($q) use ($sizeIds) {
                $q->whereIn('size_id', $sizeIds);
            });
        }

        // 🔹 Sắp xếp
        if ($request->sort) {
            switch ($request->sort) {
                case 'price_asc':
                    // Sắp xếp theo giá thấp nhất trong variants
                    $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                    break;

                case 'price_desc':
                    $query->withMin('variants', 'price')->orderBy('variants_min_price', 'desc');
                    break;

                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;

                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;

                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        $products = $query->get();

        // Lấy danh sách size để hiển thị filter bên sidebar
        $sizes = Size::all();

        return view('clients.category.show', compact('category', 'products', 'sizes'));
    }
}
