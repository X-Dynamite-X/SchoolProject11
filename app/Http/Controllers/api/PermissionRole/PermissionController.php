<?php

namespace App\Http\Controllers\api\PermissionRole;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Permission;
use App\Http\Requests\Admin\PermissionRole\UpdatePermission;
use App\Http\Requests\Admin\PermissionRole\StoreNewPermission;

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

    public function destroy(Permission $permission)
    {
        $deleted = Permission::where("id", $permission->id)->delete();
        if ($deleted) {
            return response()->json([
                "messages" => "success",
                "message" => "Permission deleted successfully"
            ]);
        } else {
            return response()->json([
                "messages" => "error",
                "message" => "Permission not found"
            ], 404);
        }
    }


    // public function destroy(Permission $permission)
    // {
    //     // حذف السجل باستخدام Query Builder
    //     $deleted = DB::table('permissions')->where('id', $permission->id)->delete();

    //     if ($deleted) {
    //         return response()->json([
    //             "messages" => "success",
    //             "message" => "Permission deleted successfully"
    //         ]);
    //     } else {
    //         return response()->json([
    //             "messages" => "error",
    //             "message" => "Permission not found"
    //         ], 404);
    //     }
    // }
}
