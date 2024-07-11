<?php
    use App\Models\Category;
    function getCategory(){
        return Category::orderBy('name', 'ASC')->where('showHome', 'Yes')->where('status', 1)->with('sub_categories')->orderBy('id', 'DESC')->get();
    }
?>
