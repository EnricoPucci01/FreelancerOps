<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\kategori;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\payment;
use App\Models\penarikan;
use App\Models\profil;
use App\Models\review;
use App\Models\sertifikat;
use App\Models\tag;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as FacadesSession;
use Xendit\Xendit;
use App\Models\tambahRekening;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class profilController extends Controller
{
    public function loadProfil($role,$custId){
        $profil=profil::where('cust_id',$custId)->first();
        $profil=json_decode(json_encode($profil),true);

        $cust=customer::where('cust_id',$custId)->first();
        $cust=json_decode(json_encode($cust),true);

        $sertifikat=sertifikat::where('cust_id',$custId)->paginate(5);
        // $sertifikat=json_decode(json_encode($sertifikat),true);

        $review=review::where('freelancer_id',$custId)->get();
        $review=json_decode(json_encode($review),true);

        $proyekSelesai=modulDiambil::where('cust_id',$custId)->where('status','selesai')->count();

        $totalBintang=0;
        $rataRata=0;
        if(count($review)>0){
            $jumlahReview= count($review);
            foreach ($review as $bintang) {
                $totalBintang= $totalBintang+$bintang['bintang'];
            }

            $rataRata= $totalBintang/$jumlahReview;
            //dd($jumlahReview);
        }

        return view('profil',[
            'dataProfil'=>$profil,
            'dataCust'=>$cust,
            'dataSertifikat'=>$sertifikat,
            'role'=>$role,
            'bintang'=>$rataRata,
            'proyekSelesai'=>$proyekSelesai
        ]);
    }

    public function loadProfilApplicant($role,$custId,$applicantId,$modulId,$proyekId){
        $profil=profil::where('cust_id',$custId)->first();
        $profil=json_decode(json_encode($profil),true);

        $cust=customer::where('cust_id',$custId)->first();
        $cust=json_decode(json_encode($cust),true);

        $sertifikat=sertifikat::where('cust_id',$custId)->paginate(5);
        // $sertifikat=json_decode(json_encode($sertifikat),true);

        $review=review::where('freelancer_id',$custId)->get();
        $review=json_decode(json_encode($review),true);

        $proyekSelesai=modulDiambil::where('cust_id',$custId)->where('status','selesai')->count();

        $totalBintang=0;
        $rataRata=0;
        if(count($review)>0){
            $jumlahReview= count($review);
            foreach ($review as $bintang) {
                $totalBintang= $totalBintang+$bintang['bintang'];
            }

            $rataRata= $totalBintang/$jumlahReview;
            //dd($jumlahReview);
        }


        return view('profil',[
            'dataProfil'=>$profil,
            'dataCust'=>$cust,
            'dataSertifikat'=>$sertifikat,
            'role'=>$role,
            'applicantId'=>$applicantId,
            'modulId'=>$modulId,
            'proyekId'=>$proyekId,
            'bintang'=>$rataRata,
            'proyekSelesai'=>$proyekSelesai
        ]);
    }

    public function loadEditProfil($custId){
        $profil=profil::where('cust_id',$custId)->first();
        $profil=json_decode(json_encode($profil),true);

        $cust=customer::where('cust_id',$custId)->first();
        $cust=json_decode(json_encode($cust),true);



        return view('editprofil',[
            'dataProfil'=>$profil,
            'dataCust'=>$cust
        ]);
    }

    public function editProfil(Request $request,$custId){
        $formValidate=$request->validate([
            'profile_nama'=>'required',
            'profile_hp'=>'required|numeric|digits_between:10,13',
            'profile_desc'=>'max:1000',
            'profile_foto'=>'image'
        ],[
            'profile_nama.required'=>'Nama tidak dapat kosong!',
            'profile_hp.required'=>'Nomor HP tidak dapat kosong!',
            'profile_hp.numeric'=>'Nomor HP hanya boleh berisi angka!',
            'profile_hp.digits_between'=>'Nomor HP anda melebihi 13 digit atau kurang dari 10 digit!',
            'profile_desc.max'=>'Panjang maksimal deskripsi diri adalah 1000 karakter!',
            'profile_foto.image'=>'Format file untuk foto profil adalah .jpg,.png,.gif!'
        ]);


        DB::beginTransaction();
        $editCust=customer::where('cust_id',$custId)->first();
        $editCust->nama=$request->input('profile_nama');
        $editCust->nomorhp=$request->input('profile_hp');
        $editCust->save();

        $filename="";

        if($editCust){
            $date=date('Y-m-dH_i_s');
            if(!empty($request->file('profile_foto'))){
                $filename=FacadesSession::get("name").$date.".".$request->file("profile_foto")->getClientOriginalExtension();
                $path=$request->file('profile_foto')->storeAs("profilePic",$filename,'public');

                if($path!=""&&$path!=null){
                    $image = $request->file('profile_foto'); //image file from frontend
                    $firebase_storage_path = 'profilePic/';
                    $localfolder = public_path('firebase-temp-uploads') .'/';
                    if ($image->move($localfolder, $filename)) {
                    $uploadedfile = fopen($localfolder.$filename, 'r');
                    app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                    //will remove from local laravel folder
                    unlink($localfolder . $filename);
                    }
                }
            }

            $editProf=profil::where('cust_id',$custId)->first();

            if(!empty($editProf)){
                $editProf->pekerjaan=$request->input('profile_job');
                $editProf->deskripsi_diri=$request->input('profile_desc');
                $editProf->foto=$filename;
                $editProf->save();
                if($editProf){
                    DB::commit();
                    return redirect()->back()->with('success','Profil Berhasil Di Update!');
                }else{
                    DB::rollback();
                    return redirect()->back()->with('error','Profil Gagal Di Update!');
                }
            }else{
                $editProf=new profil();
                $editProf->cust_id=$custId;
                $editProf->pekerjaan=$request->input('profile_job');
                $editProf->deskripsi_diri=$request->input('profile_desc');
                $editProf->foto=$filename;
                $editProf->save();
                if($editProf){
                    DB::commit();
                    return redirect()->back()->with('success','Profil Berhasil Di Update!');
                }else{
                    DB::rollback();
                    return redirect()->back()->with('error','Profil Gagal Di Update!');
                }
            }


        }else{
            DB::rollback();
            return redirect()->back()->with('error','Profil Gagal Di Update!');
        }
    }

    public function loadHistori(){
        $dataPayment=payment::where('cust_id',session()->get('cust_id'))->where('status','Completed')->get();
        $dataPayment=json_decode(json_encode($dataPayment),true);

        $dataPenarikan=penarikan::withTrashed()->where('cust_id',session()->get('cust_id'))->get();
        $dataPenarikan=json_decode(json_encode($dataPenarikan),true);

        $total=0;
        foreach ($dataPayment as $key) {
            $total=$total+(int) $key['amount'];
        }

        $totalPenarikan=0;
        foreach ($dataPenarikan as $penarikan) {
            if(!is_null($penarikan['tanggal_admit'])){
                $totalPenarikan=$totalPenarikan+(int) $penarikan['jumlah'];
            }
        }

        $sisaSaldo = (int)$total - (int)$totalPenarikan;

        return view('histori',[
            'dataPayment'=>$dataPayment,
            'total'=>$total,
            'dataPenarikan'=>$dataPenarikan,
            'totalPenarikan'=>$totalPenarikan,
            'sisaSaldo'=>$sisaSaldo
        ]);
    }

    public function loadReview($custId){
        $review= review::where('freelancer_id',$custId)->get();
        $review=json_decode(json_encode($review),true);

        $reviewclient= review::where('freelancer_id',$custId)->get('client_id');
        $reviewclient=json_decode(json_encode($reviewclient),true);

        $reviewmodul= review::where('freelancer_id',$custId)->get('modul_id');
        $reviewmodul=json_decode(json_encode($reviewmodul),true);

        $client=customer::whereIn('cust_id',$reviewclient)->get();
        $client=json_decode(json_encode($client),true);

        $modul=modul::whereIn('modul_id',$reviewmodul)->get();
        $modul=json_decode(json_encode($modul),true);

        $profile= profil::get();

        $proyekSelesai=modulDiambil::where('cust_id',$custId)->where('status','selesai')->count();

        $totalBintang=0;
        $rataRata=0;
        $jumlahReview=0;
        $bintang5=0;
        $bintang4=0;
        $bintang3=0;
        $bintang2=0;
        $bintang1=0;
        if(count($review)>0){
            $jumlahReview= count($review);
            foreach ($review as $bintang) {
                $totalBintang= $totalBintang+$bintang['bintang'];

                if($bintang['bintang']==5){
                    $bintang5++;
                }
                else if($bintang['bintang']==4){
                    $bintang4++;
                }
                else if($bintang['bintang']==3){
                    $bintang3++;
                }
                else if($bintang['bintang']==2){
                    $bintang2++;
                }
                else if($bintang['bintang']==1){
                    $bintang1++;
                }
            }

            $rataRata= $totalBintang/$jumlahReview;
            //dd($jumlahReview);
        }

        $bintang5=$bintang5/$jumlahReview*100;
        $bintang4=$bintang4/$jumlahReview*100;
        $bintang3=$bintang3/$jumlahReview*100;
        $bintang2=$bintang2/$jumlahReview*100;
        $bintang1=$bintang1/$jumlahReview*100;

        return view('reviewFreelancer',[
            'dataReview'=>$review,
            'client'=>$client,
            'modul'=>$modul,
            'rataRata'=>$rataRata,
            'proyekSelesai'=>$proyekSelesai,
            'jumlahReview'=>$jumlahReview,
            'profil'=>$profile,
            'bintang5'=>$bintang5,
            'bintang4'=>$bintang4,
            'bintang3'=>$bintang3,
            'bintang2'=>$bintang2,
            'bintang1'=>$bintang1,
        ]);
    }

    public function loadHistoriProyek($custId){
        $listModulSelesai=modulDiambil::where('cust_id',$custId)->where('status','selesai')->get('modul_id');

        $modulSelesai=modul::whereIn('modul_id',$listModulSelesai)->get();
        $modulSelesai=json_decode(json_encode($modulSelesai),true);

        $listTag=tag::get();
        $listTag=json_decode(json_encode($listTag),true);

        $listKategori=kategori::get();
        $listKategori=json_decode(json_encode($listKategori),true);


        return view('historiProyek',[
            'modulSelesai'=>$modulSelesai,
            'listtag'=>$listTag,
            'listkategori'=>$listKategori
        ]);

    }

    public function loadHistoriTransaksi(){
        $dataPayment= payment::where('email',session()->get('active'))->get();
        $dataPayment=json_decode(json_encode($dataPayment),true);

        $dataFreelancer= customer::where('role','freelancer')->get();
        $dataFreelancer=json_decode(json_encode($dataFreelancer),true);

        $dataModul=modul::get();
        $dataModul=json_decode(json_encode($dataModul),true);

        return view('historiTransaksi',[
            'dataPayment'=>$dataPayment,
            'dataFreelancer'=>$dataFreelancer,
            'dataModul'=>$dataModul
        ]);
    }

    public function tambahRekening(Request $request){
        $formValidate=$request->validate([
            'no_rek'=>'required|numeric|digits_between:10,16',
            'bank'=>'required',
        ],[
            'no_rek.required'=>'Nomor rekening tidak dapat kosong!',
            'bank.required'=>'Bank tidak dapat kosong!',
            'no_rek.numeric'=>'Nomor rekening hanya dapat berisi angka!',
            'no_rek.digits_between'=>'Nomor rekening anda melebihi 16 digit atau kurang dari 10 digit!',
        ]);

        DB::beginTransaction();
        $rekening= new tambahRekening();
        $rekening->nomor_rek=$request->input('no_rek');
        $rekening->bank=$request->input('bank');
        $rekening->cust_id=session()->get('cust_id');
        $rekening->save();

        if($rekening){
            DB::commit();
            return Redirect::back()->with('success','Nomor Rekening Berhasil Di Tambah');
        }else{
            DB::rollback();
            return Redirect::back()->with('error','Nomor Rekening Gagal Di Tambahkan');
        }
    }
}
