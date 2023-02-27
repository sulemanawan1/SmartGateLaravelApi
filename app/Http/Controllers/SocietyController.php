<?php

namespace App\Http\Controllers;

use App\Models\Society;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{

    public function addsociety(Request $request)

    {

        $isValidate = Validator::make($request->all(), [

            'country' => 'required',

            'state' => 'required',


            'city' => 'required',
            'area' => 'required',

            'type' => 'required',


            'name' => 'required',
            'address' => 'required',
            'superadminid' => 'required|exists:users,id',


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        $society = new Society();

        $society->country = $request->country;

        $society->state = $request->state;


        $society->city = $request->city;
        $society->area = $request->area;

        $society->type = $request->type;



        $society->name = $request->name;


        $society->address = $request->address;
        $society->superadminid = $request->superadminid;
        $society->save();


        return response()->json(["data" => $society]);
    }


    public  function updatesociety(Request $request)


    {
        $isValidate = Validator::make($request->all(), [

            'country' => 'required',

            'state' => 'required',


            'city' => 'required',
            'area' => 'required',

            'type' => 'required',


            'name' => 'required',

            'address' => 'required',
            'id' => 'required|exists:societies,id',


        ]);


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }


        $society = Society::find($request->id);

        $society->country = $request->country;

        $society->state = $request->state;



        $society->city = $request->city;
        $society->area = $request->area;

        $society->type = $request->type;



        $society->name = $request->name;

        $society->address = $request->address;
        $society->save();


        return response()->json([
            "success" => true,
            "data" => $society,
            "message" => "update successfully"
        ]);
    }

    public function viewallsocieties($superadminid)

    {

        // dd($userid);

        $society = Society::where('superadminid', $superadminid)->get();


        return response()->json(["data" => $society]);
    }

    public function deletesociety($id)

    {

        // dd($userid);

        $society = Society::where('id', $id)->delete();


        return response()->json(["data" => $society, "message" => "delete successfully"]);
    }



    public function viewsociety($societyid)
    {
        $society = Society::where('id', $societyid)->get();

        return response()->json(["data" => $society]);
    }


    public function viewsocietiesforresidents($type)
    {
        $society = Society::where('type','LIKE' , $type)->orWhere('type','LIKE' , $type)->get();

        return response()->json(["data" => $society]);
    }

    // public function viewbuildingsforresidents()
    // {
    //     $society = Society::where('type','LIKE' , 'building')->get();

    //     return response()->json(["data" => $society]);
    // }



    public function    searchsociety($q)
    {


        $society = Society::where('name', 'LIKE', '%' . $q . '%')->orWhere('address', 'LIKE', '%' . $q . '%')->get();

        return response()->json(["data" => $society]);
    }

    public function filtersocietybuilding($id, $q)
    {


        $society = Society::where('superadminid', $id)->where('type', 'LIKE', '%' . $q . '%')->get();

        return response()->json(["data" => $society]);
    }
}
