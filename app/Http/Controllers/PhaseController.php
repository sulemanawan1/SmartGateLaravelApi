<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhaseController extends Controller
{

    public function addphases(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'subadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            'from' => 'required|integer',
            'to' => 'required|integer|gt:from',

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $phases = new Phase();
        $from = (int) $request->from;
        $to = (int) $request->to;


        for ($i = $from; $i < $to + 1; $i++) {


            $status = $phases->insert(
                [

                    [
                        "name" => 'Phase ' . $i,
                        'subadminid' => $request->subadminid,
                        'societyid' => $request->societyid,

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



    public function phases($subadminid)
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
        $phases =  Phase::where('subadminid', $subadminid)->get();





        return response()->json([
            "success" => true,
            "data" => $phases,
        ]);
    }



    public function distinctphases($subadminid)

    {

        $blocks =  Phase::where('subadminid', $subadminid)->join('blocks', 'blocks.pid', '=', 'phases.id')->distinct()->get();
        $res = $blocks->unique('pid');

        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }


    public function viewphasesforresidents($societyid)
    {
        $phase = Phase::where('societyid',$societyid)->get();

        return response()->json(["data" => $phase]);
    }


}
