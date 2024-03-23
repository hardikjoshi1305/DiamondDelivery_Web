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
use App\Models\WalletHistory;


class AcceptController extends Controller
{
    public function OrderAcceptolddd(Request $request)
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
            $accept->type = "Debit";
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

                            $history = new WalletHistory();
                            $history->wallet_id = $paymentRecord->id;
                            $history->agent_id = $paymentRecord->agent_id;
                            $history->party_id = $paymentRecord->party_id;
                            $history->amount = $accept->amount;
                            $history->reason = $paymentRecord->reason;
                            $history->type = "0";

                            $history->save();

                            if ($paymentRecord->wallet == 0) {
                                $paymentRecord->status = "0";
                            }
                        } else {
                            $accept->remaining_amount = $accept->amount - $paymentRecord->wallet;
                            $paymentRecord->wallet -= $paymentRecord->wallet;
                            $paymentRecord->check = "Debit";

                            $history = new WalletHistory();
                            $history->wallet_id = $paymentRecord->id;
                            $history->agent_id = $paymentRecord->agent_id;
                            $history->party_id = $paymentRecord->party_id;
                            $history->amount = $accept->amount;
                            $history->reason = $paymentRecord->reason;
                            $history->type = "0";

                            $history->save();

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
    public function OrderAcceptold(Request $request)
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
            $accept->type = "Debit";
            if ($accept->wallet_type = $request->wallet_type == "1") {
                if ($request->agent_id && $request->party_id) {
                    $paymentRecord = AdvancePayment::where('agent_id', $request->agent_id)
                        ->where('party_id', $request->party_id)
                        ->where('status', '=', '0')
                        ->first();

                    if ($paymentRecord) {

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
            if ($request->payment == "Received") {
                $accept->remaining_amount = "0";
            } else {
                $accept->remaining_amount = $request->amount;
            }
            $accept->wallet_type = $request->wallet_type;
            $accept->weight = $request->weight;
            $accept->reason = $request->reason;
            $accept->status = 1;
            $accept->type = "Debit";

            if ($accept->wallet_type = $request->wallet_type == "1") {
                if ($request->agent_id && $request->party_id) {
                    $paymentRecord = AdvancePayment::where('agent_id', $request->agent_id)
                        ->where('party_id', $request->party_id)
                        ->where('status', '=', '0')
                        ->first();

                    if ($paymentRecord) {
                        if ($accept->amount <= $paymentRecord->wallet) {
                            $accept->remaining_amount = 0;
                            // $paymentRecord->wallet -= $accept->amount;
                            $accept->payment = "Received";
                            $paymentRecord->check = "Credit";
                            $history = new AdvancePayment();
                            $history->agent_id = $paymentRecord->agent_id;
                            $history->date = $accept->date;
                            $history->party_id = $paymentRecord->party_id;
                            $history->amount = $accept->amount;
                            $paymentRecord111 = AdvancePayment::where('agent_id', $request->agent_id)
                                ->where('party_id', $request->party_id)
                                ->latest()->first();
                            $aa = $paymentRecord111->remaining_wallet - $accept->amount;
                            $history->wallet = $paymentRecord111->remaining_wallet;
                            if ($accept->amount > $paymentRecord111->remaining_wallet) {
                                $history->remaining_wallet = "0";
                                $accept->remaining_amount = abs($aa);
                                $history->payment = $accept->payment;
                                $history->status = "1";
                            } else {
                                $history->remaining_wallet = $aa;
                                $history->payment = $accept->payment;
                            }
                            $history->reason = $paymentRecord->reason;
                            $history->check = "Debit";
                            $history->type = "2";
                            $history->save();
                        } else {
                            $accept->remaining_amount = $accept->amount - $paymentRecord->wallet;
                            // $paymentRecord->wallet -= $paymentRecord->wallet;
                            $paymentRecord->check = "Credit";

                            $history = new AdvancePayment();
                            $history->date = $accept->date;
                            $history->agent_id = $paymentRecord->agent_id;
                            $history->party_id = $paymentRecord->party_id;

                            $paymentRecord111 = AdvancePayment::where('agent_id', $request->agent_id)
                                ->where('party_id', $request->party_id)
                                ->latest()->first();
                            $aa = $paymentRecord111->remaining_wallet - $accept->amount;

                            $history->wallet = $paymentRecord111->remaining_wallet;

                             $cash = $accept->amount - $paymentRecord111->remaining_wallet;

                             if ($accept->payment == "Received") {
                                $history->remaining_wallet = "0";
                                $accept->remaining_amount = "0";
                                $history->amount = $cash; // Assuming $cash is defined elsewhere
                                $history->status = "1";
                                $history->payment = $accept->payment;
                            } else {
                                if($accept->amount > $paymentRecord111->remaining_wallet){ // corrected variable name
                                    $history->remaining_wallet = "0";
                                    $history->payment = $accept->payment;
                                    $accept->remaining_amount = abs($aa);
                                    $history->amount = $accept->amount;
                                    $history->status = "1";
                                } else {
                                    $history->remaining_wallet = $aa; // Assuming $aa is defined elsewhere
                                    $history->payment = $accept->payment;
                                    $accept->remaining_amount = abs($aa);
                                    $history->amount = $accept->amount;
                                }
                            }

                            $history->reason = $paymentRecord->reason;
                            $history->check = "Debit";
                            $history->type = "2";
                            $history->save();
                        }
                        $paymentRecord->save();
                    }
                }
            } else {
                $newwww = new AdvancePayment();
                $newwww->agent_id = $accessToken->id;
                $newwww->date = $request->date;
                $newwww->payment = $request->payment;
                if ($request->payment == "Received") {
                    $newwww->amount = $request->amount;
                } else {
                    $newwww->amount = "0";
                }
                $newwww->wallet = "0";
                $newwww->remaining_wallet = "0";
                $newwww->party_id = $request->party_id;
                $newwww->reason = $request->reason;
                $newwww->status = "1";
                $newwww->type = "2";
                $newwww->check = "Credit";
                $newwww->save();

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


            $history = new AdvancePayment();
            $history->agent_id = $accessToken->id;
            $history->date = $request->date;
            $history->order_id = $request->order_id;
            $history->amount = $request->transfer_amount;
            $history->reason = $request->remark;
            $history->party_id = $order->party_id;
            $history->wallet = "0";
            $history->remaining_wallet = "0";
            $history->status = "1";
            $history->check = "Credit";
            $history->payment = "Credit";
            $history->type = "1";
            $history->save();


            if ($request->transfer_amount > $order->remaining_amount) {
                $data['success'] = false;
                $data['data'] = null;
                $data['message'] = "Failled";

                return $data;
            } else {
                $accept->party_id = $order->party_id;
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
