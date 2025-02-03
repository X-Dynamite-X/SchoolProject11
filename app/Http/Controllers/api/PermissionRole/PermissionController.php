<?php

namespace App\Http\Controllers\api\PermissionRole;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\PermissionRole\StoreNewPermission;
use App\Http\Requests\Admin\PermissionRole\UpdatePermission;

class PermissionController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json(["permissions" => $permissions]);
    }
    public function store(StoreNewPermission $request)
    {
        $permission = Permission::create([
            "name" => $request->name,
        ]);

        return response()->json([
            "data" => $permission,
            "message" => "created is Success",
        ], 201);
    }



    public function update(UpdatePermission $request, Permission $permission)
    {
        $permission->merge($request->validated());
        $permission->save();
        return response()->json(["permission" => $permission, "message" => "updated is Success"]);
    }

    public function destroy2(Permission $permission)
    {
        // التحقق مما إذا تم العثور على الأذن
        if (!$permission) {
            return response()->json(["message" => "Permission not found"], 404);
        }

        // التحقق مما إذا كان الأذن مرتبطًا بأي دور
        $roles = $permission->roles;
        if ($roles->isNotEmpty()) {
            return response()->json(["message" => "Cannot delete permission because it is assigned to roles."], 400);
        }

        // التحقق مما إذا كان الأذن مرتبطًا بأي مستخدم
        $users = $permission->users;
        if ($users->isNotEmpty()) {
            return response()->json(["message" => "Cannot delete permission because it is assigned to users."], 400);
        }

        // حذف الأذن
        $permission->delete();

        // إعادة استجابة JSON بنجاح
        return response()->json(["message" => "Permission deleted successfully"]);
    }
}
