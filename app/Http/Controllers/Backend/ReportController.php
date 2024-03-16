<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Party;
use App\Models\DiamondList;
use App\Models\Agent;
use App\Models\OrderAccept;
use App\Exports\DiamondDetails;
use App\Exports\AgentWise;
use App\Exports\SoldWise;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\DiamondDetailsPDF;
use DB;
use Excel;
use PDF;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $diamonddetails = DiamondList::all();
        $orderaccept = OrderAccept::all();
        $agent = Agent::all();
        $party = Party::all();
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $remainId = $request->remaining_weight;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $colorId = $request->color;
        $shapeId = $request->shape;
        $fromweight= $request->from_weight;
        $toweight = $request->to_weight;

        $diamonddetails = DiamondList::leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
        ->leftjoin('tbl_party','tbl_party.id','=','tbl_order_accept.party_id')

        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*','tbl_order_accept.weight as aweight','tbl_agent.name as aname','tbl_party.name as pname');

        if($fromDate && $toDate){
        $diamonddetails->when($request->has(['fromDate', 'toDate']), function ($query) use ($request) {
            return $query->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$request->input('fromDate'), $request->input('toDate')]);
        });
        }
        if($agentId){
            $diamonddetails->when($request->filled('agent_id'), function ($query) use ($request) {
                return $query->where('tbl_diamond_list.agent', $request->input('agent_id'));
            });
           }
        if($partyId){
            $diamonddetails->when($request->filled('party_id'), function ($query) use ($request) {
                return $query->where('tbl_diamond_list.client', $request->input('party_id'));
            });
           }
        if($remainId){
            $diamonddetails->when($request->filled('remaining_weight'), function ($query) use ($request) {
                return $query->where('tbl_diamond_list.remaining_weight', $request->input('remaining_weight'));
        });
        }
        if($colorId){
            $diamonddetails->when($request->filled('color'), function ($query) use ($request) {
                return $query->where('tbl_diamond_list.color', $request->input('color'));
        });
        }
        if($shapeId){
            $diamonddetails->when($request->filled('shape'), function ($query) use ($request) {
                return $query->where('tbl_diamond_list.shape', $request->input('shape'));
        });
        }
        if ($fromweight && $toweight) {
            $diamonddetails->whereBetween('tbl_diamond_list.weight', [$fromweight, $toweight]);
        }
        $diamonddetails = $diamonddetails->orderBy('tbl_diamond_list.created_at', 'DESC')->get();

        return view('Backend.Report',compact('diamonddetails','agent','party'));
    }
    public function exportCSVFile(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $remainId = $request->remaining_weight;

        return Excel::download(new DiamondDetails($fromDate, $toDate,$agentId,$remainId,$partyId), 'DiamondDetails.xlsx');
    }
    public function generatePDF(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;
        $remainId = $request->remaining_weight;

        $diamonddetailsQuery = DiamondList::leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
            ->leftjoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id')
            ->leftjoin('tbl_party', 'tbl_party.id', '=', 'tbl_order_accept.party_id')
            ->select('tbl_diamond_list.*', 'tbl_diamond_list.weight as tweight', 'tbl_order_accept.*', 'tbl_order_accept.weight as aweight', 'tbl_agent.name as aname', 'tbl_party.name as pname');

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

        $agent = Agent::all();
        $party = Party::all();

        $pdf = PDF::loadView('Backend.OrderAccept_wise_pdf', compact('agent', 'party', 'diamonddetails', 'fromDate', 'toDate', 'agentId', 'partyId', 'remainId'));

        $filename = 'DiamondDetails_' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdf->output());
        return response()->download($pdfPath, 'DiamondDetails.pdf');

    }

    public function AgentWise(Request $request)
    {
        $agent = DiamondList::all();
        $agent = null;
        $agentwise = Agent::all();
        $party = Party::all();
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;


        $agent1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_order_accept.*','tbl_diamond_list.id as diamond_id', 'tbl_diamond_list.weight as tweight')
        ->selectRaw('SUM(tbl_order_accept.weight) as Total_selling_weight')
        ->leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id');

        if ($fromDate && $toDate) {
            $agent1->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$fromDate, $toDate]);
        }
         if ($agentId) {
            $agent1->where('tbl_diamond_list.agent', $agentId);
        }
        if ($partyId) {
            $agent1->where('tbl_diamond_list.client', $partyId);
        }
        $agent = $agent1->groupBy('tbl_diamond_list.lab_no')
        ->orderBy('tbl_diamond_list.created_at')
        ->get();

        return view('Backend.Agent_wise_report',compact('agent','agentwise','party'));
    }

    public function AgentgeneratePDF(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;


        $agent1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_order_accept.*','tbl_diamond_list.id as diamond_id', 'tbl_diamond_list.weight as tweight')
        ->selectRaw('ROUND(SUM(tbl_order_accept.weight), 2) as Total_selling_weight')
        ->leftJoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftJoin('tbl_agent', 'tbl_agent.id', '=', 'tbl_order_accept.agent_id');

        if ($fromDate && $toDate) {
            $agent1->whereBetween(DB::raw('DATE(tbl_diamond_list.created_at)'), [$fromDate, $toDate]);
        }

        if ($agentId) {
            $agent1->where('tbl_diamond_list.agent', $agentId);
        }

        if ($partyId) {
            $agent1->where('tbl_diamond_list.client', $partyId);
        }

        $agent = $agent1->groupBy('tbl_diamond_list.lab_no')
        ->orderBy('tbl_diamond_list.created_at')
        ->get();

        $agentwise = Agent::all();
        $party = Party::all();

        $pdf = PDF::loadView('Backend.Agent_wise_pdf', compact('agent', 'party', 'agentwise', 'fromDate', 'toDate', 'agentId', 'partyId'));

        $filename = 'AgentWise' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdf->output());
        return response()->download($pdfPath, 'AgentWise.pdf');
    }
    public function AgentexportCSVFile(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;

        return Excel::download(new AgentWise($fromDate, $toDate,$agentId,$partyId), 'AgentWise.xlsx');
    }
    public function SoldWise(Request $request)
    {
        $sold = DiamondList::all();
        $agent = null;
        $agentwise = Agent::all();
        $party = Party::all();
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;

        $sold1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*')
        ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
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
        $sold = $sold1->orderBy('tbl_diamond_list.agent', 'asc')
        ->get();

        return view('Backend.SoldBY_wise_report',compact('sold','agentwise','party'));
    }
    public function SoldgeneratePDF(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;


        $sold1 = DB::table('tbl_diamond_list')
        ->select('tbl_diamond_list.*','tbl_diamond_list.weight as tweight', 'tbl_order_accept.*')
        ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
        ->leftjoin('tbl_agent','tbl_agent.id','=','tbl_order_accept.agent_id')
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

        $agentwise = Agent::all();
        $party = Party::all();

        $pdf = PDF::loadView('Backend.Sold_wise_pdf', compact('sold', 'party', 'agentwise', 'fromDate', 'toDate', 'agentId', 'partyId'));

        $filename = 'SoldBYWise' . time() . '.pdf';
        $pdfPath = public_path('pdf/' . $filename);
        file_put_contents($pdfPath, $pdf->output());
        return response()->download($pdfPath, 'SoldBYWise.pdf');

    }
    public function SoldexportCSVFile(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $agentId = $request->agent_id;
        $partyId = $request->party_id;

        return Excel::download(new SoldWise($fromDate, $toDate,$agentId,$partyId), 'SoldWise.xlsx');
    }
}
