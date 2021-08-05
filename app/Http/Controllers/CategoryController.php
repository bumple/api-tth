<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

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

        if ($wallet = Wallet::where('user_id', Auth::id())->where('id', $id)->first()) {
            return response()->json($wallet->categories()->get());
        }
        return response()->json();
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
        return $cate && $cate->wallet->user->id === Auth::id() ? \response()->json($cate) : response()->json(404);
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
        if ($cate && $cate->wallet->user->id === Auth::id()) {
            $cate->update($request->all());
            $data = [
                'status' => 'success',
                'message' => 'Update success'
            ];
            return response()->json($data);
        }
        return \response()->json([], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $cate = $this->checkCategoryRole($id);
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
        if ($cate && $cate[0]->wallet->user === Auth::id()) {
            $cateID = []; // [1 mang cate] $cate[$i]->id -> [1,3,4]
            for ($i = 0; $i < count($cate); $i++) {
                array_push($cateID, $cate[$i]->id);
            }
            $data = Transaction::with('category')->whereIn('category_id', $cateID)
                ->where('date', date('Y-m-d'))->get();
            $total = 0;
            for ($i = 0; $i < count($data); $i++) {
                $total += $data[$i]->money;
            }
            return response()->json(['data' => $data, 'total' => $total]);
        }
        return \response()->json([], 404);
    }

    public function checkCategoryRole($id)
    {
        $cate = Category::find($id);
        return $cate && $cate->wallet->user->id === Auth::id() ? $cate : null;
    }



}
