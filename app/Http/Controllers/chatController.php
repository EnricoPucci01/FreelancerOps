<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\chatroom;
use App\Models\customer;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\profil;
use App\Models\proyek;
use Carbon\Carbon;
use Google\Service\BigtableAdmin\Split;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use LaravelFCM\Facades\FCM as FacadesFCM;

class chatController extends Controller
{
    public function loadChatroom()
    {
        $chatRoom = chatroom::where('sender_id', Session::get('cust_id'))->orWhere('reciever_id', Session::get('cust_id'))->get();
        $chatRoom = json_decode(json_encode($chatRoom), true);

        $Room = chatroom::where('sender_id', Session::get('cust_id'))->orWhere('reciever_id', Session::get('cust_id'))->get('room_id');

        $cust = customer::get();
        $cust = json_decode(json_encode($cust), true);

        $lastChat = array();
        foreach ($Room as $key) {
            $chat = chat::where('room_id', $key->room_id)->orderBy('chat_id', 'DESC')->first();
            $chat = json_decode(json_encode($chat), true);
            array_push($lastChat, $chat);
        }

        //dd($lastChat);

        $dataProyek = null;

        if (Session::get("role") == "client") {
            //get topik proyek
            $proyek_id = proyek::where("cust_id", Session::get('cust_id'))->get("proyek_id");
            $modul_id = modulDiambil::whereIn("proyek_id", $proyek_id)->get("modul_id");
            $cust_id = modulDiambil::whereIn("proyek_id", $proyek_id)->get("cust_id");

            $modulData = modul::whereIn("modul_id", $modul_id)->get();
            $modulData = json_decode(json_encode($modulData), true);
            $customerData = customer::whereIn("cust_id", $cust_id)->get();
            $customerData = json_decode(json_encode($customerData), true);
            $dataModulDiambil = modulDiambil::whereIn("proyek_id", $proyek_id)->get();
            $dataModulDiambil = json_decode(json_encode($dataModulDiambil), true);
        } else {
            $proyek_id = modulDiambil::where("cust_id", Session::get('cust_id'))->get(["proyek_id"]);
            $proyek_id = json_decode(json_encode($proyek_id), true);
            $cust_id = proyek::whereIn("proyek_id", $proyek_id)->get("cust_id");
            $modul_id = modulDiambil::where("cust_id", Session::get('cust_id'))->get("modul_id");
            $modul_id = json_decode(json_encode($modul_id), true);

            $customerData = customer::whereIn("cust_id", $cust_id)->get();
            $customerData = json_decode(json_encode($customerData), true);

            $modulData = modul::whereIn("modul_id", $modul_id)->get();
            $modulData = json_decode(json_encode($modulData), true);

            $dataModulDiambil = modulDiambil::where("cust_id", Session::get('cust_id'))->get();
            $dataModulDiambil = json_decode(json_encode($dataModulDiambil), true);

            $dataProyek = proyek::whereIn('cust_id', $cust_id)->get();
            $dataProyek = json_decode(json_encode($dataProyek), true);
        }
        //dd($customerData);
        //dd($dataModulDiambil);
        return view('chatroom', [
            'chatroom' => $chatRoom,
            'cust' => $cust,
            'chat' => $lastChat,
            'modulData' => $modulData,
            'customerData' => $customerData,
            'dataModulDiambil' => $dataModulDiambil,
            'dataProyek' => $dataProyek
        ]);
    }

    public function getTestChat()
    {

        $chatList = chat::get();
        //dd($chatList);
        return view('testchatview', [
            'chatList' => $chatList
        ]);
    }

    public function testChat(Request $req)
    {
        $input = $req->all();
        $fcm_token = $input['fcm_token'];
        $customer = customer::where('cust_id', Session::get('cust_id'))->first();

        $customer->fcm_token = $fcm_token;
        $customer->save();
        return response()->json([
            'success' => true,
            'message' => 'User Updated'
        ]);
    }

    public function createChat(Request $request)
    {
        $message = $request->input('message');

        $chat = new chat();
        $chat->room_id = 999;
        $chat->sender_id = Session::get('cust_id');
        $chat->message = $message;
        $chat->message_time = Carbon::now();
        $chat->status_read = "S";

        $this->broadcastMessage(Session::get('cust_id'),$message);
        $chat->save();

        return redirect()->back();
    }

    private function broadcastMessage($senderId, $message)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New Message From: ' . $senderId);
        $notificationBuilder->setBody($message)
            ->setSound('default')
            ->setClickAction('https://127.0.0.1:8000/testChatPage');
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_id' => $senderId,
            'message' => $message
        ]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $tokens = customer::all()->pluck('fcm_token')->toArray();
        $downstreamResponse = FacadesFCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();

    }

    public function loadKirimPesan()
    {
        return view('kirimPesan');
    }

    public function sendMessage(Request $request)
    {
        $clientId = 0;
        $freelancerId = 0;
        $jam = date('H:i');
        $target = explode("_", $request->input('reciever'));

        $reciever = $target[0];
        $topik = $target[1];

        DB::beginTransaction();

        $chatRoom = new chatroom();
        $chatRoom->sender_id = Session::get("cust_id");
        $chatRoom->reciever_id = $reciever;
        $chatRoom->topik_proyek = str_replace('%20', ' ', $topik);
        $chatRoom->unread_msg = '1';
        $chatRoom->save();

        if ($chatRoom) {
            $chat = new chat();
            $chat->room_id = $chatRoom->room_id;
            $chat->sender_id = Session::get('cust_id');
            $chat->message = $request->input('pesan');
            $chat->message_time = $jam;
            $chat->status_read = "S";
            $chat->save();
            if ($chat) {
                DB::commit();
                return Redirect::back()->with('success', 'Pesan Berhasil Di Kirim!');
            } else {
                DB::rollback();
                return Redirect::back()->with('error', 'Pesan Gagal Di Kirim!');
            }
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Chatroom Gagal Dibuat');
        }
    }

    public function loadChatbox($roomId)
    {

        $recieverName = "";

        $chat = chat::where('room_id', $roomId)->get();
        $chat = json_decode(json_encode($chat), true);

        $room = chatroom::where('room_id', $roomId)->first();
        $room = json_decode(json_encode($room), true);

        //update Status Read
        $upchat = chat::where('room_id', $roomId)->where('sender_id', "!=", Session::get('cust_id'))->get();
        foreach ($upchat as $updateItem) {
            $updateItem->status_read = "R";
            $updateItem->save();
        }

        //update jumlah pesan sudah di read
        $upRoom = chatroom::where('room_id', $roomId)->first();
        if ($upRoom->sender_id == Session::get('cust_id')) {
            $upRoom->unread_sender = "0";
            $upRoom->save();
        } else if ($upRoom->reciever_id == Session::get('cust_id')) {
            $upRoom->unread_reciever = "0";
            $upRoom->save();
        }

        if ($room['reciever_id'] == Session::get('cust_id')) {
            $name = customer::where('cust_id', $room['sender_id'])->first();
            $recieverName = $name->nama;
        } else {
            $name = customer::where('cust_id', $room['reciever_id'])->first();
            $recieverName = $name->nama;
        }

        $recieverPic = profil::where('cust_id', $room['reciever_id'])->first();
        $senderPic = profil::where('cust_id', $room['sender_id'])->first();

        return view('chatbox', [
            'chat' => $chat,
            'roomId' => $roomId,
            'recieverName' => $recieverName,
            'recieverPic' => $recieverPic,
            'senderPic' => $senderPic,
            'topik' => $room['topik_proyek']
        ]);
    }

    public function sendChat(Request $request, $roomId)
    {
        $jam = date('H:i');

        DB::beginTransaction();

        $insertChat = new chat();
        $insertChat->room_id = $roomId;
        $insertChat->sender_id = Session::get('cust_id');
        $insertChat->message = $request->input('message');
        $insertChat->message_time = $jam;
        $insertChat->status_read = "S";
        $insertChat->save();

        if ($insertChat) {
            $chatroom = chatroom::where("room_id", $roomId)->first();
            $chat_count = chat::where("room_id", $roomId)->where("sender_id", Session::get("cust_id"))->count();

            if ($chatroom->sender_id == Session::get('cust_id')) {
                $chatroom->unread_reciever = $chat_count;
            } else if ($chatroom->reciever_id == Session::get('cust_id')) {
                $chatroom->unread_sender = $chat_count;
            }

            $chatroom->save();
            if ($chatroom) {
                DB::commit();
                return Redirect::back();
            } else {
                DB::rollBack();
                return Redirect::back()->with('error', 'Pesan Gagal Dikirim!');
            }
        } else {
            DB::rollBack();
            return Redirect::back()->with('error', 'Pesan Gagal Dikirim!');
        }
    }
}
