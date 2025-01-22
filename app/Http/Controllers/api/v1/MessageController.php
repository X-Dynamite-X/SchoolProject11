<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\Message\NewMessageEvent;
use App\Http\Requests\Chat\TextMessageRequest;

class MessageController extends Controller
{
    //
    public function store(TextMessageRequest $request)
    {

        $message = Message::create(
            [
                'sender_id' => Auth::id(),
                'conversation_id' => $request->input('conversation_id'),
                'text' => $request->input('text'),
                "is_read"=>false,
                'created_at' => $request->input('created_at'),
                "updated_at" => null,
            ]
        );
        event(new NewMessageEvent($message));
        return response()->json([
            'newMessage' => $message,
            'message' => 'Message Create Successfully',
        ]);
    }
    public function update() {}
    public function delete() {}
}
