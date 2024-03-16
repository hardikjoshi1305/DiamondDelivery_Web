<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderAccept;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\DiamondList;

class PendingController extends Controller
{
    public function pending(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

         $pending = DiamondList::where('status','=',2)->where('tbl_diamond_list.agent','=', $accessToken->name)->get();

         $data = [
            'success' => true,
            'data' => $pending,
            'message' => "Successfully Show Pending records"
        ];

        return response()->json($data);
    }
}
