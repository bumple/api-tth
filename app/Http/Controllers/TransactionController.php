<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $tran = Transaction::all();
        return response()->json($tran);
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
    public function store(Request $request): JsonResponse
    {
        $tran = new Transaction();
        $tran->money = $request->money;
        $tran->note = $request->note;
        $tran->category_id = $request->category_id;
        $tran->save();

        $id = $request->wallet_id;
        $wallet = Wallet::find($id);
        if($request->category_id == 1){
            $wallet->amount += $request->money;
        } else{
            $wallet->amount -= $request->money;
        }
        $wallet->save();
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $tran = Transaction::find($id);
        return response()->json($tran);
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
    public function update(Request $request, int $id): JsonResponse
    {
        $tran = Transaction::find($id);
        $tran->name = $request->name;
        $tran->note = $request->note;
        $tran->save();
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        {
            $tran = Wallet::find($id);
            if (!$tran) {
                $data = [
                    'status' => 'error',
                    'message' => 'System error'
                ];
            } else {
                $tran->delete();
                $data = [
                    'status' => 'success',
                    'message' => 'delete successfully'
                ];
            }
            return response()->json($data);
        }
    }
}
