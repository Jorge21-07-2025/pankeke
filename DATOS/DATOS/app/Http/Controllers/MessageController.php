<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Pet;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request, Pet $pet)
    {
        if ($pet->user_id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No puedes enviarte mensajes a ti mismo'], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $pet->user_id,
            'pet_id' => $pet->id,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mensaje enviado con éxito',
        ]);
    }

    public function conversations()
    {
        $userId = auth()->id();

        $messages = Message::with(['fromUser', 'pet'])
            ->where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->latest()
            ->get();

        $conversations = [];
        foreach ($messages as $msg) {
            $otherId = $msg->from_user_id === $userId ? $msg->to_user_id : $msg->from_user_id;
            $key = $otherId . '-' . $msg->pet_id;
            if (!isset($conversations[$key])) {
                $conversations[$key] = [
                    'user' => $msg->from_user_id === $userId ? $msg->toUser : $msg->fromUser,
                    'pet' => $msg->pet,
                    'last_message' => $msg->message,
                    'last_time' => $msg->created_at,
                    'unread' => $msg->to_user_id === $userId && !$msg->read,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'conversations' => array_values($conversations),
            'unread_count' => Message::where('to_user_id', $userId)->where('read', false)->count(),
        ]);
    }

    public function messages(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:pets,id',
        ]);

        $userId = auth()->id();

        $messages = Message::with(['fromUser'])
            ->where(function ($q) use ($userId, $validated) {
                $q->where('from_user_id', $userId)->where('to_user_id', $validated['user_id'])
                  ->orWhere(function ($q2) use ($userId, $validated) {
                      $q2->where('from_user_id', $validated['user_id'])->where('to_user_id', $userId);
                  });
            })
            ->where('pet_id', $validated['pet_id'])
            ->orderBy('created_at')
            ->get();

        Message::where('to_user_id', $userId)
            ->where('pet_id', $validated['pet_id'])
            ->where('from_user_id', $validated['user_id'])
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    public function unread()
    {
        $count = Message::where('to_user_id', auth()->id())->where('read', false)->count();
        return response()->json(['unread' => $count]);
    }
}
