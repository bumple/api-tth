<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Table;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $wallets = Wallet::with('categories','transactions')->get();
        $data = [
            'status' => 'success',
            'data' => $wallets
        ];
        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     * uh anh push phan test nay len roi ma ?
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $wallet = new Wallet();
        $wallet->name = $request->name;
        $wallet->amount = $request->amount;
        $wallet->description = $request->description;
        $wallet->icon = $request->icon;
        $wallet->user_id = $request->user_id;
        $wallet->save();

        $walletdata = Wallet::where('user_id',$request->user_id)->get();
        $walletID = $walletdata[count($walletdata)-1]->id;

        $cate = new Category();
        $cate->name = 'Income';
        $cate->note = 'Deposit money to wallet';
        $cate->type = 'income';
        $cate->wallet_id = $walletID;
        $cate->save();

        $catedata = Category::where('wallet_id',$walletID)->get();
        $cateIncomeID = $catedata[0]->id;

        $tran = new Transaction();
        $tran->money = $request->amount;
        $tran->note = 'First add money when create a wallet';
        $tran->date = date('Y-m-d');
        $tran->category_id = $cateIncomeID;
        $tran->user_id = $request->user_id;
        $tran->wallet_name = $request->name;
        $tran->save();

        $data = [
            'status' => 'success',
            'message' => 'create wallet successfully !'
        ];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $wallet = Wallet::find($id);
        $data = [
            'status' => 'success',
            'data' => $wallet
        ];
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $wallet = Wallet::find($id);
        $wallet->name = $request->name;
        $wallet->amount = $request->amount;
        $wallet->description = $request->description;
        $wallet->icon = $request->icon;
        $wallet->save();
        $data = [
            'status' => 'success',
        ];
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function plusMoney(Request $request, $id)
    {
        $wallet = Wallet::find($id);
        $wallet->amount += $request->amount;
        $wallet->description = $request->description;
        $wallet->date = $request->date;
        $wallet->save();

        $data = [
            'status' => 'success',
        ];
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wallet = Wallet::find($id);
        $wallet->transactions()->delete();
        $wallet->categories()->delete();
        $wallet->delete();
        return response()->json();
    }

    public function getWalletsByUserid($id): JsonResponse
    {
        $data = Wallet::where('user_id', $id)->get();

        return response()->json($data);
    }
}
