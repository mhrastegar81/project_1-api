<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiAdminUsersUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class ApiUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereNot('role', 'admin')->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function test()
    {
        $users = User::first();
        $full_name = $users->fullName;
        dd($full_name);
        return response()->json([
            'users' => $users
        ]);
    }
    public function filter()
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('AgeMin', function (Builder $query, $value) {
                    $query->where('age', '>=', (int)$value);
                })->ignore(null),
                AllowedFilter::callback('AgeMax', function ($query, $value) {
                    $query->where('age', '<=', (int)$value);
                })->ignore(null),
                AllowedFilter::exact('email')->ignore(null),
                AllowedFilter::exact('user_name')->ignore(null),
                AllowedFilter::exact('first_name')->ignore(null),
                AllowedFilter::exact('last_name')->ignore(null),
                AllowedFilter::exact('gender')->ignore(null),
                AllowedFilter::exact('phone_number')->ignore(null),
                AllowedFilter::exact('post_code')->ignore(null),
            ])
            ->whereNot('role', 'Admin')->get();
        return response()->json([
            'status' => 'true',
            'text' => 'filter worked succesfuly',
            'users' => $users
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if ($user != null) {
            return response()->json([
                'user' => $user
            ]);
        } else {
            return response()->json([
                'text' => 'user is not aviable to show'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApiAdminUsersUpdate $request, string $id)
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
        } elseif ($user == null) {
            return response()->json([
                'text' => 'this user does not exists'
            ]);
        } else {
            return response()->json([
                'text' => 'you cant edit this user'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if ($user->role != 'Admin' && $user != null) {
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
            User::where('id', $id)->delete();
            return response()->json([
                'text' => "your $user->role delete successfuly"
            ]);
        } elseif ($user == null) {
            return response()->json([
                'text' => 'this user does not exists'
            ]);
        } else {
            return response()->json([
                'message' => 'you cant delete this users information'
            ]);
        }
    }
}
