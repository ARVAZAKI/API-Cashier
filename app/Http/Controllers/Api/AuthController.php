<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessTokenFactory;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'roles' => 'required',
            'password' => 'required',
            'no_whatsapp' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'register failed',
                'data' => $validator->errors()
            ],401);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
        $success['email'] = $user->email;
        $success['no_whatsapp'] = $user->no_whatsapp;

        return response()->json([
            'success' => true,
            'message' => 'Register Successfully',
            'data' => $success
        ]);
    }
    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;
            $success['email'] = $auth->email;
            return response()->json([
                'success' => true,
                'message' => 'login Successfully',
                'data' => $success
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah'
            ],401);
        }
    }
}
