<?php

namespace App\Http\Controllers\frontend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravolt\Avatar\Facade as Avatar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(){

        return view('front-end.account.login');
    }
    public function register(){
        return view('front-end.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make( $request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if($validator->passes()){
            // tạo và lưu thông tin người dùng
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            // tạo avatar từ tên của người dùng
            $avatarPath = 'avatar-' . $user->id .'.png';
            Avatar::create($user->name)->save(public_path('img-avatar/avatar-user/' .$avatarPath));
            $user->avatar = $avatarPath;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Registered successfully'
            ]);


        }else{
            return response()->json([
                'status' => false,
                'errors'=> $validator->errors(),
            ]);
        }
    }
    public function authenticate( Request $request){
        $validator = Validator::make( $request->all(), [

            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if($validator->passes()){

            if(Auth::guard('web')->attempt(['email'=>$request->email, 'password'=>$request->password], $request->get('remember'))){
                Log::info('AuthController Authenticate:', [
                    'guard' => 'web',
                    'authenticated' => Auth::guard('web')->check()
                ]);

                $request->session()->regenerate(); // tái tạo lại 1 ID session cho phiên

                if(session()->has('url.intended')){
                   return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');

            }else{

                return redirect()->route('account.login')->withInput($request->only('email'))->with('error', 'Email or password not correct!');
            }
        }else{
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }


    public function profile(){

        return view('front-end.account.profile');
    }
    public function logout(Request $request){
        Log::info('User Logout Initiated');
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('User Logout Completed');

        return redirect()->route('account.login')->with('success', 'You successfullt logout ');
    }


}
