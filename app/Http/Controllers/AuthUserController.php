<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Client\Response;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
//Auth::attempt($data)
        $data = $request->only(['email', 'password']);
        $data['password'] = Hash::make($data['password']);
        if (Auth::attempt($data)) {
            $dataRes = [
                "sucess" => 'thanh cong',
                "boolean" => " true"
            ];
        } else {
            $dataRes = [
                "sucess" => 'ko thanh cong',
                "boolean" => " false",
                "data" => $request
            ];
        }
        return response()->json($dataRes);
    }

    public function register(Request $request)
    {
        return dd(1);
        $validator = Validator::make($request->all(),[
            "email" => "required|unique:users",
            "name" => "required|min:6",
            "password" => "required|min:6"
        ]);



        if (!$validator->fails()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $data = [
                "msg" => "Thêm mới thành công",
                "test" => "123"
            ];
        } else {

            $data = [
                "msg" => "Email này đã tồn tại",
                "test" => $validator->errors()
            ];
        }
        return \response()->json($data);
    }
}
