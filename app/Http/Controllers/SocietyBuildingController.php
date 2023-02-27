<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Societybuilding;

use Illuminate\Support\Facades\Validator;


class SocietyBuildingController extends Controller
{
    //
    public function addsocietybuilding(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'pid' => 'required|exists:phases,id',
            'societybuildingname' => 'required|string',

        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $societybuildingresident = new Societybuilding;

        $societybuildingresident->pid = $request->pid;
        $societybuildingresident->societybuildingname = $request->societybuildingname;
        $societybuildingresident->save();




        return response()->json(
            [

                "success" => true,
                "message" => "Society Building Register to our system Successfully",
                "data" => $societybuildingresident,

            ]
        );
    }


    

    public function societybuildings($pid)
    {
        

        $societybuildingresident = Societybuilding::where('pid', $pid)->get();





        return response()->json([
            "success" => true,
            "data" => $societybuildingresident,

        ]);
    }

}