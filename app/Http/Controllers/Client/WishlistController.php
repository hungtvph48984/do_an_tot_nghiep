<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = session('wishlist', []);
        return view('clients.wishlist.index', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $wishlist = session()->get('wishlist', []);
        $productId = $request->product_id;

        if (isset($wishlist[$productId])) {
            unset($wishlist[$productId]);
            $action = 'removed';
        } else {
            $product = Product::findOrFail($productId);
            $wishlist[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->lowest_price ?? 0,
                'image' => $product->image,
            ];
            $action = 'added';
        }

        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'action' => $action,
            'count' => count($wishlist)
        ]);
    }


    public function remove(Request $request)
    {
        $wishlist = session()->get('wishlist', []);
        unset($wishlist[$request->product_id]);
        session()->put('wishlist', $wishlist);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.'
        ]);
    }



}
