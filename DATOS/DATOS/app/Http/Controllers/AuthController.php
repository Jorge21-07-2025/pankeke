<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\AdoptionRequest;

class AuthController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function registrar(Request $request) {
    
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',

     ], [
                'email.unique' => 'El correo ya fue registrado',
                'email.required' => 'El campo correo es obligatorio',
                'email.email' => 'Ingresa un formato de correo válido',
            
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Usuario registrado con éxito!');
    }

    public function login(Request $request)
    {
        $key = 'login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Demasiados intentos. Intenta de nuevo en $seconds segundos.",
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);
            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'Usuario o contraseña incorrectos',
        ]);
    }

    public function dashboard()
    {
        $users = User::all();
        $solicitudesCount = AdoptionRequest::whereHas('pet', function ($q) {
            $q->where('user_id', auth()->id());
        })->where('status', 'en_proceso')->count();
        return view('dashboard', compact('users', 'solicitudesCount'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
