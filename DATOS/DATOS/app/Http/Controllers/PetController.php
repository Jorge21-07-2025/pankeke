<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            ['icon' => '✓', 'label' => 'Vacunado', 'value' => $pet->vacunado],
            ['icon' => '✂️', 'label' => 'Castrado', 'value' => $pet->castrado],
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|in:Perro,Gato',
            'breed' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:50',
            'age_unit' => 'required|in:años,meses',
            'gender' => 'required|in:Macho,Hembra',
            'city' => 'required|string|max:255',
            'size' => 'required|in:Pequeño,Mediano,Grande',
            'weight' => 'nullable|numeric|min:0|max:200',
            'color' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'vacunado' => 'boolean',
            'castrado' => 'boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $pet = new Pet();
        $pet->name = $validated['name'];
        $pet->species = $validated['species'];
        $pet->breed = $validated['breed'];
        $pet->age = $validated['age'];
        $pet->age_unit = $validated['age_unit'];
        $pet->gender = $validated['gender'];
        $pet->city = $validated['city'];
        $pet->size = $validated['size'];
        $pet->weight = $validated['weight'] ? $validated['weight'] . ' kg' : null;
        $pet->color = $validated['color'] ?? null;
        $pet->description = $validated['description'] ?? null;
        $pet->vacunado = $request->boolean('vacunado');
        $pet->castrado = $request->boolean('castrado');
        $pet->status = 'Disponible';
        $pet->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pets', 'public');
            $pet->image = Storage::url($path);
        }

        $pet->save();

        return response()->json([
            'success' => true,
            'message' => 'Mascota publicada con éxito',
            'pet' => $pet,
        ]);
    }
}
