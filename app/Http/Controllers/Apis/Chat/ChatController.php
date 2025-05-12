<?php

namespace App\Http\Controllers\Apis\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Gets chats
     *
     * @param GetChatRequest $request
     * @return JsonResponse
     */
    public function myChats()
    {

        $userId = Auth::guard('sanctum')->user()->id;
        if (Chat::where('p_user_id', $userId)->exists()) {
            $chats = Chat::whereHas('messages')
                ->where('p_user_id', $userId)
                ->with('lastMessage.user')
                ->latest('updated_at')
                ->get();
        } else {
            $chats = Chat::whereHas('messages')
                ->where('dr_user_id', $userId)
                ->with('lastMessage.user')
                ->latest('updated_at')
                ->get();
        }
        if ($chats->isNotEmpty()) {
            return response()->json($chats);
        } else {
            return response()->json("not found", 401);
        }
    }


    /**
     * Stores a new chat
     *
     * @param StoreChatRequest $request
     * @return JsonResponse
     */


    public function getChatByUser($otherUserId): JsonResponse
    {
        $userId = Auth::guard('sanctum')->user()->id;

        if (Chat::where('p_user_id', $userId)->first()) {
            $chat = Chat::where('dr_user_id', $otherUserId)
                ->where('p_user_id', $userId)
                ->first();
        } else {
            $chat = Chat::where('dr_user_id', $userId)
                ->where('p_user_id', $otherUserId)
                ->first();
        }
        if ($chat) {
            return response()->json($chat);
        } else {
            return response()->json('not find', 401);
        }
    }

    public function getChatById($chatId)
    {
        $chat = Chat::findOrFail($chatId);
        if ($chat) {
            return response()->json($chat);
        } else {
            return response()->json("not found", 401);
        }
    }
}