<?php
    use App\Models\Category;
use App\Models\ProductImage;

    function getCategory(){
        return Category::orderBy('name', 'ASC')->where('showHome', 'Yes')->where('status', 1)->with('sub_categories')->orderBy('id', 'DESC')->get();
    }

    function productImage($productId){
        return ProductImage::where('product_id', $productId)->first();
    }
?>
