<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\MessageCrm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $conversations = Conversation::forUser($request->user()->id)
            ->with('contact')
            ->orderByDesc('last_message_at')
            ->get();
        return response()->json($conversations);
    }

    public function messages(Request $request, int $id): JsonResponse
    {
        $conversation = Conversation::forUser($request->user()->id)->findOrFail($id);
        $messages = $conversation->messages()->orderBy('created_at')->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $conversation = Conversation::forUser($request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'type'    => 'nullable|in:text,image,file',
        ]);

        $message = MessageCrm::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'user',
            'sender_id'       => $request->user()->id,
            'content'         => $validated['content'],
            'type'            => $validated['type'] ?? 'text',
        ]);

        $conversation->update(['last_message_at' => now(), 'status' => 'open']);

        return response()->json($message, 201);
    }
}
