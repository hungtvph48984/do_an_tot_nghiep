<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use App\Models\Color;

class AttributeController extends Controller
{
    public function index()
    {
        $sizes  = Size::orderBy('name')->paginate(10);
        $colors = Color::orderBy('name')->paginate(10);

        return view('admins.attributes.index', compact('sizes', 'colors'));
    }

    public function storeSize(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:sizes,name|regex:/^[A-Za-z]+$/',
        ]);
        Size::create(['name' => strtoupper($request->name)]);
        return back()->with('success','Thêm size thành công!');
    }

    public function updateSize(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:sizes,name,' . $size->id . '|regex:/^[A-Za-z]+$/',
        ]);
        $size->update(['name' => strtoupper($request->name)]);
        return back()->with('success','Cập nhật size thành công!');
    }

    public function destroySize(Size $size)
    {
        if ($size->variants()->exists()) {
            return back()->with('error','Không thể xóa size đã gán cho sản phẩm.');
        }
        $size->delete();
        return back()->with('success','Xóa size thành công!');
    }

    public function storeColor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:colors,name|regex:/^[A-Za-zÀ-ỹ\s]+$/',
            'code' => 'required|string|max:10',
        ]);
        Color::create($request->only('name','code'));
        return back()->with('success','Thêm màu thành công!');
    }

    public function updateColor(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:colors,name,' . $color->id . '|regex:/^[A-Za-zÀ-ỹ\s]+$/',
            'code' => 'required|string|max:10',
        ]);
        $color->update($request->only('name','code'));
        return back()->with('success','Cập nhật màu thành công!');
    }

    public function destroyColor(Color $color)
    {
        if ($color->variants()->exists()) {
            return back()->with('error','Không thể xóa màu đã gán cho sản phẩm.');
        }
        $color->delete();
        return back()->with('success','Xóa màu thành công!');
    }
}
