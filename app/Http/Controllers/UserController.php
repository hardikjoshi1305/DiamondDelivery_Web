<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function Index(){

        $user =  Agent::where('status','=',1)->get();
        return view('Backend.user',compact('user'));
    }
    public function AddUser(Request $request)
    {
        if($request->hidden_id){
            $validator = Validator::make($request->all(), [
                'mob' => 'unique:tbl_agent,mob,'.$request->hidden_id,
            ]);

                if($validator->fails()){
                    $error = json_decode($validator->errors());
                    foreach($error as $err){
                    $response['error']=$err[0];
                    }
                }else{
                     $user=Agent::find($request->hidden_id);
                            $user->name=$request->name;
                            $user->mob=$request->mob;
                            $user->password=bcrypt($request->password);
                            $user->pass_txt=$request->password;
                            $user->status = 1;
                            $user->save();
                            $response['success']='Update User Successfully!';
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
                        $user = new Agent;
                        $user->name=$request->name;
                        $user->mob=$request->mob;
                        $user->password=bcrypt($request->password);
                        $user->pass_txt=$request->password;
                        $user->status = 1;
                        $user->save();
                        $response['success'] = 'Successfully create a new User!';
                    }
                }
            return response()->json($response);
        }
    public function EditUser(Request $request){

        $response=Agent::find($request->id);
       return response()->json($response);
    }
    public function DeleteUser(Request $request){
        $res=Agent::find($request->id);
        if($res){
        $res->delete();
        $data['success']="Delete Record Successfully";
        }else{
            $data['error']="User Not Found";

        }
        return response()->json($data);
    }
}
