<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\chatroom;
use App\Models\customer;
use App\Models\profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class chatController extends Controller
{
    public function loadChatroom(){
        $chatRoom=chatroom::where('client_id',Session::get('cust_id'))->orWhere('freelancer_id',Session::get('cust_id'))->get();
        $Room=chatroom::where('client_id',Session::get('cust_id'))->orWhere('freelancer_id',Session::get('cust_id'))->get('room_id');

        $chatRoom=json_decode(json_encode($chatRoom),true);
        $cust=customer::get();
        $cust=json_decode(json_encode($cust),true);

        $chat=chat::whereIn('room_id',$Room)->get();
        $chat=json_decode(json_encode($chat),true);
        return view('chatroom',[
            'chatroom'=>$chatRoom,
            'cust'=>$cust,
            'chat'=>$chat
        ]);
    }

    public function loadKirimPesan(){
        return view('kirimPesan');
    }

    public function sendMessage(Request $request){
        $clientId=0;
        $freelancerId=0;
        $jam=date('H:i');

        DB::beginTransaction();
        if(Session::get('role')=='client'){
            $clientId=Session::get('cust_id');
            $freelance= customer::where('email',$request->input('tujuan'))->first();
            $freelancerId=$freelance->cust_id;

        }else{
            $freelancerId=Session::get('cust_id');
            $client=customer::where('email',$request->input('tujuan'))->first();
            $clientId=$client->cust_id;
        }

        $chatRoom= new chatroom();
        $chatRoom->client_id=$clientId;
        $chatRoom->freelancer_id=$freelancerId;
        $chatRoom->save();

        if($chatRoom){
            $chat= new chat();
            $chat->room_id=$chatRoom->room_id;
            $chat->sender_id=Session::get('cust_id');
            $chat->message=$request->input('pesan');
            $chat->message_time=$jam;
            $chat->save();
            if($chat){
                DB::commit();
                return Redirect::back()->with('success','Pesan Berhasil Di Kirim!');
            }else{
                DB::rollback();
                return Redirect::back()->with('error','Pesan Gagal Di Kirim!');
            }
        }else{
            DB::rollback();
            return Redirect::back()->with('error','Chatroom Gagal Dibuat');
        }
    }

    public function loadChatbox($roomId){

        $recieverName="";

        $chat=chat::where('room_id',$roomId)->get();
        $chat=json_decode(json_encode($chat),true);

        $room= chatroom::where('room_id',$roomId)->first();
        $room=json_decode(json_encode($room),true);


        if($room['freelancer_id']==Session::get('cust_id')){
            $name= customer::where('cust_id',$room['client_id'])->first();
            $recieverName=$name->nama;
        }else{
            $name= customer::where('cust_id',$room['freelancer_id'])->first();
            $recieverName=$name->nama;
        }

        $freelancerPic=profil::where('cust_id',$room['freelancer_id'])->first();
        $clientPic=profil::where('cust_id',$room['client_id'])->first();

        return view('chatbox',[
            'chat'=>$chat,
            'roomId'=>$roomId,
            'recieverName'=>$recieverName,
            'freelancerPic'=>$freelancerPic,
            'clientPic'=>$clientPic
        ]);
    }

    public function sendChat(Request $request,$roomId){
        $jam=date('H:i');

        DB::beginTransaction();

        $insertChat= new chat();
        $insertChat->room_id=$roomId;
        $insertChat->sender_id=Session::get('cust_id');
        $insertChat->message=$request->input('message');
        $insertChat->message_time=$jam;
        $insertChat->save();

        if($insertChat){
            DB::commit();
            return Redirect::back();
        }else{
            DB::rollBack();
            return Redirect::back()->with('error','Pesan Gagal Dikirim!');
        }
    }
}
