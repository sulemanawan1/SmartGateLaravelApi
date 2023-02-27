<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PropertyController extends Controller
{
    public function addproperties(Request $request)
    {

     $isValidate = Validator::make($request->all(), [

            'sid'=>'required|exists:streets,id',
             'from'=>'required',
             'to'=>'required|gt:from',
             'type'=>'required',
             'typeid'=>'required',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
                    $properties= new Property();
                    $from =(int) $request->from;
                    $to =(int) $request->to;

        for($i=$from;$i<$to;$i++){


        $status= $properties->insert(    [

                [
                "address"=>'House no '.$i,
                'sid'=>$request->sid,
                'type'=>$request->type,
                'typeid'=>$request->typeid,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s')],

        ]);

            }


        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }




public function properties($sid)
{
    $properties =  Property::where('sid', $sid)->get();
    

    return response()->json([
        "success" => true,
        "data" => $properties,
    ]);

}
public function viewpropertiesforresidents($streetid)
    {
        $properties = Property::where('sid',$streetid)->get();
        return response()->json(["data" => $properties]);
    }


}
