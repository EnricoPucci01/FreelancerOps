<?php

namespace App\Http\Controllers;

use App\Models\applicant;
use App\Models\customer;
use App\Models\error_report;
use App\Models\jobKategori;
use App\Models\kategori;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\payment;
use App\Models\progress;
use App\Models\proyek;
use App\Models\review;
use App\Models\tag;
use Barryvdh\DomPDF\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Environment\Console;
use Xendit\Customers;

class projectController extends Controller
{
    public function loadPostProject()
    {
        $listKategori = kategori::get();
        $listKategori = json_decode(json_encode($listKategori), true);

        $listJobKat = jobKategori::get();
        return view('postproject', [
            'kategoriList' => $listKategori,
            'jobKatList' => $listJobKat
        ]);
    }

    public function loadPostModul(Request $request)
    {
        if ($request->input('tipe_proyek') == "magang") {
            $formValidate = $request->validate([
                'name_project' => 'required|max:255',
                'desc_project' => 'required|max:1000',
                'total_pembayaran' => 'numeric|min:0',
                'deadline' => 'required'
            ], [
                'name_project.required' => 'Nama proyek tidak dapat kosong!',
                'name_project.max' => 'Panjang maksimal nama proyek adalah 255 karakter!',
                'desc_project.required' => 'Deskripsi proyek tidak dapat kosong!',
                'desc_project.max' => 'Panjang maksimal deskripsi proyek adalah 1000 karakter!',
                'total_pembayaran.numeric' => 'Total pembayaran hanya dapat di isi dengan angka!',
                'total_pembayaran.min' => 'Total pembayaran untuk proyek magang dapat kosong!',
                'deadline.required' => 'Deadline dari proyek tidak dapat kosong!'
            ]);
        } else {
            $formValidate = $request->validate([
                'name_project' => 'required|max:255',
                'desc_project' => 'required|max:1000',
                'total_pembayaran' => 'numeric|min:500000',
                'deadline' => 'required'
            ], [
                'name_project.required' => 'Nama proyek tidak dapat kosong!',
                'name_project.max' => 'Panjang maksimal nama proyek adalah 255 karakter!',
                'desc_project.required' => 'Deskripsi proyek tidak dapat kosong!',
                'desc_project.max' => 'Panjang maksimal deskripsi proyek adalah 1000 karakter!',
                'total_pembayaran.numeric' => 'Total pembayaran hanya dapat di isi dengan angka!',
                'total_pembayaran.min' => 'Total pembayaran minimal untuk proyek normal adalah 500.0000!',
                'deadline.required' => 'Deadline dari proyek tidak dapat kosong!'
            ]);
        }

        Session::put('name_project', $request->input("name_project"));
        Session::put('desc_project', $request->input("desc_project"));
        Session::put('tipe_proyek', $request->input("tipe_proyek"));
        Session::put('kategorijob_project', $request->input("kategorijob_project"));
        Session::put('kategori_project', $request->input("kategori_project"));
        Session::put('deadline', $request->input("deadline"));
        Session::put('tanggal_mulai', $request->input("tanggal_mulai"));

        $tag = kategori::get();
        $kategoriJob = jobKategori::get();
        return view('postModulProyek', [
            'tag' => $tag,
            'kategoriJob' => $kategoriJob
        ]);
    }
    public function submitPostProject(Request $request)
    {

        $modulArr = [];
        $modulMagangArr = [];
        if (Session::get('tipe_proyek') == 'magang') {
            for ($i = 0; $i < (int)$request->input('hid_val'); $i++) {
                $modulTemp = array(
                    "nama_modul" => $request->input("nama_modul" . $i . ""),
                    "deskripsi_modul" => $request->input("desc_modul" . $i . ""),
                    "bayaran1" => $request->input("rentang1_bayaran" . $i . ""),
                    "bayaran2" => $request->input("rentang2_bayaran" . $i . ""),
                    "deadline_modul" => $request->input("deadline_modul" . $i . ""),
                );
                array_push($modulMagangArr, $modulTemp);
            }
        } else {
            for ($i = 0; $i < (int)$request->input('hid_val'); $i++) {
                $modulTemp = array(
                    "nama_modul" => $request->input("nama_modul" . $i . ""),
                    "deskripsi_modul" => $request->input("desc_modul" . $i . ""),
                    "bayaran" => $request->input("bayaran" . $i . ""),
                    "deadline_modul" => $request->input("deadline_modul" . $i . ""),
                );
                array_push($modulArr, $modulTemp);
            }
        }
        //dd($modulArr);
        DB::beginTransaction();
        $postProject = new proyek();
        $postProject->cust_id = Session::get('cust_id');
        $postProject->nama_proyek = Session::get('name_project');
        $postProject->desc_proyek = Session::get('desc_project');

        if (Session::get('tipe_proyek') == "magang") {
            $postProject->total_pembayaran = "0";
            $postProject->range_bayaran1 = $request->input('rentang_pembayaran1');
            $postProject->range_bayaran2 = $request->input('rentang_pembayaran2');
        } else {
            $postProject->total_pembayaran = $request->input('total_pembayaran');
        }

        $postProject->tipe_proyek = Session::get('tipe_proyek');
        $postProject->start_proyek = Session::get('tanggal_mulai');
        $postProject->deadline = Session::get('deadline');
        $postProject->kategorijob_id = Session::get('kategorijob_project');
        $postProject->save();

        if ($postProject) {
            $query = "SELECT proyek_id FROM proyek order by proyek_id desc limit 1";
            $id = DB::select($query)[0]->proyek_id;

            foreach (Session::get('kategori_project') as $kategoriId) {
                $tag = new tag();
                $tag->kategori_id = $kategoriId;
                $tag->proyek_id = $id;
                $tag->save();
            }

            if (Session::get('tipe_proyek') == "magang") {
                //dd($modulMagangArr);
                foreach ($modulMagangArr as $itemArr) {
                    $modul = new modul();
                    $modul->proyek_id = $id;
                    $modul->title = $itemArr['nama_modul'];
                    $modul->deskripsi_modul = $itemArr['deskripsi_modul'];
                    $modul->bayaran = 0;
                    $modul->bayaran_min = $itemArr['bayaran1'];
                    $modul->bayaran_max = $itemArr['bayaran2'];
                    $modul->status = 'not taken';
                    $modul->end = $itemArr['deadline_modul'];
                    $modul->save();
                }
            }
            if (Session::get('tipe_proyek') == "normal") {

                foreach ($modulArr as $itemArr) {
                    $modul = new modul();
                    $modul->proyek_id = $id;
                    $modul->title = $itemArr['nama_modul'];
                    $modul->deskripsi_modul = $itemArr['deskripsi_modul'];
                    $modul->bayaran = $itemArr['bayaran'];
                    $modul->bayaran_min = 0;
                    $modul->bayaran_max = 0;
                    $modul->status = 'not taken';
                    $modul->end = $itemArr['deadline_modul'];
                    $modul->save();
                }
            }


            if ($modul && $tag && $postProject) {
                DB::commit();
                return \redirect("/postproject")->with('success', 'proyek anda telah berhasil di publikasikan');
            }
        }
    }

    public function loadBrowseProject()
    {
        $listProject = proyek::where('start_proyek', ">=", Carbon::now())->paginate(5);
        //$listProject=json_decode(json_encode($listProject),true);

        $listKategori = kategori::get();
        $listKategori = json_decode(json_encode($listKategori), true);

        $listKategoriJob = jobKategori::get();
        $listKategoriJob = json_decode(json_encode($listKategoriJob), true);

        $listTag = tag::get();
        $listTag = json_decode(json_encode($listTag), true);

        return view('browseproject', [
            'listkategori' => $listKategori,
            'listtag' => $listTag,
            'listproyek' => $listProject,
            'custId' => Session::get('cust_id'),
            'listkategoriJob' => $listKategoriJob,
        ]);
    }

    public function loadBrowseProjectClient($idCust)
    {
        $listProject = proyek::where('cust_id', $idCust)->paginate(5);
        //$listProject=json_decode(json_encode($listProject),true);

        $listKategori = kategori::get();
        $listKategori = json_decode(json_encode($listKategori), true);

        $listTag = tag::get();
        $listTag = json_decode(json_encode($listTag), true);

        $listKategoriJob = jobKategori::get();
        $listKategoriJob = json_decode(json_encode($listKategoriJob), true);

        return view('listProjectClient', [
            'listkategori' => $listKategori,
            'listtag' => $listTag,
            'listproyek' => $listProject,
            'idCust' => $idCust,
            'listkategoriJob' => $listKategoriJob
        ]);
    }

    public function cariProyek(Request $request)
    {
        $listTag = tag::get();
        $listTag = json_decode(json_encode($listTag), true);

        $listKategoriJob = jobKategori::get();
        $listKategoriJob = json_decode(json_encode($listKategoriJob), true);

        $listKategori = kategori::get();
        $listKategori = json_decode(json_encode($listKategori), true);

        if ($request->input('kategori_browse') != NULL) {
            $proyekFilterKategori = tag::whereIn('kategori_id', $request->kategori_browse)->get('proyek_id');
            $proyekFilterKategori = json_decode(json_encode($proyekFilterKategori), true);

            $listProyek = proyek::whereIn('proyek_id', $proyekFilterKategori)
                ->where('tipe_proyek', $request->tipe_proyek)->where('nama_proyek', 'like', '%' . $request->searchProyek . '%')->paginate(5)->appends($request->all());
        } else {
            $listProyek = proyek::where('tipe_proyek', $request->tipe_proyek)->where('nama_proyek', 'like', '%' . $request->searchProyek . '%')->paginate(5)->appends($request->all());
        }

        return view('browseproject', [
            'listkategori' => $listKategori,
            'listkategoriJob' => $listKategoriJob,
            'listtag' => $listTag,
            'listproyek' => $listProyek,
            'custId' => Session::get('cust_id'),
        ]);
    }

    public function loadProyek($id, $custId)
    {
        $dataproyek = proyek::where('proyek_id', $id)->first();
        $dataproyek = json_decode(json_encode($dataproyek), true);

        $datamodul = modul::where('proyek_id', $id)->get();
        $datamodul = json_decode(json_encode($datamodul), true);

        $modulTaken = modulDiambil::where('proyek_id', $id)->where('cust_id', $custId)->where('status', '!=', 'dibatalkan')->get();
        $modulTaken = json_decode(json_encode($modulTaken), true);

        return view('detailproyek', [
            "dataproyek" => $dataproyek,
            "datamodul" => $datamodul,
            'datamodultaken' => $modulTaken
        ]);
    }

    public function loadProyekClient($id, $accessor, $idCust)
    {
        $dataproyek = proyek::where('proyek_id', $id)->first();
        $dataproyek = json_decode(json_encode($dataproyek), true);

        $datamodul = modul::where('proyek_id', $id)->get();
        $datamodul = json_decode(json_encode($datamodul), true);

        $modulTaken = modulDiambil::where('proyek_id', $id)->where('status', "!=", 'dibatalkan')->get();
        $modulTaken = json_decode(json_encode($modulTaken), true);

        $payment = payment::get();
        $payment = json_decode(json_encode($payment), true);

        return view('detailProyekClient', [
            "dataproyek" => $dataproyek,
            "datamodul" => $datamodul,
            'modulDiambil' => $modulTaken,
            'datapayment' => $payment,
            'accessor' => $accessor,
            'id' => $idCust
        ]);
    }

    public function ajukancv(Request $request)
    {
        if (!empty($request->file("filecv"))) {
            DB::beginTransaction();
            //var_dump($request->input('checkambil'));
            $date = date('Y-m-d H_i_s');
            $filename = Session::get("name") . $date . "." . $request->file("filecv")->getClientOriginalExtension();
            $path = $request->file('filecv')->storeAs("cv", $filename, 'public');

            if ($path != "" && $path != null) {
                $fileCV = $request->file('filecv');
                $firebase_storage_path = 'cv/';
                $localfolder = public_path('firebase-temp-uploads') . '/';
                if ($fileCV->move($localfolder, $filename)) {
                    $uploadedfile = fopen($localfolder . $filename, 'r');
                    app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                    //will remove from local laravel folder
                    unlink($localfolder . $filename);
                }

                foreach ($request->input('checkambil') as $diambil) {
                    $newApplicant = new applicant();
                    $newApplicant->proyek_id = $request->input('id_proyek');
                    $newApplicant->cust_id = Session::get('cust_id');
                    $newApplicant->modul_id = $diambil;
                    $newApplicant->cv = $filename;
                    $newApplicant->applicant_desc = $request->input("custDesc");
                    $newApplicant->status = 'pending';
                    $newApplicant->save();
                }
                if ($newApplicant) {
                    DB::commit();
                    return Redirect::back()->with("success", 'CV berhasil di ajukan');
                } else {
                    DB::rollback();
                    return Redirect::back()->with("error", 'CV gagal di ajukan');
                }
            } else {
                DB::rollback();
                return Redirect::back()->with("error", 'CV gagal di ajukan');
            }
        } else {
            return Redirect::back()->with("error", 'CV anda kosong');
        }
    }

    public function loadApplicant($modulId, $proyekId, $idCust)
    {
        $applicantModul = applicant::where('proyek_id', $proyekId)->where('modul_id', $modulId)->get('cust_id');
        $applicantModul = json_decode(json_encode($applicantModul), true);

        $customerList = customer::whereIn('cust_id', $applicantModul)->get();
        $customerList = json_decode(json_encode($customerList), true);

        $applicantModul = applicant::where('proyek_id', $proyekId)->where('modul_id', $modulId)->get();
        $applicantModul = json_decode(json_encode($applicantModul), true);

        //var_dump($customerList);

        return view(
            'applicant',
            [
                "applicantList" => $customerList,
                "cv" => $applicantModul,
                "modulId" => $modulId,
                "proyekId" => $proyekId,
                'idCust' => $idCust
            ]
        );
    }

    public function terimaApplicant($custId, $modulId, $proyekId, $applicantId)
    {
        $dateTime = date('Y-m-d');
        $nama = customer::where('cust_id', $custId)->first();
        $modul = modul::where('modul_id', $modulId)->first();
        $nama_kontrak = 'KontrakKerja_' . $nama->nama . '_' . str_replace(' ', '', $modul->title) . '_' . $dateTime . '.pdf';
        DB::beginTransaction();
        $insertTakenModul = new modulDiambil();
        $insertTakenModul->cust_id = $custId;
        $insertTakenModul->proyek_id = $proyekId;
        $insertTakenModul->modul_id = $modulId;
        $insertTakenModul->kontrak_kerja = $nama_kontrak;
        $insertTakenModul->status = 'pengerjaan';
        $insertTakenModul->save();

        if ($insertTakenModul) {
            $delApplicant = applicant::find($applicantId);
            $delApplicant->status = 'diterima';
            $delApplicant->save();
            if ($delApplicant) {
                $delApplicant->delete();
                $upModul = modul::where('modul_id', $modulId)->first();
                $upModul->start = $dateTime;
                $upModul->status = 'taken';
                $upModul->save();
                if ($upModul) {
                    DB::commit();
                    Session::put('idProyek', $proyekId);
                    return Redirect::to("/generatePDF/$custId/$nama_kontrak");
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with('error', 'Freelancer gagal di terima!');
            }
        }
    }

    public function loadListProyekFreelancer($custId)
    {
        $modulDiambil = modulDiambil::where('cust_id', $custId)->where('status', "!=", 'selesai')->where('status', '!=', 'dibatalkan')->get('modul_id');
        $modulDiambil = json_decode(json_encode($modulDiambil), true);

        $modulFreelancer = modul::whereIn('modul_id', $modulDiambil)->paginate(5);
        // $modulFreelancer=json_decode(json_encode($modulFreelancer),true);


        return view('listProyekFreelancer', [
            'listproyek' => $modulFreelancer,
            'custId' => $custId
        ]);
    }

    public function loadDetailModulFreelancer($modulId, $custId)
    {
        $dataModul = modul::where('modul_id', $modulId)->first();
        $dataModul = json_decode(json_encode($dataModul), true);

        $dataProyek = proyek::where('proyek_id', $dataModul['proyek_id'])->first();
        $dataProyek = json_decode(json_encode($dataProyek), true);
        //dd($dataModul);

        $kontrak = modulDiambil::where('modul_id', $modulId)->first();
        $kontrak = json_decode(json_encode($kontrak), true);
        //dd($kontrak);
        return view('detailModulFreelancer', [
            'dataModul' => $dataModul,
            'dataProyek' => $dataProyek,
            'kontrak' => $kontrak,
            'custId' => $custId,
        ]);
    }

    public function updateProgress(Request $request, $modulId, $tipeProyek)
    {
        $formValidate = $request->validate(
            [
                'progDesc' => 'max:1000'
            ],
            [
                'progDesc.max' => 'Panjang maksimal nama modul adalah 1000 karakter!'
            ]
        );

        DB::beginTransaction();
        $filename = "";
        $status = '';
        if ($request->has('cb')) {
            $status = 'finish';
        } else {
            $status = 'progress';
        }
        $UploadDate = date('d/m/Y H:i:s');
        if (!empty($request->file("fileModul"))) {
            $date = date('Y-m-dH_i_s');
            $filename = $modulId . $date . "." . $request->file("fileModul")->getClientOriginalExtension();
            $path = $request->file('fileModul')->storeAs("progress", $filename, 'public');

            if ($path != "" && $path != null) {
                $image = $request->file('fileModul'); //image file from frontend
                $firebase_storage_path = 'progress/';
                $localfolder = public_path('firebase-temp-uploads') . '/';
                if ($image->move($localfolder, $filename)) {
                    $uploadedfile = fopen($localfolder . $filename, 'r');
                    app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                    //will remove from local laravel folder
                    unlink($localfolder . $filename);
                }
            }
        }
        $updateProgress = new progress();
        $updateProgress->modul_id = $modulId;
        $updateProgress->upload_time = $UploadDate;
        $updateProgress->file_dir = $filename;
        $updateProgress->progress = $request->input('progDesc');
        $updateProgress->status = $status;
        $updateProgress->save();

        if ($updateProgress) {
            if ($status == "finish") {
                $modul = modul::where('modul_id', $modulId)->first();
                $modul->status = $status;
                $modul->save();
                if ($modul) {
                    $modulDiambil = modulDiambil::where('modul_id', $modulId)->first();
                    $modulDiambil->status = 'selesai';
                    $modulDiambil->save();
                    if ($modulDiambil) {
                        DB::commit();
                        if ($tipeProyek == 'normal') {
                            return redirect("/generateva/$modulId");
                        } else {
                            return Redirect::back()->with('success', 'Modul Magang Telah Berhasil Di Selesaikan!');
                        }
                    } else {
                        DB::rollback();
                        return Redirect::back()->with('error', 'Modul Gagal Di Selesaikan!');
                    }
                } else {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Progress Gagal Di Update!');
                }
            } else {
                DB::commit();
                return Redirect::back()->with('success', 'Progress Berhasil Di Update!');
            }
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Progress Gagal Di Update!');
        }
    }

    public function loadProgress($modulId, $modulTakenId)
    {
        $dataModul = modul::where('modul_id', $modulId)->first();
        $dataModul = json_decode(json_encode($dataModul), true);

        $dataProgress = progress::where('modul_id', $modulId)->orderBy('created_at', 'DESC')->get();
        $dataProgress = json_decode(json_encode($dataProgress), true);

        $dataProyek = proyek::where('proyek_id', $dataModul['proyek_id'])->first();
        $dataProyek = json_decode(json_encode($dataProyek), true);


        return view('progressModul', [
            'dataProgress' => $dataProgress,
            'dataModul' => $dataModul,
            'dataProyek' => $dataProyek,
            'modulTakenId' => $modulTakenId
        ]);
    }

    public function downloadProgress($status, $filename)
    {
        if ($status == 'progress') {
            return Response::download("storage/progress/" . $filename, $filename);
        } else {
            return Response::download("storage/progress/" . $filename, $filename);
        }
    }

    public function batalkanFreelancer($modulTakenId)
    {
        DB::beginTransaction();
        $modulTerambil = modulDiambil::where('modultaken_id', $modulTakenId)->first();
        $modulTerambil->status = 'dibatalkan';
        $modulTerambil->save();
        if ($modulTerambil) {
            $modul = modul::where('modul_id', $modulTerambil->modul_id)->first();
            $modul->status = 'not taken';
            $modul->save();

            if ($modul && $modulTerambil) {
                DB::commit();

                return redirect()->back()->with('success', 'Freelancer berhasil dibatalkan!');
            } else {
                DB::rollback();

                return redirect()->back()->with('error', 'Freelancer gagal dibatalkan!');
            }
        }
    }

    public function closeModul($paymentId)
    {
        $dataPayment = payment::where('payment_id', $paymentId)->first();
        $dataPayment->status = 'close';
        $dataPayment->save();

        if ($dataPayment) {
            return Redirect::back()->with('success', 'Permintaan Penutupan Modul Dan Pembayaran Berhasil Di Ajukan!');
        } else {
            return Redirect::back()->with('error', 'Permintaan Penutupan Modul Dan Pembayaran Gagal Di Ajukan!');
        }
    }

    public function reviewPage($modulId, $freelancerId, $clientId)
    {
        $freelancer = customer::where('cust_id', $freelancerId)->first();
        $freelancer = json_decode(json_encode($freelancer), true);

        $modul = modul::where('modul_id', $modulId)->first();
        $modul = json_decode(json_encode($modul), true);

        return view('reviewPage', [
            'freelancer' => $freelancer,
            'modul' => $modul,
            'client' => $clientId,
            'proyekId' => $modul['proyek_id']
        ]);
    }

    public function submitReview(Request $request, $freelancerId, $clientId, $modulId)
    {
        DB::beginTransaction();

        $bintang = $request->input('rate');
        //dd($bintang);
        $review = $request->input('revDesc');
        $reviewTime = date('Y-m-d H:i:s');

        $insertReview = new review();
        $insertReview->client_id = $clientId;
        $insertReview->freelancer_id = $freelancerId;
        $insertReview->modul_id = $modulId;
        $insertReview->bintang = $bintang;
        $insertReview->review_desc = $review;
        $insertReview->review_time = $reviewTime;
        $insertReview->save();

        if ($insertReview) {
            DB::commit();
            return Redirect::back()->with('success', 'Review Berhasil Diberikan!');
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Review Gagal Diberikan!');
        }
    }

    public function reportError(Request $request, $modulId, $freelancerId)
    {
        DB::beginTransaction();

        $date = date('Y-m-d H:i:s');
        $filename = date('YmdHis') . '_' . $modulId . "." . $request->file("fileError")->getClientOriginalExtension();
        $path = $request->file('fileError')->storeAs("error", $filename, 'public');

        if ($path != "" && $path != null) {
            $image = $request->file('fileError'); //image file from frontend
            $firebase_storage_path = 'error/';
            $localfolder = public_path('firebase-temp-uploads') . '/';
            if ($image->move($localfolder, $filename)) {
                $uploadedfile = fopen($localfolder . $filename, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                //will remove from local laravel folder
                unlink($localfolder . $filename);
            }
        }

        $insertError = new error_report();
        $insertError->modul_id = $modulId;
        $insertError->freelancer_id = $freelancerId;
        $insertError->halaman_error = $request->input('errPage');
        $insertError->aksi = $request->input('errAct');
        $insertError->report_desc = $request->input('errDesc');
        $insertError->report_data = $filename;
        $insertError->report_time = $date;
        $insertError->save();

        if ($insertError) {
            DB::commit();
            return Redirect::back()->with('success', 'Error Telah Berhasil Di Laporkan!');
        } else {
            DB::rollback();
            return Redirect::back()->with('success', 'Error Gagal Di Laporkan!');
        }
    }

    public function loadErrorReport($modulId)
    {
        $freelancerId = modulDiambil::where('modul_id', $modulId)->where('status', '!=', 'dibatalkan')->first();
        return view('errorReportPage', [
            'modulId' => $modulId,
            'freelancerId' => $freelancerId->cust_id
        ]);
    }

    public function loadLaporanError($modulId)
    {
        $listError = error_report::where('modul_id', $modulId)->where('freelancer_id', Session::get('cust_id'))->get();
        $listError = json_decode(json_encode($listError), true);

        $listModul = modul::where('modul_id', $modulId)->first();
        $listModul = json_decode(json_encode($listModul), true);

        return view('laporanError', [
            'listError' => $listError,
            'listModul' => $listModul
        ]);
    }

    public function downloadFileError($reportData)
    {
        return Response::download('storage/error/' . $reportData);
    }

    public function selesaikanError(Request $request, $modulId)
    {
        $UploadDate = date('d/m/Y H:i:s');
        $date = date('Y-m-dH_i_s');
        $filename = $modulId . $date . "." . $request->file("fileSelesai")->getClientOriginalExtension();
        $path = $request->file('fileSelesai')->storeAs("progress", $filename, 'public');

        foreach ($request->input('checkError') as $errorSelesai) {
            DB::beginTransaction();
            $error = error_report::where('report_id', $errorSelesai)->first();

            $progress = new progress();

            $progress->modul_id = $modulId;
            $progress->upload_time = $UploadDate;
            $progress->file_dir = $filename;
            $progress->progress = "Perbaikan Untuk Laporan Error Tanggal " . $error['report_time'];
            $progress->status = 'Error Fix';
            $progress->save();

            if ($progress) {
                $error->status = 'selesai';
                $error->progress_id = $progress->progress_id;
                $error->save();
                if ($error) {
                    DB::commit();
                    return Redirect::back()->with('success', 'Laporan Error Berhasil Di Selesaikan!');
                } else {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Laporan Error Gagal Di Selesaikan!');
                }
            }
        }
    }

    public function loadRecomendedProject($tipeRecommend)
    {
        if ($tipeRecommend == 'Kategori') {
            $proyekCount = DB::table('modul_diambil')
                ->select('proyek_id', DB::raw('count(proyek_id) as total'))
                ->where('cust_id', Session::get('cust_id'))
                ->groupBy('proyek_id')
                ->orderBy('total', 'desc')
                ->first();
            $proyekCount = json_decode(json_encode($proyekCount), true);

            $proyekList = proyek::whereIn('proyek_id', $proyekCount)->get('kategorijob_id');
            $proyekList = json_decode(json_encode($proyekList), true);

            $recommendedProyek = proyek::whereIn('kategorijob_id', $proyekList)->get();
            $recommendedProyek = json_decode(json_encode($recommendedProyek), true);
            $listTag = tag::get();
            $listTag = json_decode(json_encode($listTag), true);

            $listKategori = kategori::get();
            $listKategori = json_decode(json_encode($listKategori), true);
            return view('RekomendasiProyek', [
                'recomendProyek' => $recommendedProyek,
                'listkategori' => $listKategori,
                'listtag' => $listTag,
                'tipeRekomen' => 'Kategori'
            ]);
        } else {
            $modulTaken = modulDiambil::where('cust_id', Session::get("cust_id"))->get('proyek_id');
            $modulTaken = json_decode(json_encode($modulTaken), true);

            $proyekCount = DB::table('tag')
                ->select('kategori_id', DB::raw('count(kategori_id) as total'))
                ->whereIn('proyek_id', $modulTaken)
                ->groupBy('kategori_id')
                ->orderBy('total', 'desc')
                ->first();
            $proyekCount = json_decode(json_encode($proyekCount), true);

            $tag = tag::where('kategori_id', $proyekCount['kategori_id'])->get('proyek_id');
            $tag = json_decode(json_encode($tag), true);

            $recommendedProyek = proyek::whereIn('proyek_id', $tag)->get();
            $recommendedProyek = json_decode(json_encode($recommendedProyek), true);

            $listTag = tag::get();
            $listTag = json_decode(json_encode($listTag), true);

            $listKategori = kategori::get();
            $listKategori = json_decode(json_encode($listKategori), true);

            return view('RekomendasiProyek', [
                'recomendProyek' => $recommendedProyek,
                'listkategori' => $listKategori,
                'listtag' => $listTag,
                'tipeRekomen' => 'Tag'
            ]);
        }
    }
}
