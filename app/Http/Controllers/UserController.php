<?php

namespace App\Http\Controllers;

use App\Models\Api\ResponseModel;
use App\Models\Api\UserModel;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\RoleService;
use App\Facades\Role;

class UserController extends Controller
{
    public function register(Request $request) {
        $user = Auth::user();
        if ($user) {
            return response()->json(UserModel::fromError('You are logged in'));
        }


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'username' => 'required|min:3|max:20',
            'dob' => 'required|date',
            'fname' => 'required',
            'lname' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = implode('\n', $validator->errors()->all());
            return response()->json(UserModel::fromError($errors));
        }

        $validated = $validator->validated();

        $selUser = User::query()->where('email', $validated['email'])->orWhere('username', $validated['username'])->first();
        if ($selUser) {
            return response()->json(UserModel::fromError('Username or email already exists.'));
        }

        $pwd_hash = Hash::make($validated['password']);
        $user = User::factory()->create([
            'first_name' => $validated['fname'],
            'last_name' => $validated['lname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'dob' => $validated['dob'],
            'password' => $pwd_hash
        ]);

        if (!$user) {
            return response()->json(UserModel::fromError('Failed to register user'));
        }

        Role::registerUser($user);

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();
        return response()->json(UserModel::fromUser($user));
    }

    public function login(Request $request) {

        if ($request->isMethod('get')) {
            $user = Auth::user();
            if ($user) {
                return response()->json(UserModel::fromUser($user));
            } else {
                return response()->json(UserModel::fromError('Login needed'));
            }
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(UserModel::fromError('Login failed'));
        }

        $credentials = $validator->validated();

        $selUser = User::query()->where('email', $credentials['email'])->orWhere('username', $credentials['email'])->first();

        if ($selUser) {
            $laravelAuthData = [
                'email' => $selUser->email,
                'password' => $credentials['password']
            ];

            if (Auth::attempt($laravelAuthData)) {
                $request->session()->regenerate();

                return response()->json(UserModel::fromUser($selUser));
            } else {
                return response()->json(UserModel::fromError('Login failed'));
            }
        } else {
            return response()->json(UserModel::fromError('Login failed'));
        }
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $resp = new ResponseModel();
        $resp->success();

        return response()->json($resp);
    }
}
