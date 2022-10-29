<?php
namespace App\Http\Controllers;
//use App\Http\Controllers\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\customer;
use App\Models\skill;
use App\Models\spesialisasi;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class registerController extends Controller
{
    public function loadRegister(){
        $skillList=skill::get();
        $skillList=json_decode(json_encode($skillList),true);
        return view('register',[
            'skillList'=>$skillList
        ]);
    }

    public function generateCode(Request $request){
        $formValidate=$request->validate([
            'email_register'=>'required|email|unique:customer,email',
            "name_register"=>'required',
            "pass_register"=>'required',
            "phone_register"=>'required|numeric|digits_between:10,13',
            "birthplace_register"=>'required',
            "birthdate_register"=>'required'
        ],[
            'email_register.required'=>'Email tidak dapat kosong!',
            'email_register.email'=>'Email yang anda masukan salah!',
            'email_register.unique'=>'Email yang anda masukan sudah terdaftar!',
            'pass_register.required'=>'Password tidak dapat kosong!',
            'phone_register.required'=>'Nomor HP tidak dapat kosong!',
            'phone_register.numeric'=>'Nomor HP hanya dapat berisi angka!',
            'phone_register.digits_between'=>'Nomor HP anda melebihi 13 digit atau kurang dari 10 digit!',
            "name_register.required"=>'Nama anda tidak dapat kosong!',
            "birthplace_register.required"=>'Tempat lahir tidak dapat kosong!',
            "birthdate_register.required"=>'Tanggal lahir tidak dapat kosong!'
        ]);

        $uniqueCode=uniqid();
        Session::put('role_register',$request->input("role_register"));

        Session::put('name_register',$request->input("name_register"));
        Session::put('email_register',$request->input("email_register"));
        Session::put('pass_register',Hash::make($request->input("pass_register")));
        Session::put('phone_register',$request->input("phone_register"));
        Session::put('birthplace_register',$request->input("birthplace_register"));
        Session::put('birthdate_register',$request->input("birthdate_register"));
        Session::put('uniqueCode',$uniqueCode);

        if($request->input('role_register')!='Admin'){
            Session::put('skill_register',$request->input('skill_register'));
            Session::put('pendidikan',$request->input('pendidikan_register'));
        }
        $email=$request->input("email_register");
        //return redirect("/registerUser");
        return redirect("sendEmail/$email/verify");
    }

    public function registerUser(Request $request){

        DB::beginTransaction();
        $cekEmail=customer::where("email","=",$request->input('email_register'))->count();

        if($cekEmail<=0){
            $insertNewCust= new customer;
            $insertNewCust->nama=Session::get('name_register');
            $insertNewCust->email=Session::get('email_register');
            $insertNewCust->password=Session::get('pass_register');
            $insertNewCust->nomorhp=Session::get('phone_register');
            $insertNewCust->tempat_lahir=Session::get('birthplace_register');
            $insertNewCust->tanggal_lahir=Session::get('birthdate_register');
            $insertNewCust->role=Session::get('role_register');
            $insertNewCust->saldo='0';

            if(Session::get('role_register')!='admin'){
                $insertNewCust->pendidikan=Session::get('pendidikan');
            }


            $insertNewCust->save();

            if($insertNewCust){
                if(Session::get('role_register')=="freelancer"){
                    $query = "SELECT cust_id FROM customer order by cust_id desc limit 1";
                    $id = DB::select($query)[0]->cust_id;
                    foreach(Session::get('skill_register') as $idSKill){
                        $insertSpesialisasi= new spesialisasi;
                        $insertSpesialisasi->cust_id=$id;
                        $insertSpesialisasi->skill_id=$idSKill;
                        $insertSpesialisasi->save();
                    }
                }

                DB::commit();
                return \redirect("/")->with("success", "Account registration success");
            }else{
                DB::rollBack();
                return \redirect("/register")->with("error", "Account registration failed!");
            }
        }else{
            return \redirect("/register")->with("error", "Email have been registered by other user");
        }
    }
}
