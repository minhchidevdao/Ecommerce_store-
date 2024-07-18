<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Country;
use App\Models\Order;
use App\Models\customerAddress;
use App\Models\OrderItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function checkout(Request $request){
        if(Auth::check() == false){
            session(['url.intended' => session('url.intended',  route('front.checkout'))]);

            return redirect()->route('account.login');
        }
       if(Cart::count() == 0){
            return redirect()->route('front.home');
       }
       session()->forget('url.intended'); // xóa url đã lưu đi

       $countries = Country::orderBy('name', 'ASC')->get();
       $customerAddress = customerAddress::where('user_id',(Auth::user()->id))->first();
       return view('front-end.checkout', compact('countries', 'customerAddress'));
    }

    public function processCheckout(Request $request){
        // 1 - Apply validation
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'country' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fill in the missing fields',
                'errors'=> $validator->errors(),
            ]);
        }else{

            // 2 - lưu thông tin vào bảng customer_address

            $user = Auth::user();
            // tìm kiếm  xem có bản ghi nào có user_id == user_id của người đăng nhập hiện tại không,
            // nếu có bản ghi trùng thì cập nhật lại giá trị của bản ghi, nếu không trùng thì sẽ tạo mới bản ghi
            // với id đăng nhập hiện tại
            customerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'country_id' => $request->country,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,

                ]
            );

            // 3 - lưu thông tin vào bảng order
            if($request->payment_method == 'cod'){
                $subTotal = Cart::subtotal(2,'.','');
                $shipping = 0;
                $discount = 0;
                $grandTotal = $subTotal + $shipping;

                $order = new Order();
                $order->user_id = $user->id;
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->grand_total = $grandTotal;


                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->mobile = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartment = $request->apartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->order_note = $request->order_notes;
                $order->save();

            // 4 - lưu thông tin vào bảng item

                foreach(Cart::content() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->order_id =  $order->id;
                    $orderItem->product_id = $item->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->qty*$item->price;
                    $orderItem->save();

                }
                Session::flash('success', 'You have placed your order successfully. We will process and send the goods as soon as possible');
                return response()->json([
                    'status' => true,
                    'order_id'=> $order->id,
                    'message' => 'Order saved successfully'

                ]);

            }


        }


    }
    public function thankYou(){
        return view('front-end.thanks');
    }

}
