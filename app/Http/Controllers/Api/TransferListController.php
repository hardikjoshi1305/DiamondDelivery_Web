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
use App\Models\AdvancePayment;
use App\Models\WalletHistory;


class TransferListController extends Controller
{
    public function TransferListold(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $type = $request->type;

        $diamondQuery = OrderAccept::leftJoin('tbl_diamond_list', 'tbl_diamond_list.id', '=', 'tbl_order_accept.item_id')
            ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id')
            ->leftJoin('tbl_party', 'tbl_party.id', '=', 'tbl_order_accept.party_id')
            ->where(function ($query) use ($accessToken) {
                $query->where('tbl_order_accept.status', '=', 1)
                    ->where('tbl_order_accept.agent_id', $accessToken->id)
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

    public function TransferList(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agent_id = $request->agent_id;
        $party_id = $request->party_id;
        $check = $request->check;

        $diamondQuery = AdvancePayment::leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_advance_payment.agent_id')
            ->leftJoin('tbl_party', 'tbl_party.id', '=', 'tbl_advance_payment.party_id')
            ->where(function ($query) use ($accessToken) {
                $query->where('tbl_advance_payment.agent_id', $accessToken->id);
            })
            ->select('tbl_advance_payment.*', 'tbl_agent.name as agent_name', 'tbl_party.name as party_name');

        if ($fromDate && $toDate) {
            $diamondQuery->whereBetween(DB::raw('DATE(tbl_advance_payment.created_at)'), [$fromDate, $toDate]);
        }

        if ($agent_id) {
            $diamondQuery->where('tbl_advance_payment.agent_id', $agent_id);
        }

        if ($party_id) {
            $diamondQuery->where('tbl_advance_payment.party_id', $party_id);
        }

        if ($check && $check !== 'all') {
            $diamondQuery->where('tbl_advance_payment.check', $check);
        }

        $diamond = $diamondQuery->orderBy('tbl_advance_payment.created_at', 'DESC')->get();

        $totalAmount = $diamond->sum('amount');

        if ($diamond->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'No Search Result Found.'
            ]);
        }
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
        $order->agent_id = $request->agent_id;
        $order->amount = $request->amount;
        $order->remaining_amount = $request->amount;
        $order->party_id = $request->party_id;
        $order->reason = $request->reason;
        $order->status = 2;
        $order->type = "Credit";
        $order->save();

        $advance = new AdvancePayment();
        $advance->date = $request->date;
        $advance->agent_id = $request->agent_id;
        $advance->amount = $request->amount;
        $advance->wallet = $request->amount;
        $advance->remaining_wallet = $request->amount;
        $advance->party_id = $request->party_id;
        $advance->reason = $request->reason;
        $advance->payment = "Credit";
        $advance->check = "Credit";
        $advance->type = "0";
        $advance->save();

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
    public function walletbalance(Request $request)
    {
        $agent_id = $request->agent_id;
        $party_id = $request->party_id;

        $wallet = AdvancePayment::where('agent_id', $agent_id)
            ->where('party_id', $party_id)
            ->whereIn('type', [0, 2])
            ->latest('created_at')
            ->first();

            if ($wallet !== null) {
                if ($wallet->remaining_wallet != "0") {
                    $data['success'] = true;
                    $data['data'] = $wallet;
                    $data['message'] = "Successfully Show Wallet Balance";
                } else {
                    $data = [
                        'success' => false,
                        'data' => null,
                        'message' => 'Wallet Balance is not sufficient.'
                    ];
                }
            } else {
                $data = [
                    'success' => false,
                    'data' => null,
                    'message' => 'No Search Result Found.'
                ];
            }
        return response()->json($data);

    }
    public function AdvancePaymentList(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $party_id = $request->party_id;

        if ($party_id) {
            $advance = AdvancePayment::where('party_id', $party_id)->where('agent_id', $accessToken->id)->orderByDesc('id')->get();
        } else {
            $advance = AdvancePayment::where('agent_id', $accessToken->id)->orderByDesc('id')->get();
        }
        if ($advance->isEmpty()) {
            return response([
                'success' => true,
                'data' => null,
                'message' => 'Advance PaymentList are empty.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $advance;
        $data['message'] = "Successfully Show Advance PaymentList";

        return response()->json($data);
    }
}
