<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $cate = Category::all();
        $data = [
            'status' => 'success',
            'data' => $cate
        ];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $cate = new Category();
        $cate->name = $request->name;
        $cate->note = $request->note;
        $cate->save();
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $cate = Category::find($id);
        return response()->json($cate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $cate = Category::find($id);
        $cate->name = $request->name;
        $cate->note = $request->note;
        $cate->save();
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $cate = Wallet::find($id);
        if (!$cate) {
            $data = [
                'status' => 'error',
                'message' => 'System error'
            ];
        } else {
            $cate->delete();
            $data = [
                'status' => 'success',
                'message' => 'delete successfully'
            ];
        }
        return response()->json($data);
    }
}
