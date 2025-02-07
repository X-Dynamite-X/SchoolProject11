<?php

namespace App\Http\Controllers\api\PermissionRole;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermissionRole\UpdateRole;
use App\Http\Requests\Admin\PermissionRole\StoreNewRole;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $roles = Role::with("permissions")->where("guard_name","sanctum")->get(["id", "name","guard_name"]);
        return response()->json(["roles" => RoleResource::collection($roles)]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewRole $request)
    {
        //
        $role = Role::create([
            "name" => $request->name,
        ]);
        $role->givePermissionTo($request->permissions);
        $role->save();
        $role->load("permissions");
        $permissionsNames = $role->permissions->pluck('name');

        return response()->json([
            "role" => $role->only(["id", "name"]),
            "permissions" => $permissionsNames,
            "message" => "created is Success"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
