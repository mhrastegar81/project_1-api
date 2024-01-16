<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ApiRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ApiRegisterController extends Controller
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
    public function store(ApiRegisterRequest $request)
    {
        $user = User::create([
            "role" => $request->role,
            "user_name" => $request->user_name,
            "email"=> $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
        ]);

        if($user->role == 'Seller'){
            User::where('email' , $user->email)->update([
                'status' =>'waiting'
            ]);
        }

        $user->assignRole($request->role);
        
        return response()->json([
            "status" => "success",
            "text" => "you register in the site successfully"
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
