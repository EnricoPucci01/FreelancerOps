<?php

namespace App\Http\Controllers;

use App\Mail\Subscribe;
use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class emailController extends Controller
{
    public function sendEmail($mail,$type){
        // $details=[
        //     'title'=>'Kode Verifikasi',
        //     'body'=>'Mohon Masukan Kode Verifikasi Berikut '.Session::get('uniqueCode').' Pada Website.'
        // ];

        // Mail::to(Session::get('email_register'))->send(new \App\Mail\myMail($details));

        // return view('verificationPage');
        Mail::to($mail)->send(new Subscribe($mail,$type));
        // print( new JsonResponse(
        //     [
        //         'success' => true,
        //         'message' => "Thank you for subscribing to our email, please check your inbox"
        //     ], 200
        // ));
        if($type=='verify' || $type=='newPass'){
            return view('verificationPage',[
                'type'=>$type
            ]);
        }
        else{
            return Redirect::back();
        }
    }

    public function verify(Request $request){
        if($request->input('code')==Session::get('uniqueCode')){
            return redirect('/registerUser');
        }else{
            return redirect()->back()->with('error','Kode Verifikasi Tidak Sesuai');
        }
    }

    public function verifyPassChange(Request $request){
        if($request->input('code')==Session::get('uniqueCode')){
            DB::beginTransaction();

            $customer= customer::where('email', Session::get('emailPass'))->first();
            $customer->password = Hash::make(Session::get('newPass'));

            $customer->save();

            if($customer){
                DB::commit();

                Session::forget('emailPass');
                Session::forget('newPass');

                return redirect('/')->with('success','Password Berhasil Di Ubah.');
            }else{
                DB::rollBack();

                Session::forget('emailPass');
                Session::forget('newPass');

                return redirect()->back()->with('error', 'Password Gagal di ubah');
            }
        }else{
            return redirect()->back()->with('error','Kode Verifikasi Tidak Sesuai');
        }
    }
}
