<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'text' => $this->text,
            'is_read' => $this->is_read,
            'created_at'=>Carbon::parse($this->created_at)->format('j/n/Y, g:i:s A'),
            
            //'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('y-m-d H:i:s'),
        ];
    }
}
// 'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('y-m-d H:i:s'),
