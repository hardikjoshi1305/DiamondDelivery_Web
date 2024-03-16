<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Agent;

class AccessTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();
        if (!$accessToken) {
            $data['success'] = false;
            $data['data'] = [];
            $data['message'] = "Session Expired";
            return response()->json([$data], Response::HTTP_UNAUTHORIZED);
            // return response()->json([ 'message' => 'Unauthorized' ], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
