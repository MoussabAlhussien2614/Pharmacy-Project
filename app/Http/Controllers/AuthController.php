<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\VerificationCodeMail;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Contracts\Role;

class AuthController extends Controller
{
    public function register(RegisterRequest $req) {
        $code = rand(100000, 999999);
        UserVerification::create([
            'username' => $req->username,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'code' => $code,
        ]);
        Mail::to($req->email)->send(new VerificationCodeMail($code));
       return response()->json(['message' => 'Verification code sent to your email.']);
    }


    public function verifyCode(Request $req){
        $req->validate([
            'email' => 'required|email',
            'code' => 'required'
        ]);

        $record = UserVerification::where('email', $req->email)
            ->where('code', $req->code)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $user = User::create([
            'username' => $record->username,
            'email' => $record->email,
            'password' => $record->password,
        ]);
        $user->assignRole('user');
        Cart::create(['user_id' => $user->id]);

        $token = $user->createToken('api-token')->plainTextToken;

        $record->delete();

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
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
