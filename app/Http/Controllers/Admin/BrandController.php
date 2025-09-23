<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Hiển thị form liên kết
    public function show($id)
{
    $brand = Brand::with('products')->findOrFail($id);
    return view('admins.brands.show', compact('brand'));
}
public function showLinkProducts($id)
{
    $brand = Brand::with('products')->findOrFail($id);
    $products = Product::with('category')->get(); // lấy danh sách sản phẩm kèm category

    return view('admins.brands.link-products', compact('brand', 'products'));
}

   

    // Lưu liên kết
    public function linkProducts(Request $request, $brandId)
    {
        $brand = Brand::findOrFail($brandId);
        $productIds = $request->input('product_ids', []);
        $brand->products()->sync($productIds);

        return redirect()->route('admin.brands.show', $brand->id)
            ->with('success', 'Liên kết sản phẩm thành công!');
    }
}
