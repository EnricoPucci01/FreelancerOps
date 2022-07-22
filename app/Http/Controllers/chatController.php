<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\chatroom;
use App\Models\customer;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\profil;
use App\Models\proyek;
use Google\Service\BigtableAdmin\Split;
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

        $chat=chat::whereIn('room_id',$Room)->orderBy('message_time','DESC')->first();
        $chat=json_decode(json_encode($chat),true);

        if(Session::get("role")=="client"){
            //get topik proyek
            $proyek_id= proyek::where("cust_id",Session::get('cust_id'))->get("proyek_id");
            $modul_id= modulDiambil::whereIn("proyek_id",$proyek_id)->get("modul_id");
            $cust_id=modulDiambil::whereIn("proyek_id",$proyek_id)->get("cust_id");

            $modulData= modul::whereIn("modul_id",$modul_id)->get();
            $modulData=json_decode(json_encode($modulData),true);
            $customerData= customer::whereIn("cust_id",$cust_id)->get();
            $customerData= json_decode(json_encode($customerData),true);
            $dataModulDiambil= modulDiambil::whereIn("proyek_id",$proyek_id)->get();
            $dataModulDiambil =json_decode(json_encode($dataModulDiambil),true);
        }else{
            $proyek_id=modulDiambil::where("cust_id",Session::get('cust_id'))->get(["proyek_id"]);
            $proyek_id=json_decode(json_encode($proyek_id),true);
            $cust_id= proyek::whereIn("proyek_id",$proyek_id)->get("cust_id");
            $modul_id= modulDiambil::where("cust_id",Session::get('cust_id'))->get("modul_id");
            $modul_id=json_decode(json_encode($modul_id),true);

            $customerData= customer::whereIn("cust_id",$cust_id)->get();
            $customerData= json_decode(json_encode($customerData),true);

            $modulData= modul::whereIn("modul_id",$modul_id)->get();
            $modulData=json_decode(json_encode($modulData),true);

            $dataModulDiambil= modulDiambil::where("cust_id",Session::get('cust_id'))->get();
            $dataModulDiambil =json_decode(json_encode($dataModulDiambil),true);

            $dataProyek=proyek::whereIn('cust_id',$cust_id)->get();
            $dataProyek =json_decode(json_encode($dataProyek),true);
        }
        //dd($customerData);
        //dd($dataModulDiambil);
        return view('chatroom',[
            'chatroom'=>$chatRoom,
            'cust'=>$cust,
            'chat'=>$chat,
            'modulData'=>$modulData,
            'customerData'=>$customerData,
            'dataModulDiambil'=>$dataModulDiambil,
            'dataProyek'=>$dataProyek
        ]);
    }

    public function loadKirimPesan(){
        return view('kirimPesan');
    }

    public function sendMessage(Request $request){
        $clientId=0;
        $freelancerId=0;
        $jam=date('H:i');
        $target= explode("_", $request->input('reciever'));

        $reciever = $target[0];
        $topik= $target[1];

        DB::beginTransaction();
        if(Session::get('role')=='client'){
            $clientId=Session::get('cust_id');
            $freelancerId=$reciever;

        }else{
            $freelancerId=Session::get('cust_id');
            $clientId=$reciever;
        }

        $chatRoom= new chatroom();
        $chatRoom->client_id=$clientId;
        $chatRoom->freelancer_id=$freelancerId;
        $chatRoom->topik_proyek=str_replace('%20',' ',$topik);
        $chatRoom->unread_msg='1';
        $chatRoom->save();

        if($chatRoom){
            $chat= new chat();
            $chat->room_id=$chatRoom->room_id;
            $chat->sender_id=Session::get('cust_id');
            $chat->message=$request->input('pesan');
            $chat->message_time=$jam;
            $chat->status_read="S";
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
            'clientPic'=>$clientPic,
            'topik'=>$room['topik_proyek']
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
