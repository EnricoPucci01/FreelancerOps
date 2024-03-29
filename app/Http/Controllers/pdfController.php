<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\proyek;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class pdfController extends Controller
{
    public function generatePDF($freelanceId, $namaKontrak)
    {

        $date = date('d-m-Y');
        $dataCust = customer::where('cust_id', $freelanceId)->first();
        $dataCust = json_decode(json_encode($dataCust), true);
        $idProyek = Session::get('idProyek');

        $proyek = proyek::where('proyek_id', Session::get('idProyek'))->first();
        $modul = modul::where('modul_id',Session::get('idModulDiambil'))->first();
        $idCust = Session::get('cust_id');
        $data = [
            'sign' => Session::get('name'),
            'freelancer' => $dataCust['nama'],
            'date' => $date,
            "proyek" => $proyek->nama_proyek,
            "modul" => $modul->title,
            "tanggalMulai" => $proyek->start_proyek,
            "deskripsi" => $modul->deskripsi_modul,
            "deadline" => $modul->end,
            "total_pembayaran" => $modul->bayaran
        ];
        $pdf = PDF::loadView('kontrak', $data);
        $pdfFile = $namaKontrak;
        file_put_contents($pdfFile, $pdf->output());
        $store = Storage::putFileAs('public/kontrak', $pdfFile, $namaKontrak);
        Session::put('filePDF', $namaKontrak);
        $firebase_storage_path = 'kontrak/';
        app('firebase.storage')->getBucket()->upload($pdf->output(), ['name' => $firebase_storage_path . $namaKontrak]);

        return Redirect::to("/loadDetailProyekClient/$idProyek/c");
        //return Response::download("storage/kontrak/".$namaKontrak,$namaKontrak,['Cache-Control' => 'no-cache, must-revalidate']);
    }

    public function downloadKontrak($kontrakKerja)
    {
        return Response::download("storage/kontrak/" . $kontrakKerja, $kontrakKerja, ['Cache-Control' => 'no-cache, must-revalidate']);
    }

    public function loadListKontrak($statusKontrak)
    {
        if (Session::get('role') == 'freelancer') {
            $listKontrak = modulDiambil::where('cust_id', Session::get('cust_id'))->where('status', $statusKontrak)->get();

            $listModulKontrak = modul::get();

            $listProyekKontrak = proyek::get();
        } else {
            $idProyek = proyek::where('cust_id', Session::get('cust_id'))->get('proyek_id');
            $idProyek = json_decode(json_encode($idProyek), true);

            $listKontrak = modulDiambil::whereIn('proyek_id', $idProyek)->where('status', $statusKontrak)->get();
            $listModulKontrak = modul::get();

            $listProyekKontrak = proyek::get();
        }
        return view('listKontrak', [
            'listKontrak' => $listKontrak,
            'status' => $statusKontrak,
            'listModul' => $listModulKontrak,
            'listProyek' => $listProyekKontrak
        ]);
    }

    public function reUploadPDF($idModultaken)
    {
        $role = customer::where('cust_id', Session::get('cust_id'))->first();
        $modulTaken = modulDiambil::where('modultaken_id', $idModultaken)->first();
        $proyek = proyek::where('proyek_id', $modulTaken->proyek_id)->first();
        $modul = modul::where('modul_id',$modulTaken->modul_id)->first();

        // return view("kontrak", [
        //     "date"=>Carbon::now()->format("d-m-Y H:i"),
        //     "freelancer"=>"Enrico",
        //     "sign"=>"Maxmillan",
        //     "proyek"=>"proyek 1",
        //     "modul"=>"modul 1",
        //     "tanggalMulai"=>Carbon::now(),
        //     "deskripsi"=>"membuat web interaktif untuk landing page perusahaan",
        //     "deadline"=>Carbon::now(),
        //     "total_pembayaran"=>10000000
        // ]);

        if ($role['role'] == 'freelancer') {
            $client = customer::where('cust_id', $proyek->cust_id)->first();

            $data = [
                'sign' => $client->nama,
                'freelancer' => $role->nama,
                'date' => $modulTaken->created_at,
                "proyek" => $proyek->nama_proyek,
                "modul" => $modul->title,
                "tanggalMulai" => $proyek->start_proyek,
                "deskripsi" => $modul->deskripsi_modul,
                "deadline" => $modul->end,
                "total_pembayaran" => $modul->bayaran
            ];
        } else {
            $freelancer = customer::where('cust_id', $modulTaken->cust_id)->first();
            $data = [
                'sign' => Session::get('name'),
                'freelancer' => $freelancer->nama,
                'date' => $modulTaken->created_at,
                "proyek" => $proyek->nama_proyek,
                "modul" => $modul->title,
                "tanggalMulai" => $proyek->start_proyek,
                "deskripsi" => $modul->deskripsi_modul,
                "deadline" => $modul->end,
                "total_pembayaran" => $modul->bayaran
            ];
        }

        $pdf = PDF::loadView('kontrak', $data);
        $pdfFile = $modulTaken->kontrak_kerja;
        file_put_contents($pdfFile, $pdf->output());
        $store = Storage::putFileAs('public/kontrak', $pdfFile, $modulTaken->kontrak_kerja);
        $firebase_storage_path = 'kontrak/';
        app('firebase.storage')->getBucket()->upload($pdf->output(), ['name' => $firebase_storage_path . $modulTaken->kontrak_kerja]);

        File::delete(Session::get('name') . '.png');

        if ($modulTaken->freelancer_sign != null && $modulTaken->client_sign != null) {
            File::delete(public_path('storage/sign/' . $modulTaken->freelancer_sign . '.png'));
            File::delete(public_path('storage/sign/' . $modulTaken->client_sign . '.png'));
        }

        return Redirect::to('/listKontrak/pengerjaan')->with('success', 'Kontrak Berhasil Di Tanda Tangani !');
    }
    // public function testDownload(){
    //     $date=date('d-m-Y');
    //     $dataCust=customer::where('cust_id',7)->first();
    //     $dataCust=json_decode(json_encode($dataCust),true);
    //     $data=[
    //         'sign'=>Session::get('name'),
    //         'freelancer'=>$dataCust['nama'],
    //         'date'=>$date
    //     ];
    //     $pdf= PDF::loadView('kontrak',$data);
    //     $pdfFile='TestKontrak';
    //     file_put_contents($pdfFile,$pdf->output());
    //     $store=Storage::putFileAs('public/kontrak',$pdfFile,'TestKontrak.pdf');

    //     Session::put('downloadPDF', 'TestKontrak.pdf');


    // }

    // public function Down(){
    //     $pdf=Session::get('downloadPDF');

    //     return Response::download("storage/kontrak/".$pdf,'testDocKontrak.pdf');
    // }
}
