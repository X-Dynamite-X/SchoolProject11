<?php

namespace App\Events\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IsReadeMssages implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $conversation_id;
    public $isNotReadMessages;
    public function __construct($conversation_id,$isNotReadMessages)
    {
        $this->conversation_id = $conversation_id;
        $this->isNotReadMessages= $isNotReadMessages;
        //
    }

    public function broadcastOn()
    {
        return new PrivateChannel('message_in_conversation_' . $this->conversation_id. '_isRead');
        //conversation_
        // return new PrivateChannel('conversation_' . $this->conversation_id );

    }
    public function broadcastAs()
    {
        return 'read-message';
    }
    public function broadcastWith()
    {
        return [
            "conversation_id" => $this->conversation_id,
            "messages"=> $this->isNotReadMessages ,
        ];
    }
}
