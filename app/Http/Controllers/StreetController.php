<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Street;
use App\Models\Phases;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StreetController extends Controller
{


    // public function addstreets(Request $request)

    // {

    //  $isValidate = Validator::make($request->all(), [

    //         'bid'=>'required|exists:blocks,id',
    //          'from'=>'required',
    //          'to'=>'required',
    //     ]);
    //     if ($isValidate->fails()) {
    //         return response()->json([
    //             "errors" => $isValidate->errors()->all(),
    //             "success" => false
    //         ], 403);
    //     }
    //                 $streets= new Streets();
    //                 $from =(int) $request->from;
    //                 $to =(int) $request->to;

    //     for($i=$from;$i<$to;$i++){


    //     $status= $streets->insert(    [

    //             [
    //             "name"=>'Street no '.$i,
    //             'bid'=>$request->bid,
    //             'created_at'=>date('Y-m-d H:i:s'),
    //             'updated_at'=> date('Y-m-d H:i:s')],

    //     ]);

    //         }


    //     return response()->json([
    //         "success" => true,
    //         "data" => $status,
    //     ]);
    // }

    // public function addstreets(Request $request)

    // {

    //     $isValidate = Validator::make($request->all(), [
    //         'data' => 'required'
    //     ]);

    //     if ($isValidate->fails()) {
    //         return response()->json([
    //             "errors" => $isValidate->errors()->all(),
    //             "success" => false
    //         ], 403);
    //     }

    //     $streets = new Streets();
    //     $requestData = $request->data;
    //     $myArray = array();
    //     $data = json_decode($requestData, true);
    //     foreach ($data as $li) {

    //         array_push($myArray, $li);
    //     }

    //     for ($i = 0; $i < count($myArray); $i++) {


    //         for ($j = $myArray[$i]['from']; $j < $myArray[$i]['to'] + 1; $j++) {


    //             // print($myArray[$i]['pid']);


    //             $status = $streets->insert([

    //                 [
    //                     'bid' => $myArray[$i]['bid'],
    //                     "name" => 'Street ' . $j,
    //                     'created_at' => date('Y-m-d H:i:s'),
    //                     'updated_at' => date('Y-m-d H:i:s')
    //                 ]

    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         "success" => true,
    //         "message" => "Street are added successfully",
    //         "data" => $status,
    //     ]);

    // }



    public function addstreets(Request $request)

    {

        $isValidate = Validator::make($request->all(), [

        'bid' => 'required|exists:blocks,id',
        'from'=>'required|integer',
        'to'=>'required|integer|gt:from',
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }


        $streets = new Street();
        $from =(int) $request->from;
        $to =(int) $request->to;


for($i=$from;$i<$to+1;$i++){


$status= $streets->insert(
  [

    ["name"=>'Street '.$i,
    'bid'=>$request->bid,
    'created_at'=>date('Y-m-d H:i:s'),
    'updated_at'=> date('Y-m-d H:i:s')],

]);

}

// $phases->subadminid = $request->subadminid;

return response()->json([
"success" => true,
"data" => $status,
]);


    }

    public function streets($bid)

    {

        $streets =  Street::where('bid', $bid)->get();

        return response()->json([
            "success" => true,
            "data" => $streets,
        ]);
    }



    public function viewstreetsforresidents($blockid)
    {
        $streets = Street::where('bid',$blockid)->get();
        return response()->json(["data" => $streets]);
    }

}
