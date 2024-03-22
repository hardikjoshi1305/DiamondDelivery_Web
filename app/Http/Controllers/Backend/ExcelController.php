<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiamondList;
use League\Csv\Reader;
use App\Models\Agent;
use App\Models\Party;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use File;
use Illuminate\Http\Response;
use App\Models\Shipment;
use Illuminate\Support\Stringable;
use DB;
use App\Models\User;

class ExcelController extends Controller
{
    protected function cleanString($string)
    {
        return Str::of($string)->trim();
    }
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Index()
    {
        $shipment = Shipment::all();
        $agent = Agent::all();
        $party = Party::all();
        return view('Backend.Excel', compact('agent', 'party', 'shipment'));
    }
    public function UploadExcelOLD(Request $request)
    {
        // return $request;
        $excelFile = $request->file('excelFile');
        $countData = 0;
        $Recdata = 0;
        $skipData = 0;
        $SkipRec = 0;
        $filename = $excelFile->getClientOriginalName();
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueAgentNames = [];
        if ($ext == 'csv') {
            $name = time() . '-' . $filename;
            $reader = Reader::createFromPath($excelFile->getRealPath());
            foreach ($reader as $index => $row) {
                if ($index == 0 || !array_filter($row)) {
                    continue;
                }
                $clientName = $row[3];


                $matchingClient = Party::where('name', '=', $clientName)->first();
                if ($matchingClient) {
                    $flag[] = "1";
                } else {

                    $response['error'] = "No matching client found for: $clientName";
                    return redirect()->back()->with('error', $response['error']);
                }

            }


            foreach ($reader as $index => $row) {
                if ($index == 0 || !array_filter($row)) {
                    continue;
                }
                $chunks = array_chunk($row, 100);
                foreach ($chunks as $row) {
                    $labNo = isset($row[14]) ? $this->cleanString($row[14]) : '';
                    if (empty(trim($labNo))) {
                        $randomNumber = random_int(1000, 9999);
                        $labNo = 'DIMD' . $randomNumber;
                    }
                    if (DiamondList::where('lab_no', $labNo)->exists()) {
                        $SkipRec++;
                        continue;
                    }
                    $weightdata = isset($row[5]) ? floatval(strval($this->cleanString($row[5]))) : 0;
                    $ratedata = isset($row[20]) ? floatval(strval($this->cleanString($row[20]))) : 0;
                    $totaldata = isset($row[21]) ? floatval(strval($this->cleanString($row[21]))) : 0;
                    $weight = $weightdata;
                    $rate = $ratedata;
                    $total = $totaldata;
                    if ($ratedata != 0) {
                        $rate = $ratedata;
                        if ($totaldata != 0) {
                            $weight = $totaldata / $rate;
                            $total = $totaldata;
                        }
                    } elseif ($totaldata != 0 && $weightdata != 0) {
                        $total = $totaldata;
                        $weight = $weightdata;
                        $rate = $total / $weight;
                    } elseif ($weightdata != 0 && $ratedata != 0) {
                        $weight = $weightdata;
                        $rate = $ratedata;
                        $total = $rate * $weight;
                    } else {
                    }
                    if ($total == 0) {
                        $weight = $weightdata;
                        $rate = $ratedata;
                        $total = $rate * $weight;
                    }
                    $data1 = new DiamondList;
                    if ($request->date) {

                        $data1['shipment_date'] = $request->date;
                        $data1['shipment_mode'] = $request->shipment_mode;
                        $data1['sn_no'] = (isset($row[0]) && $row[0] != '') ? $this->cleanString($row[0]) : '';
                        $data1['sell_date'] = (isset($row[1]) && $row[1] != '') ? $this->cleanString($row[1]) : '';
                        $data1['sold_by'] = (isset($row[2]) && $row[2] != '') ? $this->cleanString($row[2]) : '';


                        //   $data1['client']=(isset($row[3]) && $row[3] != '') ? $this->cleanString($row[3]) : '';
                        $clientName = (isset($row[3]) && $row[3] != '') ? $this->cleanString($row[3]) : '';
                        $matchingClient = Party::where('name', $clientName)->first();
                        if (!$matchingClient) {
                            continue;
                        }

                        $data1['client'] = $clientName;
                        //   $data1['contact_no']=(isset($row[4]) && $row[4] != '') ? $this->cleanString($row[4]) : '';
                        $data1['shape'] = (isset($row[4]) && $row[4] != '') ? $this->cleanString($row[4]) : '';
                        $data1['weight'] = round($weight, 2);
                        $data1['pcs'] = (isset($row[6]) && $row[6] != '') ? $this->cleanString($row[6]) : '';
                        $data1['color'] = (isset($row[7]) && $row[7] != '') ? $this->cleanString($row[7]) : '';
                        $data1['clarity'] = (isset($row[8]) && $row[8] != '') ? $this->cleanString($row[8]) : '';
                        $data1['cut'] = (isset($row[9]) && $row[9] != '') ? $this->cleanString($row[9]) : '';
                        $data1['pol'] = (isset($row[10]) && $row[10] != '') ? $this->cleanString($row[10]) : '';
                        $data1['symm'] = (isset($row[11]) && $row[11] != '') ? $this->cleanString($row[11]) : '';
                        $data1['floro'] = (isset($row[12]) && $row[12] != '') ? $this->cleanString($row[12]) : '';
                        $data1['lab'] = (isset($row[13]) && $row[13] != '') ? $this->cleanString($row[13]) : '';
                        $data1['lab_no'] = $labNo;
                        $data1['mm1'] = (isset($row[15]) && $row[15] != '') ? $this->cleanString($row[15]) : '';
                        $data1['mm2'] = (isset($row[16]) && $row[16] != '') ? $this->cleanString($row[16]) : '';
                        $data1['mm3'] = (isset($row[17]) && $row[17] != '') ? $this->cleanString($row[17]) : '';
                        $data1['table'] = (isset($row[18]) && $row[18] != '') ? $this->cleanString($row[18]) : '';
                        $data1['td'] = (isset($row[19]) && $row[19] != '') ? $this->cleanString($row[19]) : '';
                        $data1['rate'] = round($rate, 2);
                        $data1['total'] = round($total, 2);
                        $data1['agent'] = (isset($row[22]) && $row[22] != '') ? $this->cleanString($row[22]) : '';
                        $data1['remaining_weight'] = round($weight, 2);
                        if (!empty($data1['agent'])) {
                            $uniqueAgentNames[] = $data1['agent'];
                        }
                        $data1->save();
                        //   $uniqueAgentNames[] = $data1['agent'];
                        $Recdata = ++$countData;
                    } else {
                        $SkipRec = ++$skipData;
                    }
                    $aa = $uniqueAgentNames;
                }
            }
            $uniqueAgentNames = array_values(array_unique($uniqueAgentNames));
            $uniqueids = DB::table('tbl_agent')->whereIn('tbl_agent.name', $uniqueAgentNames)->select('id')->get();
            foreach ($uniqueids as $unique) {
                $ids = $unique->id;
                $url1 = 'https://fcm.googleapis.com/fcm/send';
                $apiKey = 'AAAAYXZOU4E:APA91bHLjWXbfl1_z_yVwkfafn_L7ry9Mdg9J9SNezdWJMnr5R7Siip0Wc3rpYowZvjzrF9BhHBTOkvtYdjnz5MhJpUWjXwpY_D2yHZqzfTV_FIIosgavl81HP0K1Z1R5YroZbfix3F6';
                $headers = array(
                    'Authorization:key=' . $apiKey,
                    'Content-Type:application/json'
                );
                $notifData = [
                    "title" => "New Tasks",
                    "body" => "You have a new task!"
                ];
                $dataPayload = [
                    'to' => $ids,
                    "title" => "New Tasks",
                    "body" => "You have a new task!"
                ];
                $apiBody = array(
                    'data' => $dataPayload,
                    'notification' => $notifData,
                    'to' => "/topics/$ids",
                    'priority' => 'high'
                    // 'to' => '/topics/all'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url1);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
                $result = curl_exec($ch);
                // echo $result;
            }
            $response['success'] = "Total Record Import $Recdata, Total Record Skip $SkipRec";
            return redirect()->back()->with('success', $response['success']);

        } else {
            $response['error'] = "Only Csv File Allow";
        }
    }


    public function UploadExcel(Request $request)
    {
        try {
            DB::beginTransaction();
            $excelFile = $request->file('excelFile');
            $countData = 0;
            $Recdata = 0;
            $skipData = 0;
            $SkipRec = 0;
            $filename = $excelFile->getClientOriginalName();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $uniqueAgentNames = [];


            if ($ext == 'csv') {

                $name = time() . '-' . $filename;
                $reader = Reader::createFromPath($excelFile->getRealPath());
                foreach ($reader as $index => $row) {
                    if ($index == 0 || !array_filter($row)) {
                        continue;
                    }
                    $chunks = array_chunk($row, 100);
                    foreach ($chunks as $row) {
                        $clientName = (isset($row[3]) && $row[3] != '') ? $this->cleanString($row[3]) : '';
                        $matchingClient = Party::where('name', $clientName)->first();
                        if (!$matchingClient) {
                            throw new \Exception("No matching client found for: $clientName");
                        }

                        $labNo = isset($row[14]) ? $this->cleanString($row[14]) : '';
                        if (empty(trim($labNo))) {
                            $randomNumber = random_int(1000, 9999);
                            $labNo = 'DIMD' . $randomNumber;
                        }
                        if (DiamondList::where('lab_no', $labNo)->exists()) {
                            $SkipRec++;
                            continue;
                        }
                        $weightdata = isset($row[5]) ? floatval(strval($this->cleanString($row[5]))) : 0;
                        $ratedata = isset($row[20]) ? floatval(strval($this->cleanString($row[20]))) : 0;
                        $totaldata = isset($row[21]) ? floatval(strval($this->cleanString($row[21]))) : 0;
                        $weight = $weightdata;
                        $rate = $ratedata;
                        $total = $totaldata;
                        if ($ratedata != 0) {
                            $rate = $ratedata;
                            if ($totaldata != 0) {
                                $weight = $totaldata / $rate;
                                $total = $totaldata;
                            }
                        } elseif ($totaldata != 0 && $weightdata != 0) {
                            $total = $totaldata;
                            $weight = $weightdata;
                            $rate = $total / $weight;
                        } elseif ($weightdata != 0 && $ratedata != 0) {
                            $weight = $weightdata;
                            $rate = $ratedata;
                            $total = $rate * $weight;
                        } else {
                        }
                        if ($total == 0) {
                            $weight = $weightdata;
                            $rate = $ratedata;
                            $total = $rate * $weight;
                        }
                        $data1 = new DiamondList;
                        if ($request->date) {

                            $data1['shipment_date'] = $request->date;
                            $data1['shipment_mode'] = $request->shipment_mode;
                            $data1['sn_no'] = (isset($row[0]) && $row[0] != '') ? $this->cleanString($row[0]) : '';
                            $data1['sell_date'] = (isset($row[1]) && $row[1] != '') ? $this->cleanString($row[1]) : '';
                            $data1['sold_by'] = (isset($row[2]) && $row[2] != '') ? $this->cleanString($row[2]) : '';

                            $data1['client'] = $clientName;
                            $data1['shape'] = (isset($row[4]) && $row[4] != '') ? $this->cleanString($row[4]) : '';
                            $data1['weight'] = round($weight, 2);
                            $data1['pcs'] = (isset($row[6]) && $row[6] != '') ? $this->cleanString($row[6]) : '';
                            $data1['color'] = (isset($row[7]) && $row[7] != '') ? $this->cleanString($row[7]) : '';
                            $data1['clarity'] = (isset($row[8]) && $row[8] != '') ? $this->cleanString($row[8]) : '';
                            $data1['cut'] = (isset($row[9]) && $row[9] != '') ? $this->cleanString($row[9]) : '';
                            $data1['pol'] = (isset($row[10]) && $row[10] != '') ? $this->cleanString($row[10]) : '';
                            $data1['symm'] = (isset($row[11]) && $row[11] != '') ? $this->cleanString($row[11]) : '';
                            $data1['floro'] = (isset($row[12]) && $row[12] != '') ? $this->cleanString($row[12]) : '';
                            $data1['lab'] = (isset($row[13]) && $row[13] != '') ? $this->cleanString($row[13]) : '';
                            $data1['lab_no'] = $labNo;
                            $data1['mm1'] = (isset($row[15]) && $row[15] != '') ? $this->cleanString($row[15]) : '';
                            $data1['mm2'] = (isset($row[16]) && $row[16] != '') ? $this->cleanString($row[16]) : '';
                            $data1['mm3'] = (isset($row[17]) && $row[17] != '') ? $this->cleanString($row[17]) : '';
                            $data1['table'] = (isset($row[18]) && $row[18] != '') ? $this->cleanString($row[18]) : '';
                            $data1['td'] = (isset($row[19]) && $row[19] != '') ? $this->cleanString($row[19]) : '';
                            $data1['rate'] = round($rate, 2);
                            $data1['total'] = round($total, 2);
                            $data1['agent'] = (isset($row[22]) && $row[22] != '') ? $this->cleanString($row[22]) : '';
                            $data1['remaining_weight'] = round($weight, 2);
                            if (!empty($data1['agent'])) {
                                $uniqueAgentNames[] = $data1['agent'];
                            }
                            $data1->save();
                            $Recdata = ++$countData;
                        } else {
                            $SkipRec = ++$skipData;
                        }
                        $aa = $uniqueAgentNames;
                    }
                }
                DB::commit();
                $uniqueAgentNames = array_values(array_unique($uniqueAgentNames));
                $uniqueids = DB::table('tbl_agent')->whereIn('tbl_agent.name', $uniqueAgentNames)->select('id')->get();
                foreach ($uniqueids as $unique) {
                    $ids = $unique->id;
                    $url1 = 'https://fcm.googleapis.com/fcm/send';
                    $apiKey = 'AAAAYXZOU4E:APA91bHLjWXbfl1_z_yVwkfafn_L7ry9Mdg9J9SNezdWJMnr5R7Siip0Wc3rpYowZvjzrF9BhHBTOkvtYdjnz5MhJpUWjXwpY_D2yHZqzfTV_FIIosgavl81HP0K1Z1R5YroZbfix3F6';
                    $headers = array(
                        'Authorization:key=' . $apiKey,
                        'Content-Type:application/json'
                    );
                    $notifData = [
                        "title" => "New Tasks",
                        "body" => "You have a new task!"
                    ];
                    $dataPayload = [
                        'to' => $ids,
                        "title" => "New Tasks",
                        "body" => "You have a new task!"
                    ];
                    $apiBody = array(
                        'data' => $dataPayload,
                        'notification' => $notifData,
                        'to' => "/topics/$ids",
                        'priority' => 'high'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url1);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
                    $result = curl_exec($ch);
                }
                $response['success'] = "Total Record Import $Recdata, Total Record Skip $SkipRec";
                return redirect()->back()->with('success', $response['success']);

            } else {
                $response['error'] = "Only Csv File Allow";
            }
        } catch (\Exception $clientException) {
            DB::rollBack();

            \Log::error($clientException);

            $response['error'] = "Error importing records. " . $clientException->getMessage();
            return redirect()->back()->with('error', $response['error']);
        }
    }

    public function getfile(Request $request, $filename)
    {
        $filePath = public_path('upload/' . $filename);

        if (file_exists($filePath)) {
            $desiredFilename = 'sample.csv';
            return response()->download($filePath, $desiredFilename);
        } else {
            return "File not found";
        }
    }
    public function DiamondList()
    {
        $diamond = DiamondList::all();
        return view('Backend.DiamondList', compact('diamond'));
    }
    public function Edit(Request $request)
    {
        $agent = Agent::all();
        $diamond = DiamondList::find($request->id);
        return view('Backend.edit_diamond', compact('diamond', 'agent'));
    }
    public function UpdateDiamond(Request $request)
    {
        $diamond = DiamondList::find($request->hidden_id);

        $diamond->shipment_date = $request->date;
        $diamond->shipment_mode = $request->shipment_mode;
        $diamond->sell_date = $request->sell_date;
        $diamond->sold_by = $request->sold_by;
        $diamond->client = $request->client;
        $diamond->shape = $request->shape;
        $diamond->weight = $request->weight;
        $diamond->color = $request->color;
        $diamond->clarity = $request->clarity;
        $diamond->cut = $request->cut;
        $diamond->pol = $request->pol;
        $diamond->symm = $request->symm;
        $diamond->floro = $request->floro;
        $diamond->lab = $request->lab;
        $diamond->lab_no = $request->lab_no;
        $diamond->pcs = $request->pcs;
        $diamond->rate = $request->rate;
        $diamond->mm1 = $request->mm1;
        $diamond->mm2 = $request->mm2;
        $diamond->mm3 = $request->mm3;
        $diamond->table = $request->table;
        $diamond->td = $request->td;
        $diamond->total = $request->total;
        $diamond->agent = $request->agent;
        $diamond->save();

        return redirect()->route('diamond.list')->with('success', 'Diamond Updated successfully.');
    }
    public function deleteDiamond($id)
    {

        $diamond = DiamondList::find($id);
        $diamond->delete();
        return redirect()->route('diamond.list')->with('error', 'Diamond deleted successfully.');
    }
}
