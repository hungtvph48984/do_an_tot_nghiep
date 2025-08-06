<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
{
    $products = Product::with(['variants.color', 'variants.size'])
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->limit(8)
        ->get();

    // Truyền biến $products sang view
    return view('clients.home.index', compact('products'));
}


}

