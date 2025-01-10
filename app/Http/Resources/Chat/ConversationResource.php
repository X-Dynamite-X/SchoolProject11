<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Chat\MessageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = $request->user()->id; // للحصول على ID المستخدم المُسجل دخوله
        $otherUser = $this->user_one_id == $userId
            ? $this->user2
            : $this->user1;

        return [
            'id' => $this->id,
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
            ],
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
