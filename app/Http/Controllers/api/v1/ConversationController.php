<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Http\Requests\Chat\ConversationRequest;

class ConversationController extends Controller
{
    //
    public function index()
    {
        //
        $userId  = 1;
        $conversation = Conversation::where("user_one_id", $userId)
            ->orWhere("user_two_id", $userId)
            ->get(['id', 'user_one_id', 'user_two_id']);
        return response()->json(data: ['conversation' => $conversation]);
    }
    public function store(ConversationRequest $request)
    {
        Conversation::create([
            'user_one_id' => $request->user_one_id,
            'user_two_id' => $request->user_two_id,
        ]);
    }
     
}
