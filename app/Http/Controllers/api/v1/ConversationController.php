<?php

namespace App\Http\Controllers\api\v1;

use App\Models\User;
use GuzzleHttp\Psr7\Query;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\Message\IsReadeMssages;
use App\Events\Message\NewConversationEvent;
use App\Http\Requests\Chat\ConversationRequest;
use App\Http\Resources\Chat\ConversationResource;

class ConversationController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id(); // استبدل بـ Auth::id() للحصول على ID المستخدم المُسجل دخوله

        // استرجاع المحادثات التي تحتوي على الرسائل
        $conversations = Conversation::where("user_one_id", $userId)
            ->orWhere("user_two_id", $userId)
            ->with([
                'messages',
                'user1:id,name,email',
                'user2:id,name,email',
            ])
            ->get();
        $conversations = $conversations->sortByDesc(function ($conversation) {
            if ($conversation->messages->isNotEmpty()) {
                return $conversation->messages->last()->created_at;
            }
            return null;
        });

        // إرجاع البيانات باستخدام Resource
        return ConversationResource::collection($conversations);
    }
    public function show(Request $request)
    {
        $userId = Auth::id(); // استبدل بـ Auth::id() للحصول على ID المستخدم المُسجل دخوله
        $serch = $request->input('serch');

        $conversations = User::whereAny(["name", "email"], 'LIKE', "%$serch%")
            ->where('id', '!=', $userId) // استبعاد المستخدم الحالي
            ->limit(15)
            ->get(['id', 'name', "email"]);
        return response()->json([
            'conversations' => $conversations,
            'message' => "Conversations fetched successfully."
        ]);
    }
    public function store(ConversationRequest $request)
    {

        $conversation = Conversation::create([
            'user_one_id' => Auth::id(),
            'user_two_id' => $request->input('user_two_id'),
        ])->load('messages', 'user1:id,name,email', 'user2:id,name,email');

        // إعادة بناء البيانات مع تغيير اسم
        $conversationEventData = $conversation->toArray();
        $conversationEventData['other_user'] = $conversationEventData['user1'];
        unset($conversationEventData['user1'], $conversationEventData['user2']);
        event(new NewConversationEvent($conversationEventData));

        $conversationData = $conversation->toArray();
        $conversationData['other_user'] = $conversationData['user2'];
        unset($conversationData['user1'], $conversationData['user2']); // إزالة المفتاح القديم

        return response()->json([
            'conversation' => $conversationData,
            'message' => 'Conversation created successfully.',
        ]);
    }
    public function isOpenConversation(Conversation $conversationId)
    {
        $isNotReadMessages = $conversationId->messages->where('is_read', '!=', true)
            ->where('sender_id', '!=', Auth::id());
        foreach ($isNotReadMessages as $message) {
            $message->update(['is_read' => true]);
        }
        if (sizeof($isNotReadMessages) > 0) {

            // dd($isNotReadMessages);
            broadcast(new IsReadeMssages($conversationId->id, $isNotReadMessages));
        }
        return response()->json(['message' => 'Conversation opened successfully.', "isNotReadMessages" => $isNotReadMessages]);
    }
    public function destroy() {}
}
