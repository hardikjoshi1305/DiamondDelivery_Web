<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondList;
use App\Models\OrderAccept;
use DB;
use PDF;
use App\Models\Agent;
use App\Models\AddPayment;
use App\Models\Party;

class TransferListController extends Controller
{
    public function TransferList(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $type = $request->type;

        $diamondQuery = OrderAccept::leftJoin('tbl_diamond_list', 'tbl_diamond_list.id', '=', 'tbl_order_accept.item_id')
            ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id')
            ->leftJoin('tbl_party', 'tbl_party.id', '=', 'tbl_order_accept.party_id')
            ->where(function ($query) {
                $query->where('tbl_order_accept.status', '=', 1)
                    ->orWhere('tbl_order_accept.status', '=', 2);
            })
            ->select('tbl_order_accept.*', 'tbl_agent.name as agent_name', 'tbl_party.name as party_name');

        if ($fromDate && $toDate) {
            $diamondQuery->whereBetween(DB::raw('DATE(tbl_order_accept.created_at)'), [$fromDate, $toDate]);
        }

        if ($agentId) {
            $diamondQuery->where('tbl_order_accept.agent_id', $agentId);
        }

        if ($partyId) {
            $diamondQuery->where('tbl_order_accept.party_id', $partyId);
        }

        if ($type && $type !== 'all') {
            $diamondQuery->where('tbl_order_accept.type', $type);
        }

        $diamond = $diamondQuery->orderBy('tbl_order_accept.created_at', 'DESC')->get();
        if ($diamond->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'No Search Result Found.'
            ]);
        }
        $totalAmount = $diamond->sum('amount');
        return response()->json([
            'success' => true,
            'data' => [
                'TransferList' => $diamond,
                'TotalAmount' => $totalAmount,

            ],
            'message' => 'Transfer list Show successfully.'
        ]);


    }

    public function SendMoney(Request $request)
    {
        $order = new OrderAccept();
        $order->date = $request->date;
        $order->agent_id = $request->name;
        $order->amount = $request->amount;
        $order->remaining_amount = $request->amount;
        $order->party_id = $request->party_id;
        $order->reason = $request->reason;
        $order->status = 2;
        $order->type = "Debit";
        $order->save();

        return response()->json(['success' => true, 'message' => 'Send Money Successfully']);
    }
    public function AgentList(Request $request)
    {
        $agent = Agent::all();
        if ($agent->isEmpty()) {
            return response([
                'success' => true,
                'data' => null,
                'message' => 'Agent Details are empty.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $agent;
        $data['message'] = "Successfully Show AgentDetails Record";

        return response()->json($data);
    }
    public function ClientList(Request $request)
    {
        $party = Party::all();
        if ($party->isEmpty()) {
            return response([
                'success' => true,
                'data' => null,
                'message' => 'Party Details are empty.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $party;
        $data['message'] = "Successfully Show PartyDetails Record";

        return response()->json($data);
    }
}
