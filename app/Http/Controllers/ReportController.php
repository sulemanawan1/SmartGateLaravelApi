<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

// 0=> pending
        // 1=> in progress
        // 3=> rejected
        // 4=> completed
class ReportController extends Controller
{
    public function reporttoadmin(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'userid' => 'required|exists:users,id',
            'subadminid' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'status' => 'required',
            'statusdescription' => 'required',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $report = new Report();
        $report->userid = $request->userid;
        $report->subadminid = $request->subadminid;
        $report->title = $request->title;
        $report->description = $request->description;
        $report->date = Carbon::parse($request->date)->format('y-m-d');
        $report->status = $request->status;
        $report->statusdescription = $request->statusdescription;
        $report->save();
        // $tk = 'ct99wz8wTeemXnjYoM0KCe:APA91bEj3t_hU0LKRPCS6lVsvMHJDj_Yg4ES_OneTJBJSgxNi10Wlxpff5ZSX9eVYgZzAyoYP6k6EkGJNI0t2LHJyf39eCGkFAhMiHhU3gSGsAPc75Yz7cpjZ6MnY_KT_V7a_DhlOmdb';
        // $tk1 = 'd_JmZJF6Qfqd4g9RdxUEqB:APA91bE3FO0n7xWo9E1S4cEH1jgq63aSPZdxSVd6EDruH96WdMR_lvfsRGmB8tja1KRloDyLiZMujlbtzxsuxsTFjrCu-6IUyf8hF7puN0pATH4mX7eVmGeUHJ54oKNg_CrN3N9n5g-w';
        // $tk2='cSxb9-tvSMil-hn_mQXVhJ:APA91bF7Qm38y5CNqRZLRx6pSAJPkagCgZCDwgGu7O7D2BIPnjFm0iO6gdp2e0Wms-GnvaELtwnz0G5G_A-BnsVWzTb5LwLpXfx4-2wZ_Q1QXxmUfms4y_Z3Qhf8fIHCq8QO4lykqbVB';
        // $serverkey = 'AAAAcuxXPmA:APA91bEz-6ptcGS8KzmgmSLjb-6K_bva-so3i6Eyji_ihfncqXttVXjdBQoU6V8sKilzLb9MvSHFId-KK7idDwbGo8aXHpa_zjGpZuDpM67ICKM7QMCGUO_JFULTuZ_ApIOxdF3TXeDR';
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $mydata = [
        //     'registration_ids' => [$tk, $tk1,$tk2],
        //     "data" => $report,
        //     "notification" => [
        //         'title' => $report->title, 'body' => $report->description,
        //         'description' => ""
        //     ]
        // ];
        // $finaldata = json_encode($mydata);
        // $headers = array(
        //     'Authorization: key=' . $serverkey,
        //     'Content-Type: application/json'
        // );
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $finaldata);
        // $result = curl_exec($ch);
        // // var_dump($result);
        // curl_close($ch);
        return response()->json([
            'message' => 'success',
            'data' => $report
        ], 200);
    }
    public function deletereport($id)
    {
        $report =   Report::find($id);
        if ($report != null) {
            $report = Report::where('id', $id)->delete();
            return response()->json([
                'success' => true,
                "data" => $report, "message" => " Report Delete Successfully"
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Report not deleted"
            ], 403);
        }
    }
    public function adminreports($residentid)
    {
        $report = Report::where('userid', $residentid)->where('status','!=',3)->where('status' ,'!=' , 4)->get();
        return response()->json([
            "success" => true,
            "data" => $report,
        ]);
    }







    public function updatereportstatus(Request $request)
    {
        $isValidate = Validator::make($request->all(), [
            'id' => 'required|exists:reports,id',
            'userid' => 'required|exists:users,id',
            //'date' => 'required|date',
            'status' => 'required',
            'statusdescription' => 'required',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $report = Report::Find($request->id);
        //$report->date = Carbon::parse($request->date)->format('y-m-d');
        $report->status = $request->status;
        $report->updated_at = Carbon::now()->addHour(5)->toDateTimeString();
        $report->statusdescription = $request->statusdescription;
        $report->update();
        return response()->json([
            "success" => true,
            "data" => $report,
            "message" => "Status Updated Successfully"
        ]);
    }
    public function reportedresidents($subadminid)
    {
        $report =  User::where('subadminid', $subadminid)->
        join('reports', 'reports.userid', '=', 'users.id')->where('status',2)->distinct()->get();
        $res = $report->unique('userid');
        //  $data = subadminsociety::where('societyid', $id)->join('users', 'users.id', '=', 'subadminsocieties.subadminid')->get();
        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }
    public function reports($subadminid, $userid)
    {
        $reports =  Report::where('subadminid', $subadminid)->where('userid', $userid)->where('status',2)->GET();
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }
    public function pendingreports($subadminid)
    {
        $reports =  Report::where('subadminid', $subadminid)->where('status',0)
            ->join('users', 'reports.userid', '=', 'users.id')->select(
                'reports.id',
                "users.firstname",
                "users.lastname",
                "users.cnic",
                "users.address",
                "users.mobileno",
                "users.roleid",
                "users.rolename",
                "users.image",
                "reports.userid",
                "reports.subadminid",
                "reports.title",
                "reports.description",
                "reports.date",
                "reports.status",
                "reports.statusdescription",
                 "reports.created_at",
                 "reports.updated_at",
            )->GET();
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }
    public function historyreportedresidents($subadminid)
    {
        $report =  User::where('subadminid', $subadminid)->join('reports', 'reports.userid', '=', 'users.id')->where('status',3)->orwhere('status',4)->distinct()->get();
        $res = $report->unique('userid');
        //  $data = subadminsociety::where('societyid', $id)->join('users', 'users.id', '=', 'subadminsocieties.subadminid')->get();
        return response()->json([
            "success" => true,
            "data" => $res->values()->all(),
        ]);
    }
    public function historyreports($subadminid, $userid)
    {   
        $reports =  Report::where('subadminid', $subadminid)->where('userid', $userid)->whereIn('status', [3, 4])->GET();
        return response()->json([
            "success" => true,
            "data" => $reports
        ]);
    }
}
