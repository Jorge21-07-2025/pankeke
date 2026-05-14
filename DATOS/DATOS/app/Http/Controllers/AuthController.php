<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cookie;
use App\Models\AdoptionRequest;
use App\Models\Pet;

class AuthController extends Controller
{
    public function home(Request $request)
    {
        return view('home', ['logueado' => Auth::check()]);
    }

    public function registrar(Request $request) {

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',

        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.unique' => 'El correo ya fue registrado',
            'email.required' => 'El campo correo es obligatorio',
            'email.email' => 'Ingresa un formato de correo válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);
        $request->session()->put('user_id', $user->id);

        return redirect('dashboard');
    }

    public function showLogin(Request $request)
    {
        if (Auth::check() || $request->session()->get('user_id')) {
            return redirect('dashboard');
        }

        return view('home', ['logueado' => Auth::check()]);
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
        $remember = (bool) $request->input('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            $userId = Auth::id();
            $request->session()->put('user_id', $userId);

            if ($remember) {
                Cookie::queue(Cookie::forever('remember_user_id', $userId));
            } else {
                Cookie::queue(Cookie::forget('remember_user_id'));
            }

            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($key, 60);

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
        $solicitudesCount = AdoptionRequest::whereHas('pet', function ($q) {
            $q->where('user_id', auth()->id());
        })->where('status', 'en_proceso')->count();

        $rescuedThisYear = Pet::whereYear('created_at', now()->year)->count();
        $adoptedCount = AdoptionRequest::where('status', 'aprobado')->count();

        return view('dashboard', compact('users', 'solicitudesCount', 'rescuedThisYear', 'adoptedCount'));
    }

    public function perfil()
    {
        $user = auth()->user()->load(['pets', 'adoptionRequests.pet']);
        return view('perfil', compact('user'));
    }

    public function perfilActualizar(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? $user->phone;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = \Illuminate\Support\Facades\Storage::url($path);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente');
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
