<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ConversationRequest;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Chat\ConversationResource;

class ConversationController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id(); // استبدل بـ Auth::id() للحصول على ID المستخدم المُسجل دخوله
    
        $conversations = Conversation::where("user_one_id", $userId)
            ->orWhere("user_two_id", $userId)
            ->with([
                'messages',
                'user1:id,name,email',
                'user2:id,name,email',
            ])
            ->get();
    
        // إرجاع البيانات باستخدام Resource
        return ConversationResource::collection($conversations);
    }
    

    public function store(ConversationRequest $request)
    {
        Conversation::create([
            'user_one_id' => $request->user_one_id,
            'user_two_id' => $request->user_two_id,
        ]);
    }
}
