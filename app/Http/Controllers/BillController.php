<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Bill;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Houseresidentaddress;


class BillController extends Controller
{



    
    public function generatebill(Request $request)
    {

      

     $isValidate = Validator::make($request->all(), [

        'subadminid' => 'required|exists:users,id',
        "residentlist"=>'required',
        'duedate' => 'required',
        'billstartdate' => 'required',
        'billenddate' => 'required',
        'status'=>'required'
        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }

      $residentlist = json_decode($request->residentlist);

   
      
      $charges=0.0;
      $chargesafterduedate=0.0;
      $appcharges=0.0;
      $tax=0.0;
      $balance=0.0;	
      $subadminid=0;
      $residnentid=0;
      $propertyid=0;
      $measurementid=0;
      $duedate=null;
      $billstartdate=null;
        $billenddate=null;
     $getmonth=null;
     $month=null;
     $status=0;

  

    

        foreach ($residentlist->residentlist as $li)

{
   
   
    $residnents = Houseresidentaddress::where('houseresidentaddresses.residentid', $li)->join('residents', 'houseresidentaddresses.residentid', '=', 'residents.residentid')->with('property')->with('measurement')->first();
    $measurement =$residnents['measurement'];
    $property =$residnents['property'];
    $subadminid=$request->subadminid;
    $residnentid=$residnents->residentid;
    $status= $request->status;
    $duedate=$request->duedate;
    $billstartdate=$request->billstartdate;
    $billenddate=$request->billenddate;
    $getmonth = Carbon::parse( $billstartdate)->format('F Y');
    $month=$getmonth;
  

  
    foreach ($measurement as $measurement)

    {
        

$measurementid=$measurement->id;
$charges=$measurement->charges;
$chargesafterduedate=$measurement->chargesafterduedate;
$appcharge=$measurement->appcharges;
$tax=$measurement->tax;
$balance=$charges;



    }

    foreach ($property as $property)

    { 
        $propertyid= $property->id;


    }



    
   


    $bill = new Bill();  

    $status =  $bill->insert(
    [
    
     [
          'charges'=>$charges,
          'chargesafterduedate'=>$chargesafterduedate,
          'appcharges'=>$appcharge,
          'tax'=>$tax,
          'balance'=>$balance,
         'subadminid' => $subadminid,
         'residentid'=>$residnentid,
         'propertyid'=>$propertyid,
         'measurementid'=>$measurementid,
         'duedate'=>$duedate,
         'billstartdate'=>$billstartdate,
         'billenddate'=>$billenddate,
         'month'=>$month,
         'status'=>$status,
         'created_at' => date('Y-m-d H:i:s'),
         'updated_at' => date('Y-m-d H:i:s')
     ],
    
    ]
    );
    



}

return response()->json([
    "success" => true,
    
    "message"=> "Monthly Bill Generated for Residents Successfully !"
]);



       
   
    }


    public function generatedbill($subadminid)
    {

        $bills =  Bill::where('subadminid', $subadminid) ->join('users', 'users.id', '=', 'bills.residentid')
        ->select(
            
            'users.rolename',
            'bills.*',
            'users.firstname', 
            'users.lastname',
             'users.image',
            'users.cnic',
            'users.roleid',
          
            
            
            )->get();





        return response()->json([
            "success" => true,
            "data" => $bills,
        ]);
    }

 

}