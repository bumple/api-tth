<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use const http\Client\Curl\AUTH_BASIC;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $tran = Transaction::where('user_id', Auth::id())->get();
        return response()->json($tran);
    }

    public function findByCategoryId($id): JsonResponse
    {
        $category = Category::find($id);
        if ($category->checkCategoryByUser()) {
            $trans = $category->transactions()->get();
            return response()->json($trans);
        }
        return response()->json([], 404);
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
        $wallet = Wallet::find($request->wallet_id);
        $cate = Category::find($request->category_id);
        if (true) {
            $tran = new Transaction();
            $tran->note = $request->note;
            $tran->date = $request->date;
            $tran->category_id = $request->category_id;
            $tran->user_id = $request->user_id;
            $tran->wallet_name = $wallet->name;
            $type = $cate->type;
            if ($type == 'outcome') {
                if ($request->money < $wallet->amount) {
                    $wallet->amount -= $request->money;
                    $tran->money = -$request->money;
                } else {
                    return response()->json(['message' => 'Not enough money']);
                }
            } else {
                $wallet->amount += $request->money;
                $tran->money = $request->money;
            }
            $tran->save();
            $wallet->save();
            return response()->json(['message' => 'success'], 201);
        }
//        return response()->json([], 404);
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
        if ($tran->checkTranByUser()) {
            $tran->note = $request->note;
            $tran->money = $request->money;
            $tran->created_at = $request->date;
            $tran->save();
            return response()->json(['message' => 'success'], 201);
        }
        return response()->json(['message' => 'unsuccessful'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $tran = Transaction::find($id);
        if ($tran->checkTranByUser()) {

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
        return response()->json([], 404);
    }

    public function getReportTransaction()
    {
        $carbon = Carbon::now();
        $month = $carbon->month;
        $year = $carbon->year;
        $lastDayofMonth = Carbon::now()->endOfMonth()->toDateString();

        $time = [
            'week1' => [Carbon::create($year, $month, 1)->toDateString(), Carbon::create($year, $month, 7)->toDateString()],
            'week2' => [Carbon::create($year, $month, 8)->toDateString(), Carbon::create($year, $month, 14)->toDateString()],
            'week3' => [Carbon::create($year, $month, 15)->toDateString(), Carbon::create($year, $month, 21)->toDateString()],
            'week4' => [Carbon::create($year, $month, 22)->toDateString(), $lastDayofMonth],
        ];

        $tranMoneyWeek1 = Transaction::selectRaw('SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('user_id', Auth::id())->whereBetween('date', $time['week1'])->first();
        $tranMoneyWeek2 = Transaction::selectRaw('SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('user_id', Auth::id())->whereBetween('date', $time['week2'])->first();
        $tranMoneyWeek3 = Transaction::selectRaw('SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('user_id', Auth::id())->whereBetween('date', $time['week3'])->first();
        $tranMoneyWeek4 = Transaction::selectRaw('SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('user_id', Auth::id())->whereBetween('date', $time['week4'])->first();


        return response()->json([
            'week1' => $tranMoneyWeek1,
            'week2' => $tranMoneyWeek2,
            'week3' => $tranMoneyWeek3,
            'week4' => $tranMoneyWeek4
        ]);
    }

    public function getReportFromToDate(Request $request): JsonResponse
    {
        $tranArray = [];
        $from = $request->from;
        $to = $request->to;
        $data = Wallet::where('user_id', Auth::id())->pluck('name');
        foreach ($data as $item) {
            $tran = Transaction::selectRaw('SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('wallet_name', $item)->where('user_id', Auth::id())->whereBetween('date', [$from, $to])->first();
            array_push($tranArray, $tran);
        }
        return response()->json(['wallet_name' => $data,
            'money' => $tranArray]);
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new TransactionsExport(), 'transactions.xlsx');
    }


    public function exportToExcel(Request $request)
    {
        $tranArray = [];
        $from = $request->from;
        $to = $request->to;
        if ($request->from && $request->to) {
            $data = Wallet::where('user_id', Auth::id())->pluck('name');
            foreach ($data as $item) {
                $tran = Transaction::selectRaw('wallet_name,SUM(CASE WHEN money > 0 THEN money ELSE 0 END) AS Income,
       SUM(CASE WHEN money < 0 THEN money ELSE 0 END) AS Outcome')->where('wallet_name', $item)->where('user_id', Auth::id())->whereBetween('date', [$from, $to])->groupBy('wallet_name')->first();
                array_push($tranArray, $tran);
            }
            return Excel::download(new TransactionsExport(collect($tranArray)), 'test.xlsx');
        }
        return response()->json([], 404);
    }
}
