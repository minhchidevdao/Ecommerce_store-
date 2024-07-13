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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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
