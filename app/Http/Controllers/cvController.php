<?php

namespace App\Http\Controllers;

use App\Models\cv;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class cvController extends Controller
{
    public function uploadCv(Request $request){
        $date=date('Y-m-dH_i_s');
        $filename=Session::get("name").$date.".".$request->file("filecv")->getClientOriginalExtension();
        $path=$request->file('filecv')->storeAs("cv",$filename,'public');
        DB::beginTransaction();

        $insertCv= new cv();
        $insertCv->cust_id=Session::get('cust_id');
        $insertCv->direktori=$filename;
        $insertCv->save();

        if($insertCv){
            DB::commit();
            return redirect("/tambahportofolio")->with("success","CV Berhasil di upload!");
        }

    }

    public function loadCV(){
        DB::beginTransaction();
        $cv= cv::get();
        $dataCv= json_decode(json_encode($cv),true);
        if(!is_null($dataCv)){
            return view('managecv',[
                'dataCv'=>$dataCv
            ]);
        }
    }

    public function previewCV($direktori){

            return Response::download("storage/cv/".$direktori,$direktori);
    }
}
