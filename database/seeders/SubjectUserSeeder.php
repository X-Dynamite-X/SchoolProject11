<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\SubjectUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $subjectUsers =
        [
                [
                    "user_id" => 1,
                    "subject_id" => 1,
                    "mark" => 90
            ],
            [
                "user_id" => 1,
                "subject_id" => 2,
                "mark" => 60
            ],
            [
                "user_id" => 1,
                "subject_id" => 3,
                "mark" => 70
            ],
            [
                "user_id" => 1,
                "subject_id" => 4,
                "mark" => 77
            ],
            [
                "user_id" => 1,
                "subject_id" => 5,
                "mark" => 33
            ],
            [
                "user_id" => 1,
                "subject_id" => 6,
                "mark" => 55
            ],
            [
                "user_id" => 2,
                "subject_id" => 1,
                "mark" => 50
            ],
            [
                "user_id" => 2,
                "subject_id" => 2,
                "mark" => 60
            ],
            [
                "user_id" => 2,
                "subject_id" => 3,
                "mark" => 70
            ],
            [
                "user_id" => 2,
                "subject_id" => 4,
                "mark" => 77
            ],
            [
                "user_id" => 2,
                "subject_id" => 5,
                "mark" => 33
            ],
            [
                "user_id" => 2,
                "subject_id" => 6,
                "mark" => 55
            ],

            [
                "user_id" => 4,
                "subject_id" => 1,
                "mark" => 70
            ],
            [
                "user_id" => 4,
                "subject_id" => 2,
                "mark" => 60
            ],
            [
                "user_id" => 4,
                "subject_id" => 3,
                "mark" => 70
            ],
            [
                "user_id" => 4,
                "subject_id" => 4,
                "mark" => 77
            ],
            [
                "user_id" => 4,
                "subject_id" => 5,
                "mark" => 33
            ],
            [
                "user_id" => 4,
                "subject_id" => 6,
                "mark" => 55
            ],

        ];
        $subjectUsers=[];
        $users = User::all();
        $subjects = Subject::all();
        foreach($subjects as $subject)
        {
            foreach ($users as $user) {
                SubjectUser::create([
                    "user_id" => $user->id,
                    "subject_id" => $subject->id,
                    "mark" => $user->id%2==0 ? 75 : 80
                ]);

            }
        }

        // foreach ($subjectUsers as $subjectUser) {
        //     SubjectUser::create($subjectUser);
        // }
    }
}
