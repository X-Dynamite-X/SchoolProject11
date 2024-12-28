<?php

namespace App\Http\Controllers\api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewUserRequst;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $users = User::with('roles')->get(["id", "email", "name"]);
        return response()->json([
            'users' => $users,
        ]);
    }
    public function store(StoreNewUserRequst $request )
    {
        $user = User::create($request->input());

        $user->syncRoles("user");
        $user->save();
        $user = User::find($user->id)->load("roles");
        return response()->json(["user" => $user, 'message' => 'User Create Successfully'], 200);
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
