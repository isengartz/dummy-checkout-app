<?php

namespace App\Http\Controllers;

use App\Models\Product;


class ProductController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::with('brand')->get();
        return view('index', [
            'products' => $products
        ]);
    }
}
