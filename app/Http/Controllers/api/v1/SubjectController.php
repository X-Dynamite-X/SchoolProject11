<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $subjects = Cache::remember('subjects_page_' . $request->input('page', 1) . '_keyword_' . $request->input('keyword', ''), now()->addMinutes(10), function () use ($request) {
            $query = Subject::select('id', 'name', 'success_mark' , "full_mark") // اختر الأعمدة المهمة فقط
                ->with('users'); // إذا كنت بحاجة للأدوار

            if ($keyword = $request->input('keyword')) {
                $query->where('name', 'like', "%$keyword%");
            }

            return $query->paginate(2);
        });

        return response()->json([
            'subjects' => $subjects,
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
