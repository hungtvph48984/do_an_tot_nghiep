<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;    
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Category::where('is_active', true);

        // Tìm kiếm theo tên danh mục, không phân biệt chữ hoa chữ thường
        // Sử dụng DB::raw để chuyển đổi tên thành chữ thường trước khi so sánh
        if ($keyword) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower($keyword) . '%');
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);

        return view('admins.category.index', compact('categories', 'keyword'));
        // return view('admins.category.hidden', compact('categories', 'keyword'));
    }

    /**
     * Hiển thị form tạo danh mục mới.
     */
    public function create()
    {
        return view('admins.category.add');
    }

    /**
     * Lưu danh mục mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admins.category.edit', compact('category'));
    }

    /**
     * Cập nhật danh mục trong cơ sở dữ liệu.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate(['name' => 'required|string|max:255']);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    
    /**
     * Xóa danh mục khỏi cơ sở dữ liệu.
     */
    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Đảo trạng thái hoạt động của danh mục (ẩn/hiện).
     */
    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active; // Đảo trạng thái
        $category->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái danh mục thành công!');
    }

    // Hiển thị danh mục đã ẩn sang bảng mới
    public function hidden(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Category::where('is_active', false);

        if ($keyword) {
            $query->where(DB::raw('LOWER(name)'), 'like', strtolower($keyword) . '%');
        }


        $categories = $query->orderBy('id', 'desc')->paginate(10);

        return view('admins.category.hidden', compact('categories', 'keyword'));
    }



    
}

