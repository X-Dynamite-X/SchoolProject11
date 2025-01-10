<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run()
    {

        $conversations=[[
            'user_one_id' => 1,
            'user_two_id' => 2, //1
        ],[
            'user_one_id' => 1,
            'user_two_id' => 3,//2
        ],[
            'user_one_id' => 1,
            'user_two_id' => 4,//3
        ],[
            'user_one_id' => 1,
            'user_two_id' => 5,//4
        ],[
            'user_one_id' => 1,
            'user_two_id' => 6,//5
        ],[
            'user_one_id' => 1,
            'user_two_id' => 7,//6
        ],[
            'user_one_id' => 2,
            'user_two_id' => 3,//7
        ],[
            'user_one_id' => 1,
            'user_two_id' => 9,//8
        ],[
            'user_one_id' => 1,
            'user_two_id' => 10,//9
        ],];
        foreach($conversations as $conversation){
            Conversation::create($conversation);
        }
    }
}
