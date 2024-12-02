<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $subjects = [
            [
                "name" => "python",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
            [
                "name" => "php",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
            [
                "name" => "java",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
            [
                "name" => "c++",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
            [
                "name" => "c#",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
            [
                "name" => "javascript",
                "success_mark" => "50",
                "full_mark" => "100",
            ],
        ];
        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
