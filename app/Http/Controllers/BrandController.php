<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Hiển thị danh sách brand
    public function index()
    {
        $allCategories = Category::orderBy('name')->get();
        
        $brands = Brand::where('status', 1)
            ->withCount(['products' => function ($query) {
                $query->where('status', 1);
            }])
            ->orderBy('name')
            ->paginate(12);
            
        return view('clients.brand.index', compact('brands', 'allCategories'));
    }

    // Hiển thị chi tiết brand
    public function show($id)
    {
        $brand = Brand::with(['products' => function ($query) {
                $query->where('status', 1);
            }])
            ->findOrFail($id);

        $allCategories = Category::orderBy('name')->get();

        return view('clients.brand.show', compact('brand', 'allCategories'));
    }
}
