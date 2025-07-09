<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Contracts\Role;

class AuthController extends Controller
{
    public function register(RegisterRequest $req) {
        $user = User::create([
            'username'=>$req->username,
            'email'=>$req->email,
            'password'=>Hash::make($req->password)
        ]);
        $user->assignRole('user');
        Cart::create(['user_id' => $user->id]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>new UserResource($user),'token'=>$token]);
    }

    public function login(LoginRequest $req) {
        $user = User::where('username',$req->username)->firstOrFail();
        if (!Hash::check($req->password,$user->password)) {
            return response()->json(['message'=>'Invalid credentials'],401);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>new UserResource($user),'token'=>$token]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'Logged out']);
    }

    public function user() {
        return UserResource::collection(User::all());
    }

}
