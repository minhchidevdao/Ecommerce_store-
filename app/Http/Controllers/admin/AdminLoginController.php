<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AdminLoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);


        if ($validator->passes()) {
            if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {

                $request->session()->regenerate(); // tái tạo lại 1 ID session cho phiên

                $admin = Auth::guard('admin')->user();

                if ($admin->role == 1) {
                    return redirect()->route('admin.dashboard')->with('success', 'welcome to dashboard');
                } else {
                    return redirect()->route('admin.login')->with('error', 'You are not authorized to access admin panel');
                }
            } else {
                return redirect()->route('admin.login')->with('error', 'email or password fail');
            }
        } else {
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
}
