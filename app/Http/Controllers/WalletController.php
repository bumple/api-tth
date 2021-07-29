<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $wallets = Wallet::all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $wallet = new Wallet();
        $wallet->name = $request->name;
        $wallet->amount = $request->amount;
        $wallet->description = $request->description;
        $wallet->save();

        $data = [
            'status' => 'success',
            'message' => 'create wallet successfully !'
        ];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $wallet = Wallet::find($id);
        $wallet->name = $request->name;
        $wallet->amount = $request->amount;
        $wallet->description = $request->description;
        $wallet->save();

        $data = [
          'status' => 'success',
        ];
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function plusMoney(Request $request, $id)
    {
        $wallet = Wallet::find($id);
        $wallet->amount += $request->amount;
        $wallet->description = $request->description;
        $wallet->save();

        $data = [
            'status' => 'success',
        ];
        return response()->json($data);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $wallet = Wallet::find($id);
        if(!$wallet){
            $data = [
                'status' => 'error',
                'message' => 'System error'
            ];
        } else {
            $wallet->delete();
            $data = [
                'status' => 'success',
                'message' => 'delete successfully'
            ];
        }
        return response()->json($data);
        }
}
