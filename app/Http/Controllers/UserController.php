<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Api\UserModel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    public function login_get() {
        return view('login_get');
    }


    public function login_post(Request $request) {

        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        $selUser = User::where('email', $credentials['email'])->orWhere('username', $credentials['email'])->first();


        if ($selUser) {

            $laravelAuthData = [
                'email' => $selUser->email,
                'password' => $credentials['password'],
            ];

            if (Auth::attempt($laravelAuthData)) {
                $request->session()->regenerate();
                return response()->json(UserModel::fromUser($selUser));
            } else {
               return response()->json(UserModel::fromError("error 1"));
            }
        } else{
            return response()->json(UserModel::fromError("error 2"));
        }
    }
}
