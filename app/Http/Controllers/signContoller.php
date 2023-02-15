<?php

namespace App\Http\Controllers;

use App\Models\modulDiambil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Storage;

class signContoller extends Controller
{
    public function loadESign($idModultaken){
        return view('eSign',[
            'idModultaken'=>$idModultaken
        ]);
    }

    public function uploadSign(Request $request,$idModultaken){
        DB::beginTransaction();
        $modulTaken=modulDiambil::where('modultaken_id',$idModultaken)->first();

        $session= $request->session()->get('name');
        $image_part=explode(";base64,",$request->signed);
        $image_typeaux=explode("image/",$image_part[0]);
        $image_type=$image_typeaux[1];
        $image_base64=base64_decode($image_part[1]);
        $fileSign= $session.'.'.$image_type;
        file_put_contents($fileSign,$image_base64);
        $path= Storage::putFileAs('public/sign',$fileSign,$fileSign);

        if(FacadesSession::get('role')=="freelancer"){
            $modulTaken->freelancer_sign=FacadesSession::get('name');
        }else{
            $modulTaken->client_sign=FacadesSession::get('name');
        }
        $modulTaken->save();
        DB::commit();

        if($path!="" && $path!=null){
            $firebase_storage_path = 'sign/';
            app('firebase.storage')->getBucket()->upload($image_base64, ['name' => $firebase_storage_path . $fileSign]);

            return Redirect::to("reUploadPDF/$idModultaken");
        }
    }
}
