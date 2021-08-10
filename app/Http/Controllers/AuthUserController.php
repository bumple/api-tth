<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Client\Response;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;
use JWTAuth;


class AuthUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','logout',]]);
    }


    public function userProfile(){
        return \response()->json(Auth::user());
    }


    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (! $token = auth()->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->createNewToken($token);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @throws ValidationException
     */
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);

    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function createNewToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

   protected function update(Request $request, $id )
   {
       if ($request->hasFile('image')){
           $user = User::find($id);
           $user->name = $request->name;
           $fileName = $request->image->getClientOriginalName();
           $image = date('Y-m-d H:i:s') . '-' . $fileName;
           $request->file('image')->storeAs('public/image', $image);
           $user->avatar = $image;
           $user->save();
           $data = [
               'message' => 'Successfully update profile',
               'user' => $user
           ];
           return response()->json($data);
       }
   }


   protected function changePassword(Request $request, $id) {
        $user = User::find($id);

        if (Hash::check($request->currentPassword, $user->password)) {
            $user->password = Hash::make($request->password_confirmation);
            $user->save();
            return \response()->json('ok');
        } else {
            return \response()->json('false');
        }
   }

   protected function getLoginUser($id){
        return \response()->json(User::find($id));
   }
}
