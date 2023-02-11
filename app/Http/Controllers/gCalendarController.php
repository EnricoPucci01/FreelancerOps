<?php


namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\modulDiambil;
use App\Models\modul;
use App\Models\profil;
use DateTimeZone;
use Google\Service\Calendar as calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
// use Google\Service\Calendar\Event;
// use Google\Service\Calendar\EventDateTime;
// use Google_Client;
use Spatie\GoogleCalendar\Event;
class gCalendarController extends Controller
{

//==============================================================================================================
// Pakai Spatie
//==============================================================================================================
    public function insertEvent(Request $request){
        $getProf=profil::where('cust_id',Session::get('cust_id'))->first();
        $events= Event::get(Carbon::parse($request->input('start_Event')),Carbon::parse($request->input('end_Event')),[],$getProf->calendar_id);

        //dd($events);
        if($request->input('warningCal')=='noinsert'){
            $formValidate=$request->validate
            (
                [
                    'name_Event'=>'required',
                    'start_Event'=>'required',
                    'end_Event'=>'required',
                    'timeStart_Event'=>'required',
                    'timeEnd_Event'=>'required'],
                [
                'name_Event.required'=>'Nama tidak dapat kosong!',
                'start_Event.required'=>'Tanggal mulai tidak dapat kosong!',
                'end_Event.required'=>'Tanggal berakhir tidak dapat kosong!',
                'timeStart_Event.required'=>'Jam mulai tidak dapat kosong!',
                'timeEnd_Event.required'=>'Jam berakhir tidak dapat kosong!']
            );
            $startDate=$request->input('start_Event').' '.$request->input('timeStart_Event');
            $endDate=$request->input('end_Event').' '.$request->input('timeEnd_Event');
            Session::put('startDate',$startDate);
            Session::put('endDate',$endDate);
            Session::put('eventname',$request->input('name_Event'));
            if($events!=null){
                Session::put('errorCal','error');
                return Redirect::back();
             }else{
                 $inEvent=Event::create([
                     'name' =>  Session::get('eventname'),
                     'startDateTime' => Carbon::parse(Session::get('startDate')),
                     'endDateTime' => Carbon::parse(Session::get('endDate')),
                 ],$getProf->calendar_id);

                 //dd($inEvent);
                 if($inEvent!=null){
                     return Redirect::back()->with('success','Acara Berhasil Di Tambahkan, Silahkan Cek Google Calendar Anda!');
                 }else{
                     return Redirect::back()->with('error','Acara Gagal Di Tambahkan!');
                 }
             }
        }else{
            $inEvent=Event::create([
                'name' =>  Session::get('eventname'),
                'startDateTime' => Carbon::parse(Session::get('startDate')),
                'endDateTime' => Carbon::parse(Session::get('endDate')),
            ],$getProf->calendar_id);
            Session::forget('errorCal');
            Session::forget('startDate');
            Session::forget('endDate');
            Session::forget('eventname');
            //dd($inEvent);
            if($inEvent!=null){
                return Redirect::back()->with('success','Acara Berhasil Di Tambahkan, Silahkan Cek Google Calendar Anda!');
            }else{
                return Redirect::back()->with('error','Acara Gagal Di Tambahkan!');
            }
        }
    }

    public function changeENV(Request $request){
        // Ganti Environment Variabel Google_calendar_id

        DB::beginTransaction();

        $formValidate=$request->validate
        (
            [
                'cal_id'=>'required',],
            [
                'cal_id.required'=>'Calendar Id tidak dapat kosong!',]
        );

        $upProfile=profil::where('cust_id',Session::get('cust_id'))->first();

        $upProfile->calendar_id=$request->input('cal_id');
        $upProfile->save();

        if($upProfile){
            DB::commit();
            return Redirect::back()->with('success','Calendar Id berhasil di ubah!');
        }else{
            DB::rollback();
            return Redirect::back()->with('error','Calendar Id gagal di ubah!');
        }

        // $path = base_path('.env');

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         'GOOGLE_CALENDAR_ID='.env('GOOGLE_CALENDAR_ID',''), 'GOOGLE_CALENDAR_ID='.$request->input('cal_id'), file_get_contents($path)
        //     ));
        //
        // }
    }

    public function loadEditCalendar(){
        // Ganti Environment Variabel Google_calendar_id

        DB::beginTransaction();

        $upProfile=profil::where('cust_id',Session::get('cust_id'))->first();

        if(is_null($upProfile)){
            $newProf=new profil();
            $newProf->cust_id = Session::get("cust_id");
            $newProf->save();

            if($newProf){
                DB::commit();
                $calIdExist=false;
            }
        }else{
            $calIdExist=($upProfile->calendar_id == null)?false:true;
        }

        return view("editCalendar", [
            "calendarId"=>$calIdExist
        ]);
        // $path = base_path('.env');

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         'GOOGLE_CALENDAR_ID='.env('GOOGLE_CALENDAR_ID',''), 'GOOGLE_CALENDAR_ID='.$request->input('cal_id'), file_get_contents($path)
        //     ));
        //
        // }
    }



//==============================================================================================================
// Pakai API Google Calendar
//==============================================================================================================

    // public function index(Request $request,$custId){

    //     if($request->ajax()) {
    //         $data = modulDiambil::where('cust_id',$custId)->get('modul_id');
    //         $data = json_decode(json_encode($data),true);
    //         //var_dump($data);
    //         $event= modul::whereIn('modul_id',$data)->get(['title','start','end']);
    //         //dd($event);
    //         return response()->json($event);
    //     }

    //    return view('calendar');
    // }

    // public function testGetEventCalendar(){
    //     session_start();
    //     $client= new Google_Client();
    //     $client->setAuthConfig('client_secret.json');
    //     $client->addScope(calendar::CALENDAR);
    //     $guzzleClient= new \GuzzleHttp\Client(
    //         array(
    //             "curl"=>array(CURLOPT_SSL_VERIFYPEER=>false)
    //         )
    //     );
    //     $client->setHttpClient($guzzleClient);

    //     if(isset($_SESSION['access_token'])&& $_SESSION['access_token']){
    //         $client->setAccessToken($_SESSION['access_token']);
    //         $service= new calendar($client);
    //         $calendarId='enricoayen3@gmail.com';
    //         $optParam=array(
    //             'maxResults'=>10,
    //             'orderBy'=>'startTime',
    //             'singleEvents'=>true,
    //             'timeMin'=>date('c'),
    //         );
    //         $result= $service->events->listEvents($calendarId,$optParam);
    //         return $result->getItems();
    //     }else{
    //         return redirect('/oauth');
    //     }
    // }

    // public function oauth(){
    //     session_start();
    //     $client_id = '661837197172-vnjb3bqqdqmhmact77av3q536akvua5g.apps.googleusercontent.com';
    //     $service_account_name = 'calendarservice@freelancerops.iam.gserviceaccount.com';
    //     $key_file_location = 'freelancerops-f3ac0c24a310.p12';

    //     $client = new Google_Client();
    //     $client->setApplicationName("Freelancer OPS Calendar");
    //     $client->setScopes('https://www.googleapis.com/auth/calendar');
    //     $rurl=action([gCalendarController::class,'oauth']);
    //     $client->setRedirectUri($rurl);
    //     $client->setAuthConfig(array(
    //         'type' => 'service_account',
    //         'client_email' => $service_account_name,
    //         'client_id' => $client_id,
    //         'private_key' => $key_file_location
    //     ));
    //     $client->addScope(calendar::CALENDAR);

    //     $guzzleClient= new \GuzzleHttp\Client(
    //             array(
    //                 "curl"=>array(CURLOPT_SSL_VERIFYPEER=>false)
    //             )
    //         );
    //         $client->setHttpClient($guzzleClient);

    //         if(!isset($_GET['code'])){
    //             $auth=$client->createAuthUrl();
    //             $filter_url=filter_var($auth,FILTER_SANITIZE_URL);

    //             return redirect($filter_url);
    //         }else{
    //             $client->authenticate($_GET['code']);
    //             $_SESSION['access_token']=$client->getAccessToken();
    //             return redirect('/event');
    //         }


    //     // $rurl=action([gCalendarController::class,'oauth']);
    //     // $client= new Google_Client();
    //     // $client->setAuthConfig('freelancerops-f3ac0c24a310.p12');
    //     // $client->setRedirectUri($rurl);
    //     // $client->addScope(calendar::CALENDAR);

    //     // $guzzleClient= new \GuzzleHttp\Client(
    //     //     array(
    //     //         "curl"=>array(CURLOPT_SSL_VERIFYPEER=>false)
    //     //     )
    //     // );
    //     // $client->setHttpClient($guzzleClient);

    //     // if(!isset($_GET['code'])){
    //     //     $auth=$client->createAuthUrl();
    //     //     $filter_url=filter_var($auth,FILTER_SANITIZE_URL);

    //     //     return redirect($filter_url);
    //     // }else{
    //     //     $client->authenticate($_GET['code']);
    //     //     $_SESSION['access_token']=$client->getAccessToken();
    //     //     return redirect('/event');
    //     // }
    // }

    // public function insertEvent(){
        // $client_id = '661837197172-vnjb3bqqdqmhmact77av3q536akvua5g.apps.googleusercontent.com';
        // $service_account_name = 'calendarservice@freelancerops.iam.gserviceaccount.com';
        // $key_file_location = 'freelancerops-f3ac0c24a310.p12';

        // $client = new Google_Client();
        // $client->setApplicationName("Freelancer OPS Calendar");
        // $client->setScopes('https://www.googleapis.com/auth/calendar');
        // $client->setAuthConfig(array(
        //     'type' => 'service_account',
        //     'client_email' => $service_account_name,
        //     'client_id' => $client_id,
        //     'private_key' => $key_file_location
        // ));
        // $client->addScope(calendar::CALENDAR);

        // if (isset($_SESSION['service_token'])) {
        //     $client->setAccessToken($_SESSION['service_token']);
        //     $service= new calendar($client);
        //     $calendarId='enricoayen3@gmail.com';
        //     $timeZone=new DateTimeZone('Asia/Jakarta');

        //     // Carbon Format
        //     // $year = 0,
        //     // $month = 1,
        //     // $day = 1,
        //     // $hour = 0,
        //     // $minute = 0,
        //     // $second = 0,
        //     // $tz = null

        //     $startTime= Carbon::create(
        //         2022,
        //         5,
        //         20,
        //         11,
        //         8,
        //         0,
        //         $timeZone
        //     );

        //     $endTime= Carbon::create(
        //         2022,
        //         5,
        //         21,
        //         10,
        //         8,
        //         0,
        //         $timeZone
        //     );
        //     $googleStartTime=new EventDateTime();
        //     $googleStartTime->setTimeZone($timeZone);
        //     $googleStartTime->setDateTime($startTime->format('c'));

        //     $googleEndTime=new EventDateTime();
        //     $googleEndTime->setTimeZone($timeZone);
        //     $googleEndTime->setDateTime($endTime->format('c'));

        //     $event= new Event();
        //     $event->setStart($googleStartTime);
        //     $event->setEnd($googleEndTime);
        //     $event->setSummary('Test Insert Event 2');
        //     $createEvent=$service->events->insert($calendarId,$event);
        // }else{
        //     return redirect('/oauth');
        // }


        //$client=new Google_Client();
        // $client->setAuthConfig('freelancerops-f3ac0c24a310.p12');
        // $client->addScope(calendar::CALENDAR);
        // $guzzleClient= new \GuzzleHttp\Client(
        //     array(
        //         "curl"=>array(CURLOPT_SSL_VERIFYPEER=>false)
        //     )
        // );
        // $client->setHttpClient($guzzleClient);

        // if(isset($_SESSION['access_token'])&& $_SESSION['access_token']){
        //     $client->setAccessToken($_SESSION['access_token']);
        //     $service= new calendar($client);
        //     $calendarId='enricoayen3@gmail.com';
        //     $timeZone=new DateTimeZone('Asia/Jakarta');

        //     // Carbon Format
        //     // $year = 0,
        //     // $month = 1,
        //     // $day = 1,
        //     // $hour = 0,
        //     // $minute = 0,
        //     // $second = 0,
        //     // $tz = null

        //     $startTime= Carbon::create(
        //         2022,
        //         5,
        //         20,
        //         11,
        //         8,
        //         0,
        //         $timeZone
        //     );

        //     $endTime= Carbon::create(
        //         2022,
        //         5,
        //         21,
        //         10,
        //         8,
        //         0,
        //         $timeZone
        //     );
        //     $googleStartTime=new EventDateTime();
        //     $googleStartTime->setTimeZone($timeZone);
        //     $googleStartTime->setDateTime($startTime->format('c'));

        //     $googleEndTime=new EventDateTime();
        //     $googleEndTime->setTimeZone($timeZone);
        //     $googleEndTime->setDateTime($endTime->format('c'));

        //     $event= new Event();
        //     $event->setStart($googleStartTime);
        //     $event->setEnd($googleEndTime);
        //     $event->setSummary('Test Insert Event 2');
        //     $createEvent=$service->events->insert($calendarId,$event);
        // }else{
        //     return redirect('/oauth');
        // }
    // }

}
