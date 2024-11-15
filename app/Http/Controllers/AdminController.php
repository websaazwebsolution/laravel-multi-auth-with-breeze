<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminController extends Controller
{
    //
    public function ShowLoginFrom()
    {
        return view('backend.superadmin.login');
    }
    public function Login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');
        
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            return redirect()->route('admin.dashboard');
        }
        
        return back()->with('message', 'Invalid credentials')->withInput();
        
    }
    public function dashboard(){
        return view('backend.admin.dashboard');
    }
}
