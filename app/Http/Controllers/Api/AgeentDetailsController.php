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
use Excel;
use App\Models\CartList;
use App\Exports\generateExcel;

class AgeentDetailsController extends Controller
{
public function AgentDetails(Request $request)
{
        $authorization = $request->header('Authorization');
        $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
        $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

         $agentIds = $request->input('client');
         $replace=str_replace("'", '"', $agentIds);
         $agentId=json_decode($replace);
        //  $lab = $request->input('lab_no');
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $color = $request->color;
        $shape = $request->shape;
        $fromWeight = $request->fromWeight;
        $toWeight = $request->toWeight;

        $list = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
            ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
            ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
            ->where('agent', '=', $accessToken->name);

        if ($agentIds) {
            $list->whereIn('tbl_diamond_list.client', $agentId)->orWhereIn('tbl_diamond_list.lab_no', $agentId);
        }
        if ($fromDate && $toDate) {
            $list->whereBetween(DB::raw("DATE_FORMAT(tbl_order_accept.shipment_date, '%d-%m-%Y')"), [date("d-m-Y", strtotime($fromDate)), date("d-m-Y", strtotime($toDate))]);
        }
        if ($shape) {
            $list->where('tbl_diamond_list.shape', '=', $shape);
        }
        if ($color) {
            $list->where('tbl_diamond_list.color', '=', $color);
        }
        if ($fromWeight && $toWeight) {
            $list->whereBetween('tbl_diamond_list.weight', [$fromWeight, $toWeight]);
        }

        $list = $list->where('tbl_diamond_list.remaining_weight', '>', 0)
            ->select('tbl_diamond_list.*')
            ->groupBy('tbl_diamond_list.lab_no')
            ->get();

        if ($list->count() === 0) {
            return response([
                'success' => true,
                'data' => [],
                'message' => 'Client Not Assign'
            ], 200);
        }

        $data['success'] = true;
        $data['data'] = $list;
        $data['message'] = "Successfully Show AgentWise Client Record";

        return response()->json($data);
}

public function DateWise(Request $request)
{
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
    $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

    $fromDate = $request->fromDate;
    $toDate = $request->toDate;
    // $color = $request->color;
     $shape = $request->shape;
    $fromWeight = $request->fromWeight;
    $toWeight = $request->toWeight;
    $clarities = $request->clarity;
    $replaceclr=str_replace("'", '"', $clarities);
    $clarity=json_decode($replaceclr);


    $colors = $request->color;
    $replacecolr=str_replace("'", '"', $colors);
    $color=json_decode($replacecolr);
    $from = $request->from;

    if($from=="all"){
        $list = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
        ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
        ->where('agent', '=', $accessToken->name);
    }else{
    $list = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
    ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
    ->leftjoin('tbl_order_accept', 'tbl_order_accept.item_id', '=', 'tbl_diamond_list.id')
    ->where('agent', '=', $accessToken->name);
    }

    if ($fromDate && $toDate) {
        $list->whereBetween(DB::raw("DATE_FORMAT(tbl_diamond_list.shipment_date, '%d-%m-%Y')"), [date("d-m-Y", strtotime($fromDate)), date("d-m-Y", strtotime($toDate))]);
    }
    if ($shape) {
        $list->where('tbl_diamond_list.shape', '=', $shape);
    }
    if ($colors) {
        $list->whereIn('tbl_diamond_list.color', $color);
    }
    if ($fromWeight && $toWeight) {
        $list->whereBetween('tbl_diamond_list.weight', [$fromWeight, $toWeight]);
    }

    if ($clarities) {
     $list->whereIn('tbl_diamond_list.clarity',$clarity)->get();
    }

    if($from=="all"){
        $list = $list->get();
    }else{
    $list = $list->where('tbl_order_accept.status','=',1)
        ->select('tbl_diamond_list.*','tbl_party.mob as contact_no')
        ->groupBy('tbl_diamond_list.lab_no')
        ->get();
    }

    if ($list->count() === 0) {
        return response([
            'success' => true,
            'data' => [],
            'message' => 'Record Not Found'
        ], 200);
    }

    $data['success'] = true;
    $data['data'] = $list;
    $data['message'] = "Successfully Show Shipment DateWise Record";

    return response()->json($data);
}
public function AddToCart(Request $request)
{
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
 $accessToken = Agent::where('token', $tokenWithoutBearer)->first();
      $string =$request->labno;
    $lab_no = explode(',', $string);
    $labno = array_map('trim', $lab_no);

    // Trim square brackets from the resulting array
    $labno = array_map(function($item) {
        return trim($item, "[]");
    }, $labno);

       $result = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
            ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
            ->whereIn('lab_no', $labno)
            ->where('agent', '=', $accessToken->name)
            ->get();
        $response = [];
        foreach ($result as $item) {
                $lab = $item->lab;
                $lab_no = $item->lab_no;
                $client = $item->client;
                $cartinfo=CartList::where('lab_no','=',$lab_no)->first();
                $usercart=CartList::where('user_id','=',$accessToken->id)->first();

                if($usercart){
                $exiest=DiamondList::where('lab_no','=',$usercart->lab_no)->first();
                $exclient=$exiest->client;
                }else{
                    $exiest="";
                    $exclient="";
                }
                if(!$cartinfo){
                    if($exiest){
                    if($exclient==$client){
                    $info=new CartList;
                    $info->user_id=$accessToken->id;
                    $info->lab_no=$lab_no;
                    $info->save();
                    $msg="Item Added Successfully";
                    }else{
                        $msg="Please Select Same Client";
                    }
                }else{
                    $info=new CartList;
                    $info->user_id=$accessToken->id;
                    $info->lab_no=$lab_no;
                    $info->save();
                    $msg="Item Added Successfully";
                }
                }else{
                    $msg="Already Exiest";
                }
                if (!isset($response[$lab])) {
                    $response[$lab] = [];
                }
                $response[$lab][] = $item;
            }
        $response = ['data' => $response];
        $data['success'] = true;
        $data['data'] =[];
        $data['message'] = $msg;
        return response()->json($data);
}

public function GetCartList(Request $request)
    {
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
    $accessToken = Agent::where('token', $tokenWithoutBearer)->first();
    $cartinfo = CartList::where('user_id', '=', $accessToken->id)->pluck('lab_no');
    $labno = $cartinfo->toArray();

    $result = DiamondList::leftJoin('tbl_agent', 'tbl_agent.name', '=', 'tbl_diamond_list.agent')
    ->leftjoin('tbl_party', 'tbl_party.name', '=', 'tbl_diamond_list.client')
    ->whereIn('lab_no', $labno)
    ->select('tbl_diamond_list.*' ,'tbl_party.id as party_id','tbl_agent.id as agent_id','tbl_party.mob as contact_no')
    ->where('agent', '=', $accessToken->name)
    ->get();
    $response = [];
        foreach ($result as $item) {
                $lab = $item->lab;
                $labno = $item->lab_no;

            $cartinfo=CartList::where('lab_no','=',$labno)->first();
            if(!$cartinfo){
                $info=new CartList;
                $info->user_id=$accessToken->id;
                $info->lab_no=$labno;
                $info->save();
            }
            if (!isset($response[$lab])) {
                $response[$lab] = [];
            }
            $response[$lab][] = $item;
        }
        if($response){
            $responsedata=$response;
        }else{
            $responsedata=null;
        }
        // $response = ['data' => $response];
        $data['success'] = true;
        $data['data'] = $responsedata;
        $data['message'] = "Get Record SuccessFully";
        return response()->json($data);
  }

  public function DeleteCart(Request $request)
{
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
    $accessToken = Agent::where('token', $tokenWithoutBearer)->first();
    $labno = $request->labno;

    $cartinfo = CartList::where('user_id', '=', $accessToken->id)->where('lab_no','=',$labno)->first();
    if($cartinfo){
        $cartinfo->delete();
        $data['success'] = true;
        $data['data'] = [];
        $data['message'] = "Delete Record SuccessFully";
    }else{
        $data['success'] = true;
        $data['data'] = [];
        $data['message'] = "Invalid";
    }
    return response()->json($data);

}

public function MultiOrderAccept(Request $request)
{
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
    $accessToken = Agent::where('token', $tokenWithoutBearer)->first();

    $stringparty =$request->party_id;
    $stringitem =$request->item_id;
    $stringamount =$request->amount;
    $stringweight =$request->weight;


    $party = explode(',', $stringparty);
    $item_id = explode(',', $stringitem);
    $amount = explode(',', $stringamount);
    $weight = explode(',', $stringweight);


    $partyid = array_map('trim', $party);
    $itemid = array_map('trim', $item_id);
    $amountdata = array_map('trim', $amount);
    $weightdata = array_map('trim', $weight);



    $partyIds = array_map(function($item1) {
        return trim($item1, "[]");
    }, $partyid);

    $itemIds = array_map(function($item2) {
        return trim($item2, "[]");
    }, $itemid);

     $amounts = array_map(function($item3) {
        return trim($item3, "[]");
    }, $amountdata);

    $weights = array_map(function($item4) {
        return trim($item4, "[]");
    }, $weightdata);



    // $partyIds = is_array($request->party_id) ? $request->party_id : explode(',', $pid);
    // $itemIds = is_array($request->item_id) ? $request->item_id : explode(',', $item_id);
    // $amounts = is_array($request->amount) ? $request->amount : explode(',', $amount);
    // $weights = is_array($request->weight) ? $request->weight : explode(',', $weight);

      $items = DiamondList::whereIn('id', $itemIds)->get();
    foreach ($items as $key => $item) {
        if (isset($weights[$key])) {
            $remaining = floatval($item->remaining_weight) - floatval($weights[$key]);
            $round = round($remaining, 2);
            $item->remaining_weight = $round;

            if ($remaining <= 0) {
                $item->status = 1;
            } else {
                $item->status = 0;
            }
            $item->save();
        }
    }
    if ($accessToken) {
        $data = [];
        foreach ($partyIds as $key => $partyId) {
            $accept = new OrderAccept();
            $accept->agent_id = $accessToken->id;
            $accept->date = $request->date;
            $accept->payment = $request->payment;
            $accept->party_id = $partyId;
            $accept->item_id = $itemIds[$key];
            $accept->amount = $amounts[$key];
            $accept->remaining_amount = $amounts[$key];
            $accept->weight = $weights[$key];
            $accept->status = 1;
            $accept->type = "Debit";
            $accept->save();
            $data[] = $accept;
        }
        $cartinfo = CartList::where('user_id', '=', $accessToken->id)->get();
        foreach($cartinfo as $row){
            $row->delete();
        }
        return response()->json(['success' => true, 'data' => $data, 'message' => 'Successfully Accept Orders']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid access token']);
    }
}

public function ExcelGetCartList(Request $request)
{
    $authorization = $request->header('Authorization');
    $tokenWithoutBearer = str_replace("Bearer ", "", $authorization);
    $accessToken = Agent::where('token', $tokenWithoutBearer)->first();
    $cartinfo = CartList::where('user_id', '=', $accessToken->id)->pluck('lab_no');
    $labno = $cartinfo->toArray();


    $excelFileName = 'cart_list_' . time() . '.xlsx';
    $filePath = 'public/excel/' . $excelFileName;

    Excel::store(new GenerateExcel($accessToken, $labno), $filePath);

    $data['success'] = true;
    $data['message'] = "Excel file generated successfully";
    $data['link'] = url('storage/app/public/excel/' . $excelFileName);
    return response()->json($data);
}
}
