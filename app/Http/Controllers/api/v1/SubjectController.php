<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Admin\StoreNewSubjectRequst;


class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with("users")->get(["id", "name","success_mark","full_mark"]);
        return response()->json([
            'subjects' => $subjects,
        ]);
    }
    public function store(StoreNewSubjectRequst $request)
    {
        $subject = Subject::create($request->input())->load('users');
        return response()->json(["subject" => $subject, 'message' => 'Subject Create Successfully'], 200);
    }
    public function update(Request $request, Subject $subject)
    {
        //
        $subject->update(
            $request->input()
        );
        return response()->json(["subject" => $subject, 'message' => 'Subject Updated Successfully'], 200);
    }
    public function destroy(Subject $subject)
    {
        //
        $subject->delete();
        return response()->json([
            "subject" => ["id" => $subject->id],
            "message" => "Subject Deleted Successfully"
        ], 200);
    }
}
