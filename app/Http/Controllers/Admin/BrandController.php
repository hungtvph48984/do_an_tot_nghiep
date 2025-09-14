<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $query = Brand::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $brands = $query->paginate(10)->withQueryString();

        return view('admins.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean'
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }

        Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Nhãn hàng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
{
    // Kiểm tra xem relationship có tồn tại không
    try {
        $brand->load('products');
    } catch (\Exception $e) {
        // Log lỗi hoặc xử lý
        $brand->products = collect(); // Tạo collection rỗng
    }
    
    return view('admins.brands.show', compact('brand'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admins.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'status' => 'boolean'
        ]);

        $data = $request->except('logo');
        $data['slug'] = Str::slug($request->name);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            
            $logoPath = $request->file('logo')->store('brands', 'public');
            $data['logo'] = $logoPath;
        }

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Nhãn hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Không thể xóa nhãn hàng này vì đang có sản phẩm liên kết!');
        }

        // Delete logo file
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Nhãn hàng đã được xóa thành công!');
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:brands,id'
        ]);

        $action = $request->action;
        $ids = $request->selected_ids;

        switch ($action) {
            case 'delete':
                // Check if any brand has products
                $brandsWithProducts = Brand::whereIn('id', $ids)
                    ->whereHas('products')
                    ->count();

                if ($brandsWithProducts > 0) {
                    return redirect()
                        ->back()
                        ->with('error', 'Không thể xóa các nhãn hàng có sản phẩm liên kết!');
                }

                $brands = Brand::whereIn('id', $ids)->get();
                foreach ($brands as $brand) {
                    if ($brand->logo) {
                        Storage::disk('public')->delete($brand->logo);
                    }
                    $brand->delete();
                }
                
                $message = 'Đã xóa ' . count($brands) . ' nhãn hàng thành công!';
                break;

            case 'activate':
                Brand::whereIn('id', $ids)->update(['status' => true]);
                $message = 'Đã kích hoạt ' . count($ids) . ' nhãn hàng thành công!';
                break;

            case 'deactivate':
                Brand::whereIn('id', $ids)->update(['status' => false]);
                $message = 'Đã vô hiệu hóa ' . count($ids) . ' nhãn hàng thành công!';
                break;
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }
}