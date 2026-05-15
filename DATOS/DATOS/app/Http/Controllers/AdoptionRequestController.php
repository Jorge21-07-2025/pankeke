<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdoptionRequestController extends Controller
{
    public function store(Request $request, $petId)
    {
        $pet = Pet::findOrFail($petId);

        if ($pet->user_id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes solicitar adoptar tu propia mascota',
            ], 422);
        }

        $exists = AdoptionRequest::where('user_id', auth()->id())
            ->where('pet_id', $petId)
            ->where('status', 'en_proceso')
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Ya enviaste una solicitud para esta mascota',
            ], 422);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
        ]);

        $adoption = AdoptionRequest::create([
            'user_id' => auth()->id(),
            'pet_id' => $petId,
            'message' => $validated['message'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'status' => 'en_proceso',
        ]);

        try {
            $ownerEmail = $pet->user?->email;
            if ($ownerEmail) {
                Mail::raw(
                    "¡Hola! Alguien quiere adoptar a {$pet->name} 🐾\n\n" .
                    "De: " . auth()->user()->name . "\n" .
                    "Email: " . auth()->user()->email . "\n" .
                    "Teléfono: " . ($validated['phone'] ?? 'No especificado') . "\n" .
                    "Mensaje: " . ($validated['message'] ?? 'Sin mensaje') . "\n\n" .
                    "Inicia sesión para ver más detalles.",
                    function ($message) use ($ownerEmail, $pet) {
                        $message->to($ownerEmail)
                            ->subject("🐾 Solicitud de adopción para {$pet->name}");
                    }
                );
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar correo de solicitud de adopción: ' . $e->getMessage());
        }

        $ownerPhone = $pet->phone;

        $contactMessage = "Solicitud enviada a {$pet->name} 🐾";
        if ($ownerPhone) {
            $contactMessage .= "\n\n📞 Contacta al dueño: {$ownerPhone}";
        }

        return response()->json([
            'success' => true,
            'message' => $contactMessage,
            'owner_phone' => $ownerPhone,
        ]);
    }

    public function update(Request $request, $id)
    {
        $adoption = AdoptionRequest::with('pet')->findOrFail($id);

        if ($adoption->pet->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:aprobado,rechazado',
        ]);

        $adoption->status = $validated['status'];
        $adoption->save();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud ' . ($validated['status'] === 'aprobado' ? 'aprobada' : 'rechazada') . ' con éxito',
        ]);
    }

    public function mine()
    {
        $requests = AdoptionRequest::with(['pet', 'user'])
            ->whereHas('pet', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return response()->json(['requests' => $requests]);
    }
}
