<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $featuredProduct = Product::orderBy('title', 'ASC')->with('product_images')->where('is_featured', 'Yes')->where('status', 1)->take(8)->get();
        $latestProduct = Product::where('status', 1)->with('product_images')->orderBy('id', 'DESC')->take(8)->get();
        // dd($latestProduct);
        return view('front-end.home', compact('featuredProduct', 'latestProduct'));
    }
}
