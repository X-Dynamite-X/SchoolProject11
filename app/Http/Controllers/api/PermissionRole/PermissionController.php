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

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(["message", "deleted is Success"]);
    }
}
