<?php

namespace App\Http\Controllers;
use App\Models\Chat;
use App\Models\Gatekeeper;
use App\Models\Resident;
use Illuminate\Support\Facades\Validator;
use App\Event\UserChat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function conversations(Request $request)
    {

        $isValidate = Validator::make($request->all(), [

            'sender' => 'required|exists:users,id',
            'reciever' => 'required|exists:users,id',
            'chatroomid' => 'required|exists:chatrooms,id',
            'message' => 'nullable',
            'lastmessage' => 'nullable',

        ]);

        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        $chat = new Chat();
        $chat->sender=$request->sender;
        $chat->reciever=$request->reciever;
        $chat->chatroomid=$request->chatroomid;
        $chat->message=$request->message??'';
        $chat->lastmessage=$request->lastmessage??'';
        $chat->save();


        event(new UserChat($chat));



        return response()->json([
            "success"=>true,
            "data" => $chat]);




    }

    public function viewconversationsneighbours($chatroomid)
    {



        $cov=Chat::where('chatroomid',$chatroomid)->get();

        // event(new UserChat($cov));



        return response()->json([
            "success"=>true,
            "data" => $cov]);




    }


    public function chatneighbours($subadminid)
    {

       $chatneighbours=   Resident::where('subadminid', $subadminid)->where('status',1)->join('users', 'residents.residentid', '=', 'users.id')->get();

        return
        response()->json(["success"=>true,
        "data" => $chatneighbours]);

    }


    public function chatgatekeepers($subadminid)
    {

        $chatgatekeepers= Gatekeeper::where('subadminid', $subadminid)->join('users', 'gatekeepers.gatekeeperid', '=', 'users.id')->get();


        return
        response()->json(["success"=>true,
        "data" => $chatgatekeepers]);
    }




}
