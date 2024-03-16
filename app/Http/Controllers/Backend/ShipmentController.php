<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $shipment =  Shipment::all();
        return view('Backend.Shipment',compact('shipment'));
    }
    public function AddShipment(Request $request)
    {
        if($request->hidden_id){
            $validator = Validator::make($request->all(),[
                'name' => 'unique:shipment_mode,name,'.$request->hidden_id,
                 ]);
                if($validator->fails()){
                    $error = json_decode($validator->errors());
                    foreach($error as $err){
                    $response['error']=$err[0];
                    }
                }else{
                     $shipment=Shipment::find($request->hidden_id);
                            $shipment->name=$request->name;
                            $shipment->save();
                            $response['success']='Update Shipment Successfully!';
                    }
                    }else{
                    $validator = Validator::make($request->all(),[
                        'name' => 'unique:shipment_mode,name',
                        ]);
                    if($validator->fails()){
                        $error = json_decode($validator->errors());
                         foreach($error as $err){
                        $response['error']=$err[0];
                         }
                        }else{
                        $shipment = new Shipment;
                        $shipment->name=$request->name;
                        $shipment->save();
                        $response['success'] = 'Successfully create a new Shipment!';
                    }
                }
            return response()->json($response);
        }
        public function EditShipment(Request $request){

            $response=Shipment::find($request->id);
           return response()->json($response);
        }
        public function DeleteShipment(Request $request){
            $res=Shipment::find($request->id);
            if($res){
            $res->delete();
            $data['success']="Delete Record Successfully";
            }else{
                $data['error']="Shipment Not Found";

            }
            return response()->json($data);
        }
}
