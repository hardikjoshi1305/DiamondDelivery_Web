<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class AgentLoginController extends Controller
{
    public function Agentlogin(Request $request)
    {
        $user = Agent::where('mob', $request->mob)->first();
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
            $ustatus = $user->status == 1 ? 'user' : 'agent';
            $response = [
                'success' => true,
                'data' => [
                    'id' =>$user->id,
                    'name' => $user->name,
                    'mob' => $user->mob,
                    'status' => $ustatus,
                    'token' => $user->token,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,

                ],
                'message' => 'Login Successfully'
            ];
            return response($response, 200);
    }
}
