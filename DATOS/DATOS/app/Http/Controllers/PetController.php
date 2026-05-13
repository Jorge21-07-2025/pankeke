<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with('shelter')->where('status', 'disponible')->get();
        return response()->json(['pets' => $pets]);
    }

    public function show($id)
    {
        $pet = Pet::with('shelter')->findOrFail($id);
        return view('pet-detail', compact('pet'));
    }

    public function data($id)
    {
        $pet = Pet::with('shelter')->findOrFail($id);

        $traits = [
            ['icon' => '✓', 'label' => 'Vacunado', 'value' => true],
            ['icon' => '✂️', 'label' => 'Castrado', 'value' => $pet->gender === 'Macho' ? true : false],
            ['icon' => '😊', 'label' => 'Sociable', 'value' => true],
            ['icon' => '🎓', 'label' => 'Entrenado', 'value' => false],
        ];

        return response()->json([
            'pet' => array_merge($pet->toArray(), [
                'traits' => $traits,
                'shelter' => $pet->shelter?->name ?? 'No especificado',
            ]),
        ]);
    }
}
