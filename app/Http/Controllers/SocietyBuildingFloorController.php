<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Societybuildingfloor;



class SocietyBuildingFloorController extends Controller
{
    public function addsocietybuildingfloors(Request $request)
    {

        $isValidate = Validator::make($request->all(), [


            'buildingid' => 'required|exists:societybuildings,id',

            'from' => 'required|integer',
            'to' => 'required|integer',

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $societybuildingfloor = new Societybuildingfloor();
        $from = (int) $request->from;
        $to = (int) $request->to;


        for ($i = $from; $i < $to + 1; $i++) {


            $status = $societybuildingfloor->insert(
                [

                    [
                        "name" => 'Floor ' . $i,

                        'buildingid' => $request->buildingid,

                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],

                ]
            );
        }

        // $phases->subadminid = $request->subadminid;

        return response()->json([
            "success" => true,
            "data" => $status,
        ]);
    }



    // public function floors($subadminid)
    // {

    //     //  $isValidate = Validator::make($request->all(), [

    //     //         'subadminid' => 'required|exists:users,id',

    //     //     ]);
    //     //     if ($isValidate->fails()) {
    //     //         return response()->json([
    //     //             "errors" => $isValidate->errors()->all(),
    //     //             "success" => false
    //     //         ], 403);
    //     //     }
    //     $phases =  Phase::where('subadminid', $subadminid)->get();





    //     return response()->json([
    //         "success" => true,
    //         "data" => $phases,
    //     ]);
    // }



    // public function distinctfloors($subadminid)

    // {

    //     $blocks =  Phase::where('subadminid', $subadminid)->join('blocks', 'blocks.pid', '=', 'phases.id')->distinct()->get();
    //     $res = $blocks->unique('pid');

    //     return response()->json([
    //         "success" => true,
    //         "data" => $res->values()->all(),
    //     ]);
    // }


    public function viewsocietybuildingfloors($buildingid)
    {
        $societybuildingfloor = Societybuildingfloor::where('buildingid', $buildingid)->get();

        return response()->json(["data" => $societybuildingfloor]);
    }

    public function societybuildingfloor($subadminid)
    {

        //  $isValidate = Validator::make($request->all(), [

        //         'subadminid' => 'required|exists:users,id',

        //     ]);
        //     if ($isValidate->fails()) {
        //         return response()->json([
        //             "errors" => $isValidate->errors()->all(),
        //             "success" => false
        //         ], 403);
        //     }
        $societybuildingfloor =  Societybuildingfloor::where('subadminid', $subadminid)->get();





        return response()->json([
            "success" => true,
            "data" => $societybuildingfloor,
        ]);
    }
}