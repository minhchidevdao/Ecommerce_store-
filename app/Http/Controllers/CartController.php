<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request){

        $product = Product::with('product_images')->find($request->id);
        if( $product == null){
            return response()->json([
                'status' => true,
                'message' => 'Product not found'
            ]);
        }

        // nếu các phần tử trong cart > 0 thì thực hiện if
        if(Cart::count() > 0){
            // sản phẩm tồn tain trong cart
            // nếu kiểm tra product đã có trong giỏ hàng
            // thì trả về tin nhắn:  product already in you cart
            // nếu sản phẩm chưa tồn tại trong giỏ hàng, hãy thêm sản phẩm vào giỏ hàng

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            // kiểm tra xem đã có sản phẩm nào trong cart trùng với sản phẩm sắp được thêm không
            foreach( $cartContent as $item){
                if($item -> id == $product->id){
                    $productAlreadyExist = true;
                }
            }

            // nếu sản phẩm này chưa trùng với sản phẩm nào trong giỏ hàng thì thêm vào
            if($productAlreadyExist == false){
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                $status = true;
                $message = $product->title. " added in cart";
            } else{
                $status = false;
                $message = $product->title. " Already added in cart";
            }

        }else{

            // [productImage] : nếu sản phẩm có hình ảnh thì lấy hình ảnh đầu tiên của sản phẩm
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = $product->title.' added in cart';
        }

        return response()->json([
            'status' => $status,
            'message' =>  $message,
        ]);
    }
    public function cart(){
        $cartContent = Cart::content();
        // dd(Cart::content());
        return view('front-end.cart', compact('cartContent'));
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $newQty = $request->qty;

        $itemInfo = Cart::get($rowId); // lấy rowId của sản phẩm sau đó từ rowId lấy ra id sản phẩm
        $product = Product::find($itemInfo->id);
        // check qty available stock
        if($product->track_qty == 'Yes'){

            if($product->qty >= $newQty){
                Cart::update($rowId, $newQty);
                $message = 'Cart updated successfully';
                $status = true;
                Session::flash('success', 'Cart updated successfully');


            }else{

                $message = 'Request quanty '.$newQty.' not available in stock';
                $status = false;
                Session::flash('error', $message);
            }
        }else{
            Cart::update($rowId, $newQty);
            $message = 'Cart updated successfully';
            $status = true;
            Session::flash('success', 'Cart updated successfully');


        }


        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function deleteCart(Request $request){

        $itemInfo = Cart::get($request->rowId);

        if( $itemInfo == null) {
            $errorMessage = 'Product not found in cart';
            Session::flash('error',  $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);

        }

        $successMessage = 'Product removed from cart successfully';
        Cart::remove($request->rowId);
        Session::flash('success',  $successMessage);
        return response()->json([
            'status' => true,
            'message' => $successMessage,
        ]);

    }
}
