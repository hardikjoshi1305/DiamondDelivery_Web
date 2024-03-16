<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Party;
use App\Models\DiamondList;
use App\Models\OrderAccept;
use DB;

class ShapeController extends Controller
{
    public function Shape(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        if ($accessToken) {
            $list = DiamondList::where('agent', $accessToken->name)->select('shape')->distinct()->get();
            $uniqueShapes = $list->pluck('shape')->unique();

            return response()->json([
                'success' => true,
                'message' => 'shapes retrieved successfully',
                'data' => $uniqueShapes,
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'No result Found',
            ], 404);
        }
    }

    public function clarity(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
         $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        if ($accessToken) {
            $list = DiamondList::where('agent', $accessToken->name)->select('clarity')->distinct()->get();
            $uniqueclarity = $list->pluck('clarity')->unique();

            return response()->json([
                'success' => true,
                'message' => 'clarity retrieved successfully',
                'data' => $uniqueclarity,
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'No result Found',
            ], 404);
        }
    }


    
}
