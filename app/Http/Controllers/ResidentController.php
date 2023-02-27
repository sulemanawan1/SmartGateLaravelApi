<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resident;
use App\Models\Owner;
use App\Models\Houseresidentaddress;
use App\Models\Apartmentresidentaddress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class ResidentController extends Controller
{



    public function searchresident($subadminid, $q)
    {

        // ->join('users', 'users.id', '=', 'residents.residentid')
        // ->join('owners', 'owners.residentid', "=", 'residents.residentid');

        $data = User::join(
            'residents',
            'users.id',
            '=',
            'residents.residentid'
        )
            ->Where('residents.subadminid', $subadminid)
            ->Where('users.firstname', 'LIKE', '%' . $q . '%')
            ->orWhere('users.lastname', 'LIKE', '%' . $q . '%')
            ->orWhere('users.mobileno', 'LIKE', '%' . $q . '%')
            ->orWhere('users.cnic', 'LIKE', '%' . $q . '%')
            ->orWhere('users.address', 'LIKE', '%' . $q . '%')
            ->orWhere('residents.vechileno', 'LIKE', '%' . $q . '%')->get();


        return response()->json(
            [
                "success" => true,
                "residentslist" => $data
            ]
        );
    }

    public function registerresident(Request $request)


    {

        $isValidate = Validator::make($request->all(), [


            "residentid" => 'required|exists:users,id',
            "subadminid" => 'required|exists:users,id',

            "country" => "required",
            "state" => "required",
            "city" => "required",
            "houseaddress" => "required",
            "residenttype" => "required",
            "propertytype" => "required",
            "committeemember" => "required",
            "status" => "required",
            "vechileno" => "nullable",

            /* owner details */

            "ownername" => "nullable",
            "owneraddress" => "nullable",
            "ownermobileno" => "nullable",

            /* apartment/houses details */

            "societyid"=>"nullable",
            "pid"=>"nullable",
            "bid"=>"nullable",
            "sid"=>"nullable",
            "propertyid" =>"nullable",
            "buildingid"=>"nullable",	
            "societybuildingfloorid"=>"nullable",
            "societybuildingapartmentid"=>"nullable",
            "measurementid"=>"nullable"



        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }



        $resident = new Resident;
        $resident->residentid = $request->residentid;
        $resident->subadminid = $request->subadminid;
        $resident->country = $request->country;
        $resident->state = $request->state;
        $resident->city = $request->city;
        $resident->houseaddress = $request->houseaddress ?? 'NA';
        $resident->vechileno = $request->vechileno??'';
        $resident->residenttype = $request->residenttype;
        $resident->propertytype = $request->propertytype;
        $resident->committeemember = $request->committeemember ?? 0;
        $resident->status = $request->status ?? 0;
        $resident->save();

    if( $request->propertytype=='house')

{
        $address  = new Houseresidentaddress;
        $address->residentid = $request->residentid;
         $address->societyid = $request->societyid;
         $address->pid = $request->pid;
         $address->bid = $request->bid;
         $address->sid = $request->sid;
         $address->propertyid = $request->propertyid;
         $address->measurementid = $request->measurementid;
         $address->save();

}
else {
    $address  = new Apartmentresidentaddress;
    $address->residentid = $request->residentid;
    $address->societyid = $request->societyid;
    $address->pid = $request->pid;
    $address->buildingid=$request->buildingid;
    $address->societybuildingfloorid=$request->societybuildingfloorid;
    $address->societybuildingapartmentid=$request->societybuildingapartmentid;
    $address->measurementid = $request->measurementid;
    $address->save();
}





        $user = User::where('id',$request->subadminid)->get()->first();
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverkey='AAAAcuxXPmA:APA91bEz-6ptcGS8KzmgmSLjb-6K_bva-so3i6Eyji_ihfncqXttVXjdBQoU6V8sKilzLb9MvSHFId-KK7idDwbGo8aXHpa_zjGpZuDpM67ICKM7QMCGUO_JFULTuZ_ApIOxdF3TXeDR';
        $headers = array (
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json'
        );
        $mydata=['registration_ids'=>[$user->fcmtoken],

        "data"=>["data"=>$resident],
        "android"=> [
            "priority"=> "high",
            "ttl"=> 60 * 60 * 1,

        ],
        "notification"=>['title'=>'You have one verification request','body'=>'',
        'description'=>"jani"]

    ];
    $finaldata=json_encode($mydata);
        $headers = array (
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json'
        );
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $finaldata );
        $result = curl_exec ( $ch );
        // var_dump($result);
        curl_close ( $ch );






        if ($resident->residenttype == 'Rental') {
            $owner = new Owner;
            $owner->residentid = $resident->residentid;
            $owner->ownername = $request->ownername ?? "NA";
            $owner->owneraddress = $request->owneraddress ?? "NA";
            $owner->ownermobileno = $request->ownermobileno ?? "NA";
            $owner->save();
        }





        return response()->json(
            [

                "success" => true,
                "message" => "Resident Register to our system Successfully",
                "data" => $resident,

            ]
        );
    }
    
    public function viewresidents($id)


    {
        // $data = Resident::where('subadminid', $id)
        //     ->join('users', 'users.id', '=', 'residents.residentid')

        //     ->join('owners', 'owners.residentid', "=", 'residents.residentid')->paginate(5);


        $data = Resident::where('subadminid', $id)->where('status',1)
            ->join('users', 'users.id', '=', 'residents.residentid')->get();

            // ->join('owners', 'owners.residentid', "=", 'residents.residentid')->get();



        return response()->json(
            [
                "success" => true,
                "residentslist" => $data
            ]
        );
    }


    public function deleteresident($id)

    {

        $resident = Resident::where('residentid', $id)->delete();

        return response()->json([

            "success" => true,
            "data" => $resident,
            "message" => "Resident Deleted successfully"
        ]);
    }


    public function updateresident(Request $request)

    {

        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            // 'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required',
            // 'roleid' => 'required',
            // 'rolename' => 'required',
            // 'password' => 'required',
            'image' => 'nullable|image',
            "id" => 'required|exists:users,id',
            "vechileno" => "nullable",
            "residenttype" => "required",
            "propertytype" => "required",
            "committeemember" => "required",
            "ownername" => "nullable",
            "owneraddress" => "nullable",
            "ownermobileno" => "nullable",

        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        $user = User::Find($request->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        // $user->cnic = $request->cnic;
        if ($request->hasFile('image')) {
            $destination = public_path('storage\\') . $user->image;

            if (File::exists($destination)) {

                unlink($destination);
            }
            $image = $request->file('image');
            $imageName = time() . "." . $image->extension();
            $image->move(public_path('/storage/'), $imageName);

            $user->image = $imageName;
        }
        $user->update();

        $resident = Resident::where('residentid', $request->id)->first();

        $resident->update([
            'vechileno' => $request->vechileno,
            'residenttype' => $request->residenttype,
            'propertytype' => $request->propertytype,
            'committeemember' => $request->committeemember,
        ]);


        if ($request->residenttype == "Rental") {
            $owner =  Owner::where('residentid', $request->id)->first();
            $owner->ownername = $request->ownername;
            $owner->owneraddress = $request->owneraddress;
            $owner->ownermobileno = $request->ownermobileno;
            $owner->update();
        }


        return response()->json([
            "success" => true,
            "data" => $resident,
            "message" => "Resident  Details Updated Successfully"
        ]);
    }


    public function loginresidentdetails ($residentid)

    {
        $data = Resident::where('residentid', $residentid)->get();



    return response()->json(
        [
            "success" => true,
            "data" => $data
        ]
    );



    }
    public function  loginresidentupdateaddress(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'residentid' => 'required',
            'address' => 'required',
        ]);




        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        $user = User::Find($request->residentid);


        $user->address=$request->address;
        $user->update();


        return response()->json([
            "success" => true,



        ]);


    }



    public function residentlogin(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'cnic' => 'required',
            'password' => 'required',
        ]);



        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }
        // $user = Auth:: user();

        $user = User::where('cnic', $request->cnic)
            ->join('residents', 'residents.residentid', '=', 'users.id')->first();

        // dd($user);
        // if(Hash::check($request->password, $user->password)) {


        //     return response()->json(['status'=>'true','message'=>'Email is correct']);
        // } else {
        //     return response()->json(['status'=>'false', 'message'=>'password is wrong']);
        // }



        $tk =   $request->user()->createToken('token')->plainTextToken;


        return response()->json([
            "success" => true,
            "data" => $user,
            "Bearer" => $tk


        ]);


        // return response()->json([
        //     "success" => false,
        //     "data" => "Unauthorized"

        // ], 401);

    }


    public function unverifiedhouseresident ($subadminid,$status)

    {
        // ->join('users', 'users.id', '=', 'residents.residentid')
        $residents = Resident::where('subadminid',$subadminid)->where('status',$status) ->where('propertytype','house')
        ->join('houseresidentaddresses', 'residents.residentid', '=', 'houseresidentaddresses.residentid')  ->join('users', 'users.id', '=', 'houseresidentaddresses.residentid')
        ->with('society')
        ->with('phase')
        ->with('block')
        ->with('street')
        ->with('property')
        ->with('measurement')->with('owner')->get();


        // $residents = Resident::where('subadminid',$subadminid)->where('status',$status) 
        // ->join('apartmentresidentaddresses', 'residents.residentid', '=', 'apartmentresidentaddresses.residentid')  ->join('users', 'users.id', '=', 'apartmentresidentaddresses.residentid')
        // ->with('society')
        // ->with('phase')
        // ->with('building')
        // ->with('floor')
        // ->with('apartment')
        // ->with('measurement')->get();



        return response()->json([
            "success" => true,
            "data"=>$residents



        ]);


    }

    public function unverifiedapartmentresident ($subadminid,$status)

    {
        // ->join('users', 'users.id', '=', 'residents.residentid')
        // $residents = Resident::where('subadminid',$subadminid)->where('status',$status) 
        // ->join('houseresidentaddresses', 'residents.residentid', '=', 'houseresidentaddresses.residentid')  ->join('users', 'users.id', '=', 'houseresidentaddresses.residentid')
        // ->with('society')
        // ->with('phase')
        // ->with('block')
        // ->with('street')
        // ->with('property')
        // ->with('measurement')->get();


        $residents = Resident::where('subadminid',$subadminid)->where('status',$status) ->where('propertytype','apartment')
        ->join('apartmentresidentaddresses', 'residents.residentid', '=', 'apartmentresidentaddresses.residentid')  ->join('users', 'users.id', '=', 'apartmentresidentaddresses.residentid')
        ->with('society')
        ->with('phase')
        ->with('building')
        ->with('floor')
        ->with('apartment')
        ->with('measurement')->with('owner')->get();



        return response()->json([
            "success" => true,
            "data"=>$residents



        ]);


    }


    public function verifyhouseresident (Request $request)

    {
        $isValidate = Validator::make($request->all(), [


            'residentid' => 'required|exists:residents,residentid',
            'status'=>'required',
            'pid'=>'required',
            'bid'=>'required',
            'sid'=>'required',
            'propertyid'=>'required',
            'vechileno'=>'nullable',
            'measurementid'=>'required'

        ]); 


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }





        $residents = Houseresidentaddress::where('residentid',$request->residentid)->first();

        // dd( $residents->status);
       
        $residents->pid=$request->pid;
        $residents->bid=$request->bid;
        $residents->sid=$request->sid;
        $residents->propertyid=$request->propertyid;
        $residents->measurementid=$request->measurementid;
        $residents->update();

        $res =Resident::where('residentid',$residents->residentid)->first();
        $res->status=$request->status;
        $res->vechileno=$request->vechileno??'';
        $res->update();
        
      
        $user =User::where('id',  $residents->residentid)->first();
        $user->address=  $res->houseaddress;
        $user->update();



        return response()->json([
            "success" => true,
            "data"=>$residents

        ]);


    }


    public function verifyapartmentresident (Request $request)

    {
        $isValidate = Validator::make($request->all(), [

            'pid'=>'required',

            'residentid' => 'required|exists:residents,residentid',
            'status'=>'required',
            'buildingid'=>'required',
            'societybuildingfloorid'=>'required',
            'societybuildingapartmentid'=>'required',
            'vechileno'=>'nullable',
            'measurementid'=>'required'
        ]); 


        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }





        $residents = Apartmentresidentaddress::where('residentid',$request->residentid)->first();

        // dd( $residents->status);
       
        $residents->pid=$request->pid;
        $residents->buildingid=$request->buildingid;
        $residents->societybuildingfloorid=$request->societybuildingfloorid;
        $residents->societybuildingapartmentid=$request->societybuildingapartmentid;
        $residents->measurementid=$request->measurementid;
        $residents->update();

        $res =Resident::where('residentid',$residents->residentid)->first();
        $res->status=$request->status;
        $res->vechileno=$request->vechileno??'';
        $res->update();
        
      
        $user =User::where('id',  $residents->residentid)->first();
        $user->address=  $res->houseaddress;
        $user->update();



        return response()->json([
            "success" => true,
            "data"=>$residents

        ]);


    }

}
