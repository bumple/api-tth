<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $cate = Category::where('wallet_id', $request->wallet_id);
        $data = [
            'status' => 'success',
            'data' => $cate
        ];
        return response()->json($data);
    }

    public function getCategoryByWalletId($id)
    {
        $wallet = Wallet::find($id);
        $cate = $wallet->categories()->get();
        return response()->json($cate);
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
        $cate->wallet_id = $request->wallet_id;
        $cate->save();
        $data = [
            'status' => 'success'
        ];
        return response()->json($data);
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
        $data = [
            'status' => 'success',
            'message' => 'Update success'
        ];
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $cate = Category::find($id);

        if (!$cate) {
            $data = [
                'status' => 'error',
                'message' => 'System error'
            ];
        } else {
            $cate->transactions()->delete();
            $cate->delete();
            $data = [
                'status' => 'success',
                'message' => 'delete successfully'
            ];
        }
        return response()->json($data);
    }

    public function categoryStatistic($id)
    {
        $cate = Category::where('wallet_id', $id)->get();
        $cateID = []; // [1 mang cate] $cate[$i]->id -> [1,3,4]
        $cateName = [];
        for ($i = 0; $i < count($cate); $i++) {
            array_push($cateID, $cate[$i]->id);
            $cateName[$cate[$i]->id] = $cate[$i]->name;
        }
        $tran = [];
        for ($i = 0;$i < count($cateID);$i++){
            $data = DB::table('transactions')->where('category_id',$cateID[$i])
            ->where('date',date('Y-m-d'))->get();
            array_push($tran,$data);
        }
        return response()->json([$tran,$cateName]);
    }
}
