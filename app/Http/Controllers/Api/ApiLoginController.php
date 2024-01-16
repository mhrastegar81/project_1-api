<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiLoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApiLoginRequest $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                "status" => "false",
                "text" => "your login faild  <email or password wrong>"
            ]);
        }else{
            $user = auth()->user();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            session()->put('token', $token);
            return response()->json([
                "status" => "true",
                "text" => "you loged in ",
                "token" => $token
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // if(!Auth::attempt($request->only('email', 'password'))){
        //     return response()->json([
        //         'status' => 'false',
        //         'text' => 'you loged out faild'
        //     ]);
        // }
        $user = auth()->user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
            Auth::guard('web')->logout();
        });
        return response()->json([
            'status' => 'true',
            'text' => 'you loged out successfuly'
        ]);
    }
}
