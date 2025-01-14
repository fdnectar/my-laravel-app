<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function homePage() {
        return view('front.pages.home');
    }

    public function shoppingCartView() {
        return view('front.pages.cart');
    }

    public function productDetails(Request $request) {
        $product_id = $request->id;
        $product = Product::findOrFail($product_id);

        $data = [
            'product' => $product
        ];
        return view('front.pages.product-details', $data);
    }
}
