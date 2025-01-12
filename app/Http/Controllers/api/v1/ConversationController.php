<?php

namespace App\Http\Controllers\api\v1;

use App\Models\User;
use GuzzleHttp\Psr7\Query;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Chat\ConversationRequest;
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

    public function show(Request $request)
    {
        $userId = 1; // معرف المستخدم الحالي
        $serch = $request->input('serch');

        // البحث مع استبعاد المستخدم الحالي
        $conversations = User::where(function ($query) use ($serch) {
            $query->where('name', 'LIKE', "%" . $serch . "%")
                ->orWhere('email', 'LIKE', "%" . $serch . "%");
        })
            ->where('id', '!=', $userId) // استبعاد المستخدم الحالي
            ->limit(15)
            ->get(['id', 'name']); // إرجاع الحقول المطلوبة فقط

        return response()->json([
            'conversations' => $conversations,
            'message' => "Conversations fetched successfully."
        ]);
    }

    public function store(Request $request)
    {

         $conversation = Conversation::create([
            'user_one_id' => Auth::id(),
            'user_two_id' => $request->input('user_two_id'),
        ])->load('messages', 'user2:id,name,email');

        // إعادة بناء البيانات مع تغيير اسم المفتاح
        $conversationData = $conversation->toArray();
        $conversationData['other_user'] = $conversationData['user2'];
        unset($conversationData['user2']); // إزالة المفتاح القديم

        return response()->json([
            'conversation' => $conversationData,
            'message' => 'Conversation created successfully.',
        ]);
    }
}
