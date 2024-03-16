<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Index(){

        $agent =  Agent::all();
        return view('Backend.Agent',compact('agent'));
    }
    public function AddAgent(Request $request)
    {
        if($request->hidden_id){
            $validator = Validator::make($request->all(),[
                'mob' => 'unique:tbl_agent,mob,'.$request->hidden_id,
                 ]);
                if($validator->fails()){
                    $error = json_decode($validator->errors());
                    foreach($error as $err){
                    $response['error']=$err[0];
                    }
                }else{
                     $agent=Agent::find($request->hidden_id);
                            $agent->name=$request->name;
                            $agent->mob=$request->mob;
                            $agent->password=bcrypt($request->password);
                            $agent->pass_txt=$request->password;
                            $agent->status = 0;
                            $agent->save();
                            $response['success']='Update Agent Successfully!';
                    }
                    }else{
                    $validator = Validator::make($request->all(),[
                        'mob' => 'unique:tbl_agent,mob',
                        ]);
                    if($validator->fails()){
                        $error = json_decode($validator->errors());
                         foreach($error as $err){
                        $response['error']=$err[0];
                         }
                        }else{
                        $agent = new Agent;
                        $agent->name=$request->name;
                        $agent->mob=$request->mob;
                        $agent->password=bcrypt($request->password);
                        $agent->pass_txt=$request->password;
                        $agent->status = 0;
                        $agent->save();
                        $response['success'] = 'Successfully create a new Agent!';
                    }
                }
            return response()->json($response);
        }
    public function EditAgent(Request $request){

        $response=Agent::find($request->id);
       return response()->json($response);
    }
    public function DeleteAgent(Request $request){
        $res=Agent::find($request->id);
        if($res){
        $res->delete();
        $data['success']="Delete Record Successfully";
        }else{
            $data['error']="Agent Not Found";

        }
        return response()->json($data);
    }
}
