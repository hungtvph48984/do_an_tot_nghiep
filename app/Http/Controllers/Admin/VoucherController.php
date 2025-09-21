<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::paginate(10);
        return view('admins.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admins.vouchers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|unique:vouchers,code|max:255',
            'type'       => 'required|in:fixed,percent',
            'sale_price' => 'required|numeric|min:0',
            'min_order'  => 'required|numeric|min:0',
            'max_price'  => 'nullable|numeric|min:0',
            'quantity'   => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        Voucher::create($request->all());

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher được tạo thành công!');
    }

    public function show(Voucher $voucher)
    {
        return view('admins.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        return view('admins.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:fixed,percent',
            'sale_price' => 'required|numeric|min:0',
            'min_order'  => 'required|numeric|min:0',
            'max_price'  => 'nullable|numeric|min:0',
            'quantity'   => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        //  Không update code
        $voucher->update([
            'name'       => $request->name,
            'type'       => $request->type,
            'sale_price' => $request->sale_price,
            'min_order'  => $request->min_order,
            'max_price'  => $request->max_price,
            'quantity'   => $request->quantity,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher được cập nhật thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher được xóa thành công!');
    }

    // Nếu vẫn muốn chức năng generateCode riêng để khi tạo nhanh
    public function generateCode()
    {
        do {
            $code = 'VC' . strtoupper(Str::random(10));
        } while (Voucher::where('code', $code)->exists());

        return response()->json(['code' => $code]);
    }
}
