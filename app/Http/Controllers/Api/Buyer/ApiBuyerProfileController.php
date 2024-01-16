<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiBuyerProfileController extends Controller
{

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user->role != 'Admin' && $user != null) {
            User::where('id', $id)->update($request->only(
                'status',
                'user_name',
                'first_name',
                'last_name',
                'age',
                'password',
                'email',
            ));
            return response()->json([
                'text' => 'your edit successful saved'
            ]);
        }elseif($user == null){
            return response()->json([
                'text' => 'this user does not exists'
            ]);
        }else{
            return response()->json([
                'text' => 'you cant edit this user'
            ]);
        }
    }

}
