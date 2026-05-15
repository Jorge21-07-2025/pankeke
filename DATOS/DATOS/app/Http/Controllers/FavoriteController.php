<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function organizacion(User $user)
    {
        if (!in_array($user->role, ['refugio', 'veterinaria'])) {
            abort(404);
        }

        $pets = Pet::with('shelter')
            ->where('user_id', $user->id)
            ->where('status', 'disponible')
            ->latest()
            ->get();

        return view('organizacion', compact('user', 'pets'));
    }

    public function page()
    {
        $favorites = Favorite::with('pet.shelter')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $user = Auth::user();

        return view('favoritos', compact('favorites', 'user'));
    }

    public function index()
    {
        $favorites = Favorite::with('pet')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json(['favorites' => $favorites]);
    }

    public function toggle(Pet $pet)
    {
        $existing = Favorite::where('user_id', auth()->id())
            ->where('pet_id', $pet->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['favorited' => false]);
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'pet_id' => $pet->id,
        ]);

        return response()->json(['favorited' => true]);
    }
}
