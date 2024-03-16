<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Party;
use App\Models\DiamondList;
use App\Models\OrderAccept;
use DB;

class ColorController extends Controller
{
    public function Color(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        if ($accessToken) {
            $list = DiamondList::where('agent', $accessToken->name)->select('color')->distinct()->get();
            $uniqueShapes = $list->pluck('color')->unique();

            return response()->json([
                'success' => true,
                'message' => 'Color retrieved successfully',
                'data' => $uniqueShapes,
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'No result Found',
            ], 404);
        }
    }
}
