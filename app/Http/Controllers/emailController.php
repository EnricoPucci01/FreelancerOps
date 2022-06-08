<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class emailController extends Controller
{
    public function sendEmail(){
        $details=[
            'title'=>'Kode Verifikasi',
            'body'=>'Mohon Masukan Kode Verifikasi Berikut '.Session::get('uniqueCode').' Pada Website.'
        ];

        Mail::to(Session::get('email_register'))->send(new \App\Mail\myMail($details));

        return view('verificationPage');
    }

    public function verify(Request $request){
        if($request->input('code')==Session::get('uniqueCode')){
            return redirect('/registerUser');
        }else{
            return redirect()->back()->with('error','Kode Verifikasi Tidak Sesuai');
        }
    }
}
