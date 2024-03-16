<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderAccept;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Index()
    {
        $notification = OrderAccept::leftjoin('tbl_agent', 'tbl_agent.id', 'tbl_order_accept.agent_id')
            ->leftjoin('tbl_party', 'tbl_party.id', 'tbl_order_accept.party_id')
            ->select('tbl_order_accept.*', 'tbl_agent.name as agent_name', 'tbl_party.name as client_name')
            ->orderByDesc('tbl_order_accept.created_at')
            ->get();

        OrderAccept::where('read_at', 0)->orderByDesc('created_at')->update(['read_at' => 1]);

        return view('notification', compact('notification'));
    }

}
