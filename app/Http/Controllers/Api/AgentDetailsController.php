<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondList;
use App\Models\Agent;
use DB;


class AgentDetailsController extends Controller
{
    public function AgentDetails(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $list = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
        ->leftjoin('tbl_party','tbl_party.name','=','tbl_diamond_list.client')
        ->leftjoin('tbl_order_accept','tbl_order_accept.item_id','=','tbl_diamond_list.id')
        ->where('agent', '=', $accessToken->name)
        ->where('tbl_diamond_list.remaining_weight', '>', 0)

        ->where(function ($query) {
            $query->where('tbl_order_accept.status', '!=', 0)
                  ->orWhereNull('tbl_order_accept.status');
        })
        ->select('tbl_diamond_list.*','tbl_party.mob as contact_no')
        ->groupBy('tbl_diamond_list.lab_no')
        ->get();


        if ($list->isEmpty()) {
            return response([
                'success' => true,
                'data' => [],
                'message' => 'All Diamond Are Sold.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $list;
        $data['message'] = "Successfully Show AgentDetails Record";

        return response()->json($data);
    }
}
