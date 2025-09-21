<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size; // nếu chưa có model Size thì có thể xoá dòng này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * (Tuỳ chọn) Trang tất cả sản phẩm
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->withMin('variants as min_price', 'price')
            ->withMax('variants as max_price', 'price')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Chuẩn hoá old_price nếu bạn chưa có compare_price
        $products->getCollection()->transform(function ($p) {
            $p->old_price = $p->max_price;
            return $p;
        });
        

        return view('clients.products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        
        // Lọc sản phẩm theo tên (sử dụng LIKE để tìm kiếm gần đúng)
        $products = Product::where('name', 'like', '%' . $searchTerm . '%')
            ->withMin('variants as min_price', 'price')
            ->withMax('variants as max_price', 'price')
            ->paginate(12);
        
        // Lấy tất cả các size
        $sizes = Size::all(); // Hoặc sử dụng cách khác nếu bạn có cách lấy sizes khác

        // Trả về view với sản phẩm tìm thấy, từ khóa tìm kiếm và sizes
        return view('clients.products.index', compact('products', 'searchTerm', 'sizes'));
    }




    public function categoryShow(Category $category, Request $request)
    {
        // Validate nhẹ
        $request->validate([
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'sizes'     => 'nullable|array',
            'sizes.*'   => 'integer',
            'sort'      => 'nullable|in:price_asc,price_desc,name_asc,name_desc,newest,oldest',
        ]);

        // Size cho sidebar (nếu chưa có bảng sizes thì truyền collect())
        $sizes = class_exists(Size::class)
            ? Size::query()->orderBy('name')->get()
            : collect();

        $query = Product::query()
            ->where('category_id', $category->id)
            ->withMin('variants as min_price', 'price')
            ->withMax('variants as max_price', 'price')
            ->withCount([
                'variants as sizes_count'  => fn($q) => $q->select(DB::raw('COUNT(DISTINCT size_id)')),
                'variants as colors_count' => fn($q) => $q->select(DB::raw('COUNT(DISTINCT color_id)')),
            ]);

        // Lọc giá theo variants.price
        $min = $request->integer('min_price');
        $max = $request->integer('max_price');
        if ($min || $max) {
            $query->whereHas('variants', function ($q) use ($min, $max) {
                if ($min) $q->where('price', '>=', $min);
                if ($max) $q->where('price', '<=', $max);
            });
        }

        // Lọc size (tham số: sizes[] là mảng id)
        $sizesFilter = (array) $request->input('sizes', []);
        if (!empty($sizesFilter)) {
            $query->whereHas('variants', fn($q) => $q->whereIn('size_id', $sizesFilter));
        }

        // Sắp xếp
        switch ($request->input('sort')) {
            case 'price_asc':  $query->orderBy('min_price', 'asc');  break;
            case 'price_desc': $query->orderBy('min_price', 'desc'); break;
            case 'name_asc':   $query->orderBy('name', 'asc');       break;
            case 'name_desc':  $query->orderBy('name', 'desc');      break;
            case 'newest':     $query->orderBy('created_at', 'desc');break;
            case 'oldest':     $query->orderBy('created_at', 'asc'); break;
            default:           $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(15)->withQueryString();

        // Nếu không có compare_price → tạm dùng max_price làm old_price cho view
        $products->getCollection()->transform(function ($p) {
            if (!isset($p->old_price)) {
                $p->old_price = $p->max_price;
            }
            return $p;
        });

        $allCategories = Category::query()
        ->where('is_active', 1) 
        ->orderBy('name')
        ->get();

        return view('clients.category.show', [
            'category' => $category,
            'products' => $products, // Paginator → links() OK
            'sizes'    => $sizes,
            'allCategories' => $allCategories,
        ]);

    }

    public function show($id)
    {
        return $this->details($id);
    }

    public function details($id)
    {
        // Lấy sản phẩm chi tiết
        $product = Product::with([
            'variants.color',
            'variants.size' => function ($query) {
                $query->orderBy('name', 'asc');
            },
            'category'
        ])->findOrFail($id);

        // Sắp xếp variant theo size
        if ($product->variants) {
            $product->variants = $product->variants
                ->sortBy(fn($variant) => optional($variant->size)->name)
                ->values();
        }

        // Lấy sản phẩm liên quan (cùng danh mục, khác id hiện tại)
        $relatedProducts = Product::query()
            ->withMin('variants as min_price', 'price')
            ->withMax('variants as max_price', 'price')
            ->withCount([
                'variants as sizes_count'  => fn($q) => $q->select(DB::raw('COUNT(DISTINCT size_id)')),
                'variants as colors_count' => fn($q) => $q->select(DB::raw('COUNT(DISTINCT color_id)')),
            ])
            ->where('category_id', $product->category_id)
            ->where('id', '<>', $product->id)
            ->take(12)
            ->get();

        // Chuẩn hoá old_price nếu chưa có compare_price
        $relatedProducts->transform(function ($p) {
            if (!isset($p->old_price)) {
                $p->old_price = $p->max_price;
            }
            return $p;
        });

        return view('clients.detail', compact('product', 'relatedProducts'));
    }


    /** API: lấy giá theo thuộc tính */
    public function getAttributePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'color_id'   => 'required|integer',
            'size_id'    => 'required|integer',
        ]);

        $variant = ProductVariant::query()
            ->where('product_id', $request->product_id)
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->first();

        if (!$variant) {
            return response()->json(['error' => 'Variant not found'], 404);
        }
        return response()->json($variant);
    }

    /** API: lấy biến thể theo id */
    public function getVariant(Request $request)
    {
        $request->validate(['variant_id' => 'required|integer']);

        $variant = ProductVariant::with(['product', 'size', 'color'])
            ->find($request->input('variant_id'));

        if (!$variant) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        return response()->json($variant);
    }

    /**
     * (Không cần nữa) — nếu bạn vẫn dùng filter cũ, đảm bảo cũng paginate và truyền $category
     */
    public function filter(Request $request)
    {
        $query = Product::with(['variants.size', 'variants.color']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->min_price && $request->max_price) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereBetween('price', [$request->min_price, $request->max_price]);
            });
        }

        if ($request->size_id) {
            $query->whereHas('variants', fn($q) => $q->whereIn('size_id', (array) $request->size_id));
        }

        $products = $query->paginate(12)->withQueryString();

        // Lấy category để view không lỗi
        $category = $request->category_id ? Category::find($request->category_id) : null;
        $sizes = class_exists(Size::class) ? Size::query()->orderBy('name')->get() : collect();

        return view('clients.category.show', compact('products', 'category', 'sizes'));
    }

    
    
}
