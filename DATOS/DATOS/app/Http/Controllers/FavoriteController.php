<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
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
