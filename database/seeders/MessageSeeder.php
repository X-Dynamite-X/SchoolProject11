<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $text = ["hi", "hello", "how are you ?", "fine", "good", "bad"];

        $messages = [
            [
                'conversation_id' => 1,
                'sender_id' => 1,
            ],
            [
                'conversation_id' => 1,
                'sender_id' => 2,
            ],
            [
                'conversation_id' => 2,
                'sender_id' => 1,
            ],
            [
                'conversation_id' => 2,
                'sender_id' => 3,
            ],
            [
                'conversation_id' => 4,
                'sender_id' => 5,
            ],
            [
                'conversation_id' => 4,
                'sender_id' => 1,
            ],
            [
                'conversation_id' => 1,
                'sender_id' => 1,
            ],
        ];
        foreach ($messages as $message) {
            $message['text'] = $text[array_rand($text)];
            Message::create($message);
        }
    }
}
