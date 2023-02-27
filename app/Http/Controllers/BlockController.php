<?php

namespace App\Http\Controllers;
use App\Models\Block;
use App\Models\Street;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BlockController extends Controller
{
    // public function addblocks(Request $request)

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

    //     $blocks = new Blocks();
    //     $requestData = $request->data;
    //     $myArray = array();
    //     $data = json_decode($requestData, true);
    //     foreach ($data as $li) {

    //         array_push($myArray, $li);
    //     }

    //     for ($i = 0; $i < count($myArray); $i++) {


    //         for ($j = $myArray[$i]['from']; $j < $myArray[$i]['to'] + 1; $j++) {


    //             // print($myArray[$i]['pid']);


    //             $status = $blocks->insert([

    //                 [
    //                     'pid' => $myArray[$i]['pid'],
    //                     "name" => 'Block ' . $j,
    //                     'created_at' => date('Y-m-d H:i:s'),
    //                     'updated_at' => date('Y-m-d H:i:s')
    //                 ]

    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         "success" => true,
    //         "message" => "Blocks are added successfully",
    //         "data" => $status,
    //     ]);
    // }

    public function addblocks(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
        'pid' => 'required|exists:phases,id',
        'from'=>'required|integer',
        'to'=>'required|integer|gt:from',
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

        $blocks = new Block();

        $from =(int) $request->from;
        $to =(int) $request->to;



        for($i=$from;$i<$to+1;$i++){


            $status= $blocks->insert(
                  [

                    ["name"=>'Block '.$i,
                    'pid'=>$request->pid,
                    'created_at'=>date('Y-m-d H:i:s.u'),
                    'updated_at'=> date('Y-m-d H:i:s.u')],

            ]);

                }

                return response()->json([
                    "success" => true,
                    "data" => $status,
                ]);
    }


    public function distinctblocks($subadminid)

    {

        $blocks= Street::where('bid', $subadminid)->
         join('blocks', 'blocks.id', '=', 'streets.id')
         ->join('phases','phases.id','=','blocks.pid')->distinct()->get();
        $res = $blocks->unique('bid');

        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }




     public function blocks($pid)

    {

        $blocks =  Block::where('pid', $pid)->get();


        return response()->json([
            "success" => true,
            "data" => $blocks,

        ]);
    }

    public function viewblocksforresidents($phaseid)
    {
        $phase = Block::where('pid', $phaseid)->get();
        return response()->json(["data" => $phase]);
    }



}
