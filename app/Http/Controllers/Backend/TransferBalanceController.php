<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondList;
use App\Models\OrderAccept;
use DB;
use PDF;
use App\Models\Agent;
use App\Models\AddPayment;
use App\Models\Party;

class TransferBalanceController extends Controller
{
    public function index(Request $request)
    {
        $agent = Agent::all();
        $party = Party::all();

        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $agentId = $request->input('agent_id');
        $partyId = $request->input('party_id');
        $type = $request->input('type');


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
        return view('Backend.transfer_menu', compact('diamond', 'agent', 'party'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'amount' => 'required',
        ]);


        $order = new OrderAccept();
        $order->date = $request->date;
        $order->agent_id = $request->name;
        $order->amount = $request->amount;
        $order->remaining_amount = $request->amount;
        $order->reason = $request->reason;
        $order->status = 2;
        $order->type = "Credit";

        $order->save();

        return redirect()->route('transfer.menu')->with('success', 'Send Money Successfully');
    }
    public function payment(Request $request)
    {
        $id = $request->id;

        $payment = AddPayment::leftjoin('tbl_agent', 'tbl_agent.id', 'tbl_add_payment.agent_id')->where('order_id', $id)
            ->select('tbl_add_payment.*', 'tbl_agent.name as agent_name')->get();

        return view('Backend.payment_list', compact('payment'));
    }

    public function Collection(Request $request)
    {
        $agent = Agent::all();
        $party = Party::all();

        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $agentId = $request->input('agent_id');
        $partyId = $request->input('party_id');


        $collectionOrder = OrderAccept::leftJoin('tbl_diamond_list', 'tbl_diamond_list.id', '=', 'tbl_order_accept.item_id')
        ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id')
        ->leftJoin('tbl_party', 'tbl_party.id', '=', 'tbl_order_accept.party_id')
        ->where('tbl_order_accept.status', '=', 1)
        ->select('tbl_order_accept.*', 'tbl_agent.name as agent_name', 'tbl_party.name as party_name');

        if ($fromDate && $toDate) {
            $collectionOrder->whereBetween(DB::raw('DATE(tbl_order_accept.created_at)'), [$fromDate, $toDate]);
        }
        if ($agentId) {
            $collectionOrder->where('tbl_order_accept.agent_id', $agentId);
        }
        if ($partyId) {
            $collectionOrder->where('tbl_order_accept.party_id', $partyId);
        }

        $collection = $collectionOrder->orderBy('tbl_order_accept.created_at', 'DESC')->get();
        return view('Backend.collection', compact('collection', 'agent', 'party'));
    }

    // public function Delete($id)
    // {
    //     return $id;
    //      $diamond = OrderAccept::find($id);

    //     $weight = $diamond->weight;

    //     $items=DiamondList::where('id','=',$diamond->item_id)->first();
    //     $items->remaining_weight += $weight;
    //     $items->save();

    //     $diamond->delete();

    //     return redirect()->route('transfer_menu')->with('error', 'Diamond deleted successfully.');
    // }
}
