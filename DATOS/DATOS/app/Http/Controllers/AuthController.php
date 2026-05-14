<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function home(Request $request)
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }

        $rememberId = $request->cookie('remember_user_id');

        if ($rememberId) {
            $user = User::find($rememberId);

            if ($user) {
                Auth::login($user);
                $request->session()->put('user_id', $user->id);

                return redirect('dashboard');
            }

            Cookie::queue(Cookie::forget('remember_user_id'));
        }

        return view('home');
    }

    public function registrar(Request $request) {

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',

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

    public function showLogin(Request $request)
    {
        if (Auth::check() || $request->session()->get('user_id')) {
            return redirect('dashboard');
        }

        return view('home');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = (bool) $request->input('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $userId = Auth::id();
            $request->session()->put('user_id', $userId);

            if ($remember) {
                Cookie::queue(Cookie::forever('remember_user_id', $userId));
            } else {
                Cookie::queue(Cookie::forget('remember_user_id'));
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Usuario o contraseña incorrectos',
        ]);
    }

    public function dashboard(Request $request)
    {
        if (! Auth::check()) {
            $rememberId = $request->cookie('remember_user_id');

            if ($rememberId) {
                $user = User::find($rememberId);

                if ($user) {
                    Auth::login($user);
                    $request->session()->put('user_id', $user->id);
                } else {
                    Cookie::queue(Cookie::forget('remember_user_id'));
                    return redirect('/');
                }
            } else {
                return redirect('/');
            }
        }

        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('user_id');
        Cookie::queue(Cookie::forget('remember_user_id'));

        return redirect('/');
    }
}
