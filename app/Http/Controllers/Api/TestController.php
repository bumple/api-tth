<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function  login(Request $request): JsonResponse
    {
        $data = $request->only(['email','password']);
        if(!Auth::attempt($data)){
            return response()->json(['status' => 'error']);
        }else {
            return response()->json(['status'=>'success']);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();
            return response()->json(['status' => 'success']);
        }catch (\Exception $e){
            return response()->json(['status' => 'error']);
        }
    }
}
