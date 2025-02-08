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

        $roles = Role::with("permissions")->where("guard_name", "sanctum")->get(["id", "name", "guard_name"]);
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
            "role" => $role->only(["id", "name", "guard_name"]),
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
    public function update(UpdateRole $request, Role $role)
    {
        // تحديث اسم الدور
        $role->update([
            'name' => $request->input('name'), // استخدام input() للحصول على القيم
        ]);

        // مزامنة الصلاحيات مع الدور
        $permissions = $request->input('permissions'); // الحصول على الصلاحيات من الطلب
        if (!empty($permissions)) {
            $role->syncPermissions($permissions); // مزامنة الصلاحيات
        } else {
            $role->syncPermissions([]); // إذا لم يتم إرسال صلاحيات، يتم إزالة جميع الصلاحيات
        }

        // إرجاع استجابة JSON تحتوي على الدور المحدث ورسالة نجاح
        return response()->json([
            "role" => $role->load('permissions'), // تحميل الصلاحيات المرتبطة بالدور
            "message" => "The role has been updated successfully.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
        $deleted = Role::where("id", $role->id)->delete();
        if ($deleted) {
            return response()->json([
                "messages" => "success",
                "message" => "Role deleted successfully"
            ]);
        } else {
            return response()->json([
                "messages" => "error",
                "message" => "Role not found"
            ], 404);
        }
    }
}
