<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Party;

class PartyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Index(){
        $party = Party::all();
        return view('Backend.Party',compact('party'));
    }
    public function AddParty(Request $request)
    {
        if($request->hidden_id){
        $validator = Validator::make($request->all(),[
            'name' => 'unique:tbl_party,name,'.$request->hidden_id,
             ]);
            if($validator->fails()){
                $error = json_decode($validator->errors());
                foreach($error as $err){
                $response['error']=$err[0];
                }
            }else{
                 $party=Party::find($request->hidden_id);
                        $party->name=$request->name;
                        $party->address=$request->address;
                        $party->mob=$request->mob;
                        $party->save();
                        $response['success']='Update Party Successfully!';
                }
                }else{
                $validator = Validator::make($request->all(),[
                    'name' => 'unique:tbl_party,name',
                    ]);
                if($validator->fails()){
                    $error = json_decode($validator->errors());
                     foreach($error as $err){
                    $response['error']=$err[0];
                     }
                    }else{
                    $party = new Party;
                    $party->name = $request->name;
                    $party->address = $request->address;
                    $party->mob = $request->mob;
                    $party->save();
                    $response['success'] = 'Successfully create a new Party!';
                }
            }
        return response()->json($response);
    }
    public function EditParty(Request $request){

        $response=Party::find($request->id);
       return response()->json($response);
    }
    public function DeleteParty(Request $request){
        $res=Party::find($request->id);
        if($res){
        $res->delete();
        $data['success']="Delete Record Successfully";
        }else{
            $data['error']="Party Not Found";

        }
        return response()->json($data);
    }
}
