<?php

namespace App\Http\Controllers;

use App\Mail\Subscribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        if($type=='verify'){
            return view('verificationPage');
        }else{
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
}
