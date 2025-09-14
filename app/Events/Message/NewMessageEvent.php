<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public function __construct($message)
    {
        $this->message = $message;
        \Log::info('NewMessageEvent constructed for message:', [
            'message_id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'channel' => 'conversation_' . $message->conversation_id
        ]);
    }
    public function broadcastOn()
    {
        return new PrivateChannel('conversation_' . $this->message->conversation_id);
    }
    public function broadcastAs()
    {
        return 'new-message';
    }
    public function broadcastWith()
    {
        $data = [
            "id" => $this->message->id,
            'text' => $this->message->text,
            'created_at' => $this->message->created_at ? $this->message->created_at->format('j/n/Y, g:i:s A') : now()->format('j/n/Y, g:i:s A'),
            'sender_id' => $this->message->sender_id,
            'conversation_id' => $this->message->conversation_id,
        ];

        \Log::info('Broadcasting message data:', $data);

        return $data;
    }
}

