<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubjectUser;
use App\Models\Subject;
use App\Models\User;
use App\Http\Requests\Admin\StoreSubjectUsersRequest;


class SubjectUsersController extends Controller
{

    public function store(StoreSubjectUsersRequest $request, Subject $subject)
    {
        // استلام معرفات المستخدمين من الطلب
        $user_ids = $request->input('user_ids');

        // التحقق من صحة المستخدمين واستبعاد غير الموجودين
        $valid_users = User::whereIn('id', $user_ids)->get();

        // ربط المستخدمين بالموضوع
        $subject->users()->attach($valid_users->pluck('id'), ['mark' => 0]);

        // استرجاع بيانات المستخدمين المضافين حديثًا
        $added_users = $subject->users()
            ->whereIn('users.id', $valid_users->pluck('id')) // تحديد الجدول "users" بوضوح
            ->get();
        return response()->json([
            "message" => "Users Added to Subject Successfully",
            "users" => $added_users,
            "subject_id"=> $subject->id,
        ]);
    }




    public function update(Request $request, Subject $subject, User $user)
    {
        $subjectUser = SubjectUser::where('subject_id', $subject->id)
            ->where('user_id', $user->id)
            ->first();
        if ($subjectUser) {
            $subjectUser->update(['mark' => $request->mark]);
            return response()->json(["subjectUser" => $subjectUser,"message" => "User in Subject Updated Successfully"]);
        }
        return response()->json(["message" => "subjectUser not found"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject, User $user)
    {
        $subjectUser = SubjectUser::where('subject_id', $subject->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $subjectUser->delete();

        return response()->json(["message" => "User in Subject Deleted Successfully", "user_id" => $user->id, "subject_id" => $subject->id]);
    }
}
