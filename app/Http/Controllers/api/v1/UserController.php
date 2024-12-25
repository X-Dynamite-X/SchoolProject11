<?php

namespace App\Http\Controllers\api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        // $keyword = $request->input('keyword');
        // $start = $request->input('start', 0); // بداية الجلب
        // $limit = $request->input('limit', 50); // عدد العناصر
        // $query = User::select("id", "email", "name")
        //     ->with("roles");
        // if ($keyword) {
        //     $query->where('name', 'like', "%$keyword%")
        //         ->orWhere('email', 'like', "%$keyword%")
        //     ;
        // }
        // $totalCount = $query->count();
        // $nextPage = ($start + $limit) < $totalCount ? $start + $limit : null;
        // $prevPage = $start > 0 ? $start - $limit : null;
        // $users = $query->skip($start)->take($limit)->get();
        $users = User::with('roles')->get(["id", "email", "name"]);
        return response()->json([
            // 'nextPage' => $nextPage,
            // 'prevPage' => $prevPage,
            // 'prev_page_url' => $prevPage !== null ? url()->current() . '?start=' . $prevPage . '&limit=' . $limit : null,
            // 'next_page_url' => $nextPage !== null ? url()->current() . '?start=' . $nextPage . '&limit=' . $limit : null,
            'users' => $users,
            // 'count' => $totalCount,
        ]);
    }
    public function destroy(User $user)
    {
        try {
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $userId = $user->id;
            $user->delete();
            return response()->json([
                "user" => ["id" => $userId],

                "message" => "User deleted successfully"
            ], 200);

        } catch (\Exception $e) {
            // التعامل مع الأخطاء
            return response()->json([
                "message" => "An error occurred while deleting the user",
                "error" => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->input("roles"));
        return response()->json(["user" => $user, 'message' => 'User updated successfully'], 200);
    }
}
