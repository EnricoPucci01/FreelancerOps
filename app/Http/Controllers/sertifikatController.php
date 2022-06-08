<?php

namespace App\Http\Controllers;

use App\Models\sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class sertifikatController extends Controller
{
    public function insertSertifikat(Request $request){
        DB::beginTransaction();
        //var_dump($request->input('checkambil'));
        $date=date('Y-m-dH_i_s');
        $filename=str_replace(' ','',$request->input('judul')).$date.".".$request->file("fileSertifikat")->getClientOriginalExtension();
        $path=$request->file('fileSertifikat')->storeAs("sertifikat",$filename,'public');

        if($path!="" && $path!=null){
            $image = $request->file('fileSertifikat'); //image file from frontend
            $firebase_storage_path = 'sertifikat/';
            $localfolder = public_path('firebase-temp-uploads') .'/';
            if ($image->move($localfolder, $filename)) {
            $uploadedfile = fopen($localfolder.$filename, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
            //will remove from local laravel folder
            unlink($localfolder . $filename);
            }
        }


        $insertSertifikat=new sertifikat();
        $insertSertifikat->nama_sertifikat=$request->input('judul');
        $insertSertifikat->cust_id=Session::get('cust_id');
        $insertSertifikat->deskripsi_sertifikat=$request->input('desc_sertifikat');
        $insertSertifikat->direktori=$filename;
        $insertSertifikat->save();

        if($insertSertifikat){
            DB::commit();
            return redirect()->back()->with('success','Sertifikat Berhasil di Upload');
        }else{
            DB::rollback();
            return redirect()->back()->with('error','Sertifikat Gagal di Upload');
        }
    }

    public function downloadSertifikat($direktori){
        return Response::download("storage/sertifikat/".$direktori,$direktori);
    }
}
