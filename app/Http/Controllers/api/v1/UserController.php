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
        $users = Cache::remember('users_page_' . $request->input('page', 1) . '_keyword_' . $request->input('keyword', ''), now()->addMinutes(10), function () use ($request) {
            $query = User::select('id', 'name', 'email') // اختر الأعمدة المهمة فقط
                ->with('roles'); // إذا كنت بحاجة للأدوار

            if ($keyword = $request->input('keyword')) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', value: "%$keyword%");
            }

            return $query->paginate(2);
        });

        return response()->json([
            'users' => $users,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
