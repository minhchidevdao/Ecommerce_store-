<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\ShippingController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\frontend\AuthController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\FrontController;
use App\Http\Controllers\frontend\ShopController;


// Route::get('/', function () {
//     return view('welcome');
// });
// Front-end Route

Route::get('/', [FrontController::class, 'index'])->name('front.home');
Route::get('product/{slug}', [ShopController::class, 'product'])->name('shop.product');
Route::get('shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class, 'index'])->name('front.shop');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('delete-cart', [CartController::class, 'deleteCart'])->name('front.deleteCart');
Route::get('checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::post('process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('thank/{order_id}', [CartController::class, 'thankYou'])->name('front.thankyou');
Route::post('checkout-getordersummery', [CartController::class, 'getOrderSummery'])->name('front.getOrderSummery');



// Authentication User
Route::prefix('/account')->group(function(){
    Route::middleware('guest')->group( function(){ // gọi đến middleware guest để kiểm tra xem các route này đã đăng nhập hay chưa, nếu như đã đăng nhập rồi thì sẽ bị chuyển hướng sang route khác ở phần guest
        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');

    });
    Route::middleware('auth')->group(function(){
        // Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');

    });

});



// admin authentication
Route::prefix('/admin')->group(function () {
    Route::middleware('admin.guest')->group(function () {

        Route::get('/', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    });


    //
    Route::middleware('admin.auth')->group(function () {
        Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

        // Category Routes
        Route::get('/category', [CategoryController::class, 'index'])->name('admin.categories');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/category/create', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/category/edit/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');

        Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Sub_category Routes
        Route::get('/sub_category', [SubCategoryController::class, 'show'])->name('sub_category.show');
        Route::get('/sub_category/create', [SubCategoryController::class, 'create'])->name('sub_category.create');
        Route::post('/sub_category/create', [SubCategoryController::class, 'store'])->name('sub_category.store');
        Route::get('/sub_category/edit/{id}', [SubCategoryController::class, 'edit'])->name('sub_category.edit');
        Route::put('/sub_category/update/{id}', [SubCategoryController::class, 'update'])->name('sub_category.update');
        Route::delete('/sub_category/delete/{id}', [SubCategoryController::class, 'destroy'])->name('sub_category.destroy');

        // Brands Routes
        Route::get('/brand', [BrandController::class, 'show'])->name('brand.show');
        Route::get('/brand/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/brand/create', [BrandController::class, 'store'])->name('brand.store');
        Route::get('/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::put('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::delete('/brand/delete/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

        // Product Routes
        Route::get('/product', [ProductController::class, 'index']) -> name('product.index');
        Route::get('/product/create', [ProductController::class, 'create']) -> name('product.create');
        Route::post('/product/store', [ProductController::class, 'store']) -> name('product.store');
        Route::get('/product/edit/{id}', [ProductController::class, 'edit']) -> name('product.edit');
        Route::put('/product/edit/{id}', [ProductController::class, 'update']) -> name('product.update');
        Route::get('/product/profile{id}', [ProductController::class, 'show']) -> name('product.show');
        Route::post('/product/store', [ProductController::class, 'store']) -> name('product.store');
        Route::delete('/product/delete/{id}', [ProductController::class, 'destroy']) -> name('product.delete');
        Route::get('/get-products', [ProductController::class, 'getProduct'])->name('product.getProduct');

        // Shipping Routes
        // Route::get('/shipping', [ShippingController::class, 'show'])->name('shipping.show');
        Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping-create', [ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping-edit/{id}', [ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping-delete/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');


        // product-subcategorie
        Route::get('/product-subcategories', [ProductSubCategoryController::class, 'create']) -> name('product-subcategories');

        //product-image
        Route::post('/product-image/update', [ProductImageController::class, 'update']) -> name('product-image.update');
        Route::delete('/product-image', [ProductImageController::class, 'destroy']) -> name('product-image.destroy');

        //temp-image.create
        Route::post('/upload-temp-image', [TempImagesController::class, 'store'])->name('temp-images.create');

        Route::get('/getSlug', function (Request $request) {
            $slug = '';

            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');

        // Logout Route
        Route::get('logout',  [HomeController::class, 'logout'])->middleware('auth')->name('admin.logout');
    });
});
