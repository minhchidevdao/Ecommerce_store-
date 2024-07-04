<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::orderBy('name', 'ASC')->get();
        $brand = Brand::orderBy('name', 'ASC')->get();

        return view('admin.products.create', compact('category', 'brand'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required:numeric',
            'is_featured' => 'required|in:Yes,No'

        ];
        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules = ['qty' => 'required:numeric'];
        }

        $validator = Validator::make($request->all(), $rules);
        if( $validator->passes()){
            // $product = new Product();
            // $product->title = $request->title;
            // $product->slug = $request->slug;
            // $product->description = $request->description;
            // $product->sku = $request->sku;
            // $product->category_id = $request->category;
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
