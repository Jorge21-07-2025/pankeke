<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use App\Models\EmergencyAssignment;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:2000',
            'location' => 'nullable|string|max:500',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
        ]);

        $emergency = Emergency::create([
            'user_id' => auth()->id(),
            'description' => $validated['description'],
            'location' => $validated['location'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'status' => 'pendiente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Emergencia reportada. Los rescatistas de tu zona están siendo notificados.',
            'emergency' => $emergency,
        ]);
    }

    public function activas()
    {
        $emergencies = Emergency::with(['user', 'assignments.user'])
            ->where('status', '!=', 'resuelto')
            ->latest()
            ->get();

        return response()->json(['emergencies' => $emergencies]);
    }

    public function asignar(Request $request, Emergency $emergency)
    {
        if (auth()->user()->role !== 'rescatista') {
            return response()->json(['success' => false, 'message' => 'Solo los rescatistas pueden asignarse'], 403);
        }

        $exists = EmergencyAssignment::where('emergency_id', $emergency->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Ya te asignaste a esta emergencia'], 422);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
        ]);

        EmergencyAssignment::create([
            'emergency_id' => $emergency->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'] ?? null,
            'status' => 'en_camino',
        ]);

        if ($emergency->status === 'pendiente') {
            $emergency->status = 'en_curso';
            $emergency->save();
        }

        return response()->json([
            'success' => true,
            'message' => '¡Gracias por ayudar! Ya vas en camino.',
        ]);
    }

    public function resolver(Emergency $emergency)
    {
        $isReporter = $emergency->user_id === auth()->id();
        $isAssigned = EmergencyAssignment::where('emergency_id', $emergency->id)
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isReporter && !$isAssigned && auth()->user()->role !== 'rescatista') {
            return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
        }

        $emergency->status = 'resuelto';
        $emergency->save();

        return response()->json([
            'success' => true,
            'message' => 'Emergencia marcada como resuelta. ¡Gracias por tu ayuda!',
        ]);
    }
}
