<?php

namespace Database\Seeders;

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
        $subjectUsers = [
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
                "mark" => 90
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
                "mark" => 90
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
        foreach ($subjectUsers as $subjectUser) {
            \App\Models\SubjectUser::create($subjectUser);
        }
    }
}
