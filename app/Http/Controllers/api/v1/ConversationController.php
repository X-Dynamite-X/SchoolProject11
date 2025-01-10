<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ConversationRequest;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    //
    public function index()
    {

        // $userId = 1;
        $userId = Auth::user()->id;

        $conversations = Conversation::where("user_one_id", $userId)
            ->orWhere("user_two_id", $userId)
            ->with([
                'messages',
                'user1:id,name,email',
                'user2:id,name,email',
            ])
            ->get();

        $conversations = $conversations->map(function ($conversation) use ($userId) {
            $otherUser = $conversation->user_one_id == $userId
                ? $conversation->user2
                : $conversation->user1;

            return [
                'id' => $conversation->id,
                'other_user' => $otherUser,
               'messages' => $conversation->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'text' => $message->text,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at,
                ];
            }),
            ];
        });

        // إرجاع الاستجابة بصيغة JSON
        return response()->json(['conversations' => $conversations]);
    }

    public function store(ConversationRequest $request)
    {
        Conversation::create([
            'user_one_id' => $request->user_one_id,
            'user_two_id' => $request->user_two_id,
        ]);
    }
}
