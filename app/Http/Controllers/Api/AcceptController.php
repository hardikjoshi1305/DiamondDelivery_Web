<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderAccept;
use App\Models\Agent;
use App\Models\DiamondList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\AddPayment;
use App\Models\AdvancePayment;


class AcceptController extends Controller
{
    public function OrderAccept(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $item = DiamondList::find($request->item_id);
        $remainin = $item->remaining_weight - $request->weight;
        $round = round($remainin, 2);
        $item->remaining_weight = $round;

        if ($remainin <= 0) {
            $item->status = 1;
        } else {
            $item->status = 0;
        }
        $item->save();
        if ($accessToken) {
            $records = $request->json()->all();
            $accept = new OrderAccept();
            $accept->agent_id = $accessToken->id;
            $accept->date = $request->date;
            $accept->payment = $request->payment;
            $accept->party_id = $request->party_id;
            $accept->item_id = $request->item_id;
            $accept->amount = $request->amount;
            $accept->remaining_amount = $request->amount;
            $accept->wallet_type = $request->wallet_type;
            $accept->weight = $request->weight;
            $accept->status = 1;
            $accept->type = "Credit";
            if ($accept->wallet_type = $request->wallet_type == "1") {
                if ($request->agent_id && $request->party_id) {
                    $paymentRecord = AdvancePayment::where('agent_id', $request->agent_id)
                        ->where('party_id', $request->party_id)
                        ->where('status', '=', '0')
                        ->first();

                    if ($paymentRecord) {
                        if ($accept->amount <= $paymentRecord->wallet) {
                            $accept->remaining_amount = 0;
                            $paymentRecord->wallet -= $accept->amount;
                            $accept->payment = "Received";
                            $paymentRecord->check = "Debit";

                            if ($paymentRecord->wallet == 0) {
                                $paymentRecord->status = "0";
                            }
                        } else {
                            $accept->remaining_amount = $accept->amount - $paymentRecord->wallet;
                            $paymentRecord->wallet -= $paymentRecord->wallet;
                            $paymentRecord->check = "Debit";

                            if ($paymentRecord->wallet == 0) {
                                $paymentRecord->status = "1";
                            }
                        }

                        $paymentRecord->save();
                    }

                }
            }
            $accept->save();
            $data['success'] = true;
            $data['data'] = $accept;
            $data['message'] = "Successfully Accept Orders";
        } else {
            $data['success'] = false;
            $data['message'] = "Invalid access token";
        }
        return response()->json($data);
    }


    public function Remark(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $orderrejectdetail = OrderAccept::where('agent_id', '=', $accessToken->id)
            ->leftjoin('tbl_agent', 'tbl_agent.id', 'tbl_order_accept.agent_id')
            ->where('tbl_order_accept.status', '=', 1)
            ->orwhere('tbl_order_accept.status', '=', 2)
            ->select('tbl_order_accept.*')
            ->get();

        if ($orderrejectdetail->isEmpty()) {
            return response([
                'success' => false,
                'data' => [],
                'message' => 'Not More Transfer Money Order.'
            ], 200);
        }

        $data['success'] = true;
        $data['data'] = $orderrejectdetail;
        $data['message'] = "Successfully Show Transfer Money Order";

        return response()->json($data);

    }

    public function PendingPayment(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $orderpending = OrderAccept::where('agent_id', '=', $accessToken->id)
            ->leftjoin('tbl_diamond_list', 'tbl_diamond_list.id', 'tbl_order_accept.item_id')
            ->leftjoin('tbl_agent', 'tbl_agent.id', 'tbl_order_accept.agent_id')
            ->where('tbl_order_accept.payment', '=', "Credit")
            ->select('tbl_order_accept.*', 'tbl_diamond_list.lab_no as lab_no')
            ->orderBy('tbl_order_accept.created_at', 'desc')
            ->get();
        if ($orderpending->isEmpty()) {
            return response([
                'success' => false,
                'data' => [],
                'message' => 'Not More Pending Payment Order.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $orderpending;
        $data['message'] = "Successfully Show Pending Payment Order";

        return response()->json($data);
    }
    public function AddPayment(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        if ($accessToken) {
            $accept = new AddPayment();
            $accept->agent_id = $accessToken->id;
            $accept->date = $request->date;
            $accept->order_id = $request->order_id;
            $accept->transfer_amount = $request->transfer_amount;
            $accept->remark = $request->remark;
            $order = OrderAccept::where('id', $request->order_id)->first();

            if ($request->transfer_amount > $order->remaining_amount) {
                $data['success'] = false;
                $data['data'] = null;
                $data['message'] = "Failled";

                return $data;
            } else {
                $accept->save();

            }
            if ($order) {
                $order->remaining_amount -= $request->transfer_amount;
                if ($order->remaining_amount <= 0) {
                    $order->remaining_amount = 0;
                    $order->payment = 'Received';
                }

                $order->save();
            }

            $data['success'] = true;
            $data['data'] = $accept;
            $data['message'] = "Successfully Transfer Payment";
        } else {
            $data['success'] = false;
            $data['message'] = "Invalid access token";
        }
        return response()->json($data);
    }

    public function FetchTransaction(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $order_id = $request->input('order_id');

        $addpayment = AddPayment::where('tbl_add_payment.agent_id', '=', $accessToken->id)
            ->where('tbl_add_payment.order_id', '=', $order_id)
            ->leftjoin('tbl_agent', 'tbl_agent.id', 'tbl_add_payment.agent_id')
            ->leftjoin('tbl_order_accept', 'tbl_order_accept.id', 'tbl_add_payment.order_id')
            ->select('tbl_order_accept.*', 'tbl_add_payment.*')
            ->get();

        if ($addpayment->isEmpty()) {
            return response([
                'success' => false,
                'data' => [],
                'message' => 'Not More Show Fetch Transaction Record.'
            ], 200);
        }
        $data['success'] = true;
        $data['data'] = $addpayment;
        $data['message'] = "Successfully Show Fetch Transaction Record";

        return response()->json($data);
    }
}
