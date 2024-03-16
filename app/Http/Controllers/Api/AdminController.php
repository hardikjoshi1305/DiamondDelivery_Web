<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                return response([
                    'success' => false,
                    'data' => [],
                    'message' => 'This Credentials do not match our records.',
                ], 404);
            }

            // $token = $user->createToken('my-app-token')->plainTextToken;
            $token = Str::random(60);
            $user->token = $token;
            $user->save();

            $response = [
                'success' => true,
                'data' => $user,
                'message' => 'Login Successfully'
            ];
            return response($response, 200);
    }
}
