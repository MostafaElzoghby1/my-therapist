<?php

namespace App\Http\Controllers\Apis\Chat;

use App\Http\Controllers\Controller;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatMessageController extends Controller
{
    
    public function get($chatId): JsonResponse
    {
        
        $messages = ChatMessage::where('chat_id', $chatId)
            ->with('user')
            ->latest('created_at')
            ->get();

        return response()->json($messages);
    }

    /**
     * Create a chat message
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function send(StoreMessageRequest $request , $chatId) 
    {
       $data = $request->validated();
        $userId = Auth::guard('sanctum')->user()->id;
        
        $chatMessage = ChatMessage::create([
            "user_id" => $userId,
            "chat_id" => $chatId,
            "message" =>$request->message
        ]);
        $chatMessage->load('user');

        /// TODO send broadcast event to pusher and send notification to onesignal services
        $this->sendNotificationToOther($chatMessage);

        return response()->json("$chatMessage Message has been sent successfully",200);
    }

    /**
     * Send notification to other users
     *
     * @param ChatMessage $chatMessage
     */
    private function sendNotificationToOther(ChatMessage $chatMessage) : void {

        broadcast(new NewMessageSent($chatMessage))->toOthers();
    }

        // $user = auth()->user();
        // $userId = $user->id;

        // $chat = Chat::where('id',$chatMessage->chat_id)
        //     ->with(['participants'=>function($query) use ($userId){
        //         $query->where('user_id','!=',$userId);
        //     }])
        //     ->first();
        // if(count($chat->participants) > 0){
        //     $otherUserId = $chat->participants[0]->user_id;

        //     $otherUser = User::where('id',$otherUserId)->first();
        //     $otherUser->sendNewMessageNotification([
        //         'messageData'=>[
        //             'senderName'=>$user->username,
        //             'message'=>$chatMessage->message,
        //             'chatId'=>$chatMessage->chat_id
        //         ]
        //     ]);

}
    


