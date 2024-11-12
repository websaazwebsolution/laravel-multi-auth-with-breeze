<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeacherController extends Controller
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
    public function ShowRegisterForm()
    {
        return view('backend.teacher.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Teacher::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('teacher')->login($teacher);

        // event(new Registered( $teacher));



        return redirect(route('teacher.dashboard', absolute: false));

    }

    public function dashboard(){
        return view('backend.teacher.dashboard');
    }
    /**
     * Display the specified resource.
     */
    public function ShowLoginForm(Request $reuqest )
    {
        return view('backend.teacher.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');
        
        if (Auth::guard('teacher')->attempt($credentials, $remember)) {
            return redirect()->route('teacher.dashboard');
        }
        
        return back()->with('message', 'Invalid credentials')->withInput();
        

    }   

    public function logout(Request $request){
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('teacher.login'));
    }
}