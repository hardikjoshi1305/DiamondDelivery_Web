<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderAccept;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\DiamondList;


class RejectController extends Controller
{
    public function OrderReject(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $item=DiamondList::find($request->item_id);
        $item->status = 0;
        $item->save();

        if ($accessToken) {
            $records = $request->json()->all();

                $reject = new OrderAccept();
                $reject->agent_id = $accessToken->id;
                $reject->party_id = $request->party_id;
                $reject->item_id = $request->item_id;
                $reject->reason = $request->reason;
                $reject->status = 0;

                $reject->save();

            $data['success'] = true;
            $data['data'] = $reject;
            $data['message'] = "Not Accept Orders";
        } else {
            $data['success'] = false;
            $data['message'] = "Invalid access token";
        }
        return response()->json($data);
    }
    public function OrderRejectDetail(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();


        $orderrejectdetail = OrderAccept::leftJoin('tbl_diamond_list', 'tbl_diamond_list.id', '=', 'tbl_order_accept.item_id')
        ->leftjoin('tbl_party','tbl_party.id','=','tbl_order_accept.party_id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->where('agent', '=', $accessToken->name)
        ->where('tbl_order_accept.status', '=', 0)
        ->select('tbl_diamond_list.*','tbl_order_accept.*','tbl_party.mob as contact_no')
        ->get();


        if ($orderrejectdetail->isEmpty()) {
            return response([
                'success' => false,
                'data' => [],
                'message' => 'Not More Result Order Reject.'
            ], 200);
        }

        $data['success'] = true;
        $data['data'] = $orderrejectdetail;
        $data['message'] = "Successfully Show Order Reject List";

        return response()->json($data);

    }
}
