<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Party;
use App\Models\DiamondList;
use App\Models\OrderAccept;
use DB;
use PDF;

class SoldController extends Controller
{
    public function Sold(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

          $list = OrderAccept::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_order_accept.agent_id')
        ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_order_accept.party_id')
        ->leftjoin('tbl_diamond_list', 'tbl_diamond_list.id', '=', 'tbl_order_accept.item_id')
        ->where('tbl_order_accept.status', '=', 0)
        ->where('tbl_order_accept.agent_id', '=', $accessToken->id)
        ->get();

        $data = [
            'success' => true,
            'data' => $list,
            'message' => "Successfully retrieved Rejected records"
        ];

        return response()->json($data);
    }
}
