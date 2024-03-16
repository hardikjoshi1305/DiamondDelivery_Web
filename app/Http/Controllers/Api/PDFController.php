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

class PDFController extends Controller
{
    public function PDF(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $remainId = $request->remaining_weight;

        $diamonddetailsQuery = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*','tbl_party.mob as contact_no')
        ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->leftjoin('tbl_party','tbl_party.name','=','tbl_diamond_list.client');


        if ($fromDate && $toDate) {
            $diamonddetailsQuery->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$fromDate, $toDate]);
        }
        if ($agentId) {
            $diamonddetailsQuery->where('tbl_diamond_list.agent', $agentId);
        }
        if ($partyId) {
            $diamonddetailsQuery->where('tbl_diamond_list.client', $partyId);
        }
        if ($remainId) {
            $diamonddetailsQuery->where('tbl_diamond_list.remaining_weight', $remainId);
        }
        $diamonddetails = $diamonddetailsQuery->orderBy('tbl_diamond_list.created_at', 'DESC')->get();

        if ($diamonddetails->isEmpty()) {
            return response()->json(['message' => 'No search results found']);
        }
        $pdf = PDF::loadView('Backend.OrderAccept_wise_pdf', compact('diamonddetails', 'fromDate', 'toDate', 'agentId', 'partyId', 'remainId'));

        $pdfContent = $pdf->output();
        $filename = 'DiamondDetails_' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdfContent);

        $pdfUrl = url('public/pdf/' . $filename);
        $pdfUrl = str_replace('\\', '/', $pdfUrl);

        $data['success'] = true;
        $data['data'] = $pdfUrl;
        $data['message'] = "Successfully";

        return response()->json($data);
    }
    public function AgentWisePDF(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent;
        $partyId = $request->party;
        $color = $request->color;
        $shape = $request->shape;
        $fromWeight = $request->fromWeight;
        $toWeight = $request->toWeight;

        $agent1 = DB::table('tbl_diamond_list')
            ->select('tbl_diamond_list.*', 'tbl_order_accept.*', 'tbl_diamond_list.id as diamond_id', 'tbl_diamond_list.weight as tweight','tbl_party.mob as contact_no')
            ->selectRaw('ROUND(SUM(tbl_order_accept.weight), 2) as Total_selling_weight')
            ->selectRaw('ROUND(SUM(tbl_order_accept.amount), 2) as Total_selling_amount')
            ->leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
            ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id')
            ->leftjoin('tbl_party','tbl_party.name','=','tbl_diamond_list.client')
            ->where('tbl_diamond_list.agent', '=', $accessToken->name)
            ->where('tbl_order_accept.status', '=', '1');

        if ($fromDate && $toDate) {
            $agent1->whereBetween(DB::raw("DATE_FORMAT(tbl_diamond_list.shipment_date, '%d-%m-%Y')"), [date("d-m-Y", strtotime($fromDate)), date("d-m-Y", strtotime($toDate))]);
        }
        if ($agentId) {
            $agent1->where('tbl_diamond_list.agent', $agentId);
        }
        if ($partyId) {
            $agent1->where('tbl_diamond_list.client', $partyId);
        }
        if ($shape) {
            $agent1->where('tbl_diamond_list.shape', '=', $shape);
        }
        if ($color) {
            $agent1->where('tbl_diamond_list.color', '=', $color);
        }
        if ($fromWeight && $toWeight) {
            $agent1->whereBetween('tbl_diamond_list.weight', [$fromWeight, $toWeight]);
        }

            $agent = $agent1->groupBy('tbl_diamond_list.lab_no')
            ->orderBy('tbl_diamond_list.created_at')
            ->get();
        $totalAmount = $agent->sum('Total_selling_amount');

        $pdf = PDF::loadView('Backend.Agent_wise_pdf', compact('agent', 'fromDate', 'toDate', 'agentId', 'partyId'));
        $pdfContent = $pdf->output();
        $filename = 'AgentWise_' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdfContent);

        $pdfUrl = url('public/pdf/' . $filename);
        $pdfUrl = str_replace('\\', '/', $pdfUrl);
        $response = [
            'success' => true,
            'data' => [
                'AgentWise' => $pdfUrl,
                'AgentDetails' => $agent,
                'TotalCollection' => $totalAmount
            ],
            'message' => 'Successfully Created PDF'
        ];

        return response()->json($response);
    }


    public function SoldWisePDF(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;

        $sold1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*','tbl_party.mob as contact_no')
        ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->leftjoin('tbl_party','tbl_party.name','=','tbl_diamond_list.client')

        ->where(function ($sold1) {
            $sold1->where('tbl_diamond_list.status', '=','1');
        });

        if ($fromDate && $toDate) {
            $sold1->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$fromDate, $toDate]);
        }

        if ($agentId) {
            $sold1->where('tbl_diamond_list.agent', $agentId);
        }

        if ($partyId) {
            $sold1->where('tbl_diamond_list.client', $partyId);
        }

        $sold = $sold1->orderBy('tbl_diamond_list.created_at', 'DESC')->get();

        if ($sold->isEmpty()) {
            return response()->json(['message' => 'No search results found']);
        }

        $pdf = PDF::loadView('Backend.Sold_wise_pdf', compact('sold', 'fromDate', 'toDate', 'agentId', 'partyId'));

        $pdfContent = $pdf->output();
        $filename = 'SoldWise_' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdfContent);

        $pdfUrl = url('public/pdf/' . $filename);
        $pdfUrl = str_replace('\\', '/', $pdfUrl);

        $data['success'] = true;
        $data['data'] = $pdfUrl;
        $data['message'] = "Successfully";

        return response()->json($data);

    }
    public function AllAgent(Request $request)
    {
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

        if ($accessToken) {

            $agents = Agent::select('id', 'name')->get();
            $data['success'] = true;
            $data['data'] = $agents;
            $data['message'] = "Successfully";

            return response()->json($data);

        } else {
            return response([
                'success' => false,
                'data' => [],
                'message' => 'This not any records.',
            ], 404);
        }

    }
}


