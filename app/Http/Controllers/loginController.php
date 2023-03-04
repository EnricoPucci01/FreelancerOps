<?php

namespace App\Http\Controllers;

use App\Models\applicant;
use App\Models\customer;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\notificationModel;
use App\Models\proyek;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;

class loginController extends Controller
{
    public function login (Request $request){

        $formValidate=$request->validate([
                'email_login'=>'required|email',
                'pass_login'=>'required'
            ],
            [
                'email_login.required'=>'Email tidak dapat kosong!',
                'email_login.email'=>'Email yang anda masukan salah!',
                'pass_login.required'=>'Password tidak dapat kosong!'
            ]
        );

        $loginResult= customer::where("email",$request->input('email_login'))->withTrashed()->get();
        $dataLogin=json_decode(json_encode($loginResult),true);
        if($dataLogin[0]['deleted_at']==null){
            if(count($loginResult)<=0){
                return \redirect("/")->with('error','Email anda tidak terdaftar');
            }else{
                $dataLogin=json_decode(json_encode($loginResult),true);
                if(Hash::check($request->input('pass_login'),$dataLogin[0]['password'])){
                    FacadesSession::put('active',$dataLogin[0]['email']);
                    FacadesSession::put('name',$dataLogin[0]['nama']);
                    FacadesSession::put('role',$dataLogin[0]['role']);


                    if($dataLogin[0]['role']=="freelancer"){
                        FacadesSession::put('cust_id',$dataLogin[0]['cust_id']);
                        DB::commit();
                        return \redirect("/autoRejectApplicant");
                    }else if($dataLogin[0]['role']=="client"){
                        //Auth::login($dataLogin[0]['email']);
                        FacadesSession::put('cust_id',$dataLogin[0]['cust_id']);
                        DB::commit();

                        return \redirect('/dashboardClient');
                    }else if($dataLogin[0]['role']=="admin"){
                        FacadesSession::put('cust_id','0');
                        FacadesSession::put('adminCustId',$dataLogin[0]['cust_id']);
                        DB::commit();
                        return \redirect('/autoClosePayment');
                    }


                }else{
                    return \redirect("/")->with('error','Password anda tidak terdaftar');
                }
            }
        }else{
            return \redirect("/")->with('error','Akun Anda Tidak Aktif Harap Hubungi Admin Untuk Mengaktifkan Akun Kembali');
        }
    }

    public function loadDashboardFreelancer(){
        $modulDiambil= modulDiambil::where('cust_id',FacadesSession::get('cust_id'))->where('status',"!=",'dibatalkan')->get('modul_id');
        $modulDiambil=json_decode(json_encode($modulDiambil),true);
        $modulFreelancer= modul::whereIn('modul_id',$modulDiambil)->where('status','!=','finish')->get();
        $modulFreelancer=json_decode(json_encode($modulFreelancer),true);
        $uang= customer::where('cust_id',FacadesSession::get('cust_id'))->first();
        $uang= json_decode(json_encode($uang),true);

        //dd($modulFreelancer);

        $getNotif= notificationModel::where('customer_id', FacadesSession::get('cust_id'))->where("status","S")->count();
        FacadesSession::put("notif",$getNotif);
        return view('dashboard',[
            'modul'=>$modulFreelancer,
            'total'=>$uang['saldo'],
        ]);
    }

    public function loadDashboardClient(){
        $proyek=proyek::where('cust_id',FacadesSession::get('cust_id'))->get();
        $proyek=json_decode(json_encode($proyek),true);
        $getNotif= notificationModel::where('customer_id', FacadesSession::get('cust_id'))->where('status',"S")->count();
        //dd($modulFreelancer);
        FacadesSession::put("notif",$getNotif);
        return view('dashboardClient',[
            'proyek'=>$proyek,
        ]);
    }

    public function logout(){
        FacadesSession::flush();
        return redirect('/');
    }

    public function autoRejectApplicant(){
        DB::beginTransaction();
        $listApplicant=applicant::get();
        $listApplicant=json_decode(json_encode($listApplicant),true);
        $role=session()->get('role');
        $dateNow=Carbon::now();
        foreach ($listApplicant as $applicant ) {
            $applyDate=new DateTime($applicant['created_at']);
            $dateDiff=$applyDate->diff($dateNow);
            // echo $applyDate->format('d/m/Y')." || ";
            // return $dateDiff->days;
            if($dateDiff->days>=3){
                $rejectApplicant=applicant::find($applicant['applicant_id']);
                $rejectApplicant->status='ditolak';
                $rejectApplicant->save();
                $rejectApplicant->delete();
            }
        }
        DB::commit();
        return \redirect("/dashboardfreelancer");
    }
}
