<?php

namespace App\Http\Controllers;

use App\Models\applicant;
use App\Models\customer;
use App\Models\error_report;
use App\Models\jobKategori;
use App\Models\kategori;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\notificationModel;
use App\Models\payment;
use App\Models\pembatalanFreelancer;
use App\Models\progress;
use App\Models\proyek;
use App\Models\review;
use App\Models\reviewClient;
use App\Models\tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

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
        $date = Carbon::now()->format('dmYHis');
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

        if (Carbon::parse($request->input("deadline"))->gt(Carbon::parse($request->input("tanggal_mulai")))) {
            Session::put('name_project', $request->input("name_project"));
            Session::put('desc_project', $request->input("desc_project"));
            Session::put('tipe_proyek', $request->input("tipe_proyek"));
            Session::put('kategorijob_project', $request->input("kategorijob_project"));
            Session::put('kategori_project', $request->input("kategori_project"));
            Session::put('deadline', $request->input("deadline"));
            Session::put('tanggal_mulai', $request->input("tanggal_mulai"));
            Session::put('kota', $request->input('kota'));
            $filename = "";

            if (!empty($request->file("dokumen"))) {
                $filename = "Dokumen" . str_replace(' ', '', $request->input("name_project"))."$date" . "." . $request->file("dokumen")->getClientOriginalExtension();
                $path = $request->file('dokumen')->storeAs("dokumen", $filename, 'public');
                if ($path != "" && $path != null) {
                    $image = $request->file('dokumen'); //image file from frontend
                    $firebase_storage_path = 'dokumen/';
                    $localfolder = public_path('firebase-temp-uploads') . '/';
                    if ($image->move($localfolder, $filename)) {
                        $uploadedfile = fopen($localfolder . $filename, 'r');
                        app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                        //will remove from local laravel folder
                        unlink($localfolder . $filename);
                    }
                }
            }
            Session::put('dokumen_name', $filename);
            $tag = kategori::get();
            $kategoriJob = jobKategori::get();
            return view('postModulProyek', [
                'tag' => $tag,
                'kategoriJob' => $kategoriJob
            ]);
        } else {
            return Redirect::back()->with("error", "Tanggal mulai tidak dapat melebihi tanggal deadline!");
        }
    }

    public function submitPostProject(Request $request)
    {
        $deadlineModulIsGreater = false;
        $modulArr = [];
        $modulMagangArr = [];
        $hid_val = $request->input('hid_val');
        $date = Carbon::now()->format("dmYHis");
        if (Session::get('tipe_proyek') == 'magang') {

            for ($i = 0; $i < (int)$hid_val; $i++) {
                if ($request->hasAny("nama_modul" . $i . "")) {
                    $filename = "";

                    if (!empty($request->file("dokumenModul" . $i . ""))) {
                        $filename = "DokumenModul" . str_replace(' ', '', $request->input("nama_modul" . $i . ""))."$date". "." . $request->file("dokumenModul" . $i . "")->getClientOriginalExtension();
                        $path = $request->file("dokumenModul" . $i . "")->storeAs("dokumenModul", $filename, 'public');
                        if ($path != "" && $path != null) {
                            $image = $request->file("dokumenModul" . $i . ""); //image file from frontend
                            $firebase_storage_path = 'dokumenModul/';
                            $localfolder = public_path('firebase-temp-uploads') . '/';
                            if ($image->move($localfolder, $filename)) {
                                $uploadedfile = fopen($localfolder . $filename, 'r');
                                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                                //will remove from local laravel folder
                                unlink($localfolder . $filename);
                            }
                        }
                    }
                    $modulTemp = array(
                        "nama_modul" => $request->input("nama_modul" . $i . ""),
                        "deskripsi_modul" => $request->input("desc_modul" . $i . ""),
                        "dokumen_modul" => $filename,
                        "bayaran1" => $request->input("rentang1_bayaran" . $i . ""),
                        "bayaran2" => $request->input("rentang2_bayaran" . $i . ""),
                        "deadline_modul" => $request->input("deadline_modul" . $i . ""),
                    );
                    array_push($modulMagangArr, $modulTemp);
                    if (Carbon::parse($request->input("deadline_modul" . $i . ""))->gt(Carbon::parse(Session::get('deadline')))) {
                        $deadlineModulIsGreater = true;
                    }
                }
            }
        } else {
            for ($i = 0; $i < (int)$hid_val; $i++) {
                if ($request->hasAny("nama_modul" . $i . "")) {
                    $filename = "";

                    if (!empty($request->file("dokumenModul" . $i . ""))) {
                        $filename = "DokumenModul" . str_replace(' ', '', $request->input("nama_modul" . $i . ""))."$date" . "." . $request->file("dokumenModul" . $i . "")->getClientOriginalExtension();
                        $path = $request->file("dokumenModul" . $i . "")->storeAs("dokumenModul", $filename, 'public');
                        if ($path != "" && $path != null) {
                            $image = $request->file("dokumenModul" . $i . ""); //image file from frontend
                            $firebase_storage_path = 'dokumenModul/';
                            $localfolder = public_path('firebase-temp-uploads') . '/';
                            if ($image->move($localfolder, $filename)) {
                                $uploadedfile = fopen($localfolder . $filename, 'r');
                                app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                                //will remove from local laravel folder
                                unlink($localfolder . $filename);
                            }
                        }
                    }
                    $modulTemp = array(
                        "nama_modul" => $request->input("nama_modul" . $i . ""),
                        "deskripsi_modul" => $request->input("desc_modul" . $i . ""),
                        "dokumen_modul" => $filename,
                        "bayaran" => $request->input("bayaran" . $i . ""),
                        "deadline_modul" => $request->input("deadline_modul" . $i . ""),
                    );
                    array_push($modulArr, $modulTemp);

                    if (Carbon::parse($request->input("deadline_modul" . $i . ""))->gt(Carbon::parse(Session::get('deadline')))) {
                        $deadlineModulIsGreater = true;
                    }
                }
            }
            //dd($modulArr);
        }
        if ($deadlineModulIsGreater) {
            return \redirect("/postproject")->with('error', 'Deadline modul tidak dapat melebihi deadline proyek');
            //return Redirect::back()->with('error','Deadline modul tidak dapat melebihi deadline proyek');
        } else {
            DB::beginTransaction();
            $postProject = new proyek();
            $postProject->cust_id = Session::get('cust_id');
            $postProject->nama_proyek = Session::get('name_project');
            $postProject->desc_proyek = Session::get('desc_project');
            $postProject->dokumentasi_proyek = Session::get('dokumen_name');
            $postProject->daerah_proyek = Session::get('kota');
            if (Session::get('tipe_proyek') == "magang") {
                $postProject->total_pembayaran = "0";
                $postProject->range_bayaran1 = $request->input('rentang_pembayaran1');
                $postProject->range_bayaran2 = $request->input('rentang_pembayaran2');
                $postProject->project_active = 'false';
            } else {
                $postProject->total_pembayaran = $request->input('total_pembayaran');
                $postProject->project_active = 'true';
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
                        $modul->dokumentasi = $itemArr['dokumen_modul'];
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
                        $modul->dokumentasi = $itemArr['dokumen_modul'];
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
                    if (Session::get('tipe_proyek') == "magang") {
                        return \redirect("/generatevaPostMagang/$id");
                    } else {
                        return \redirect("/postproject")->with('success', 'proyek anda telah berhasil di publikasikan');
                    }
                }
            }
        }
    }

    public function loadBrowseProject()
    {
        $listProject = proyek::where('start_proyek', ">=", Carbon::now())->where('project_active', 'true')->paginate(5);
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

    public function backSyncProject(){
        $listProject = proyek::where('start_proyek', ">=", Carbon::now())->where('project_active', 'true')->get();
        $listProject=json_decode(json_encode($listProject),true);


        return $listProject;
    }

    public function loadBrowseProjectClient()
    {
        $listProject = proyek::where('cust_id', Session::get('cust_id'))->paginate(5);
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
            'idCust' => Session::get('cust_id'),
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

        $proyekFilterKategori = [];

        $key=$request->input('searchProyek');

        $query = proyek::
        where('tipe_proyek', $request->tipe_proyek)->
        where('nama_proyek', 'LIKE', "%{$key}%")->
        where('start_proyek', ">=", Carbon::now())->
        where('project_active', 'true');

        if ($request->input('kategori_browse') != NULL) {

            $proyekFilterKategori = tag::whereIn('kategori_id', $request->kategori_browse)->get('proyek_id');
            $proyekFilterKategori = json_decode(json_encode($proyekFilterKategori), true);

            $query->whereIn('proyek_id', $proyekFilterKategori);
        }
        if ($request->input('kota') != null) {
            $query->where("daerah_proyek", $request->input("kota"));
        }

        $listProyek = $query->paginate(5)->appends($request->all());

        //$listProyek = json_decode(json_encode($listProyek), true);
        //dd($request->input('kota'));

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

        $clientName = customer::where('cust_id', $dataproyek['cust_id'])->first();

        $review = reviewClient::where('client_id', $dataproyek['cust_id'])->get();

        $totalBintang = 0;
        $rataRata = 0;
        $jumlahReview = 0;
        if (count($review) > 0) {
            $jumlahReview = count($review);
            foreach ($review as $bintang) {
                $totalBintang = $totalBintang + $bintang['bintang'];
            }

            $rataRata = $totalBintang / $jumlahReview;
            //dd($jumlahReview);
        }

        return view('detailproyek', [
            "dataproyek" => $dataproyek,
            "datamodul" => $datamodul,
            'datamodultaken' => $modulTaken,
            "avgBintang" => $rataRata,
            'clientNama' => $clientName
        ]);
    }

    public function loadProyekClient($id, $accessor)
    {
        $dataproyek = proyek::where('proyek_id', $id)->first();
        $dataproyek = json_decode(json_encode($dataproyek), true);

        $datamodul = modul::where('proyek_id', $id)->get();
        $datamodul = json_decode(json_encode($datamodul), true);

        $modulTaken = modulDiambil::where('proyek_id', $id)->where('status', '!=', 'dibatalkan')->get();
        $modulTaken = json_decode(json_encode($modulTaken), true);

        $payment = payment::get();
        $payment = json_decode(json_encode($payment), true);

        $query = "SELECT modul_id AS idModul, COUNT(applicant_id) AS pendaftar
        FROM applicant
        WHERE applicant.proyek_id = $id AND applicant.deleted_at IS NULL
        GROUP BY idModul

        UNION ALL

        SELECT modul.modul_id AS idModul, '0' AS pendaftar
        FROM modul
        WHERE modul.proyek_id = $id AND modul.modul_id NOT IN(SELECT applicant.modul_id FROM applicant WHERE applicant.deleted_at IS NULL)
        ";

        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);

        $modulDibatalkan = modulDiambil::where('proyek_id', $id)->where('status', 'dibatalkan')->distinct()->get('modul_id');
        $modulDibatalkan = json_decode(json_encode($modulDibatalkan), true);
        //dd($modulTaken);
        return view('detailProyekClient', [
            "dataproyek" => $dataproyek,
            "datamodul" => $datamodul,
            'modulDiambil' => $modulTaken,
            'datapayment' => $payment,
            'accessor' => $accessor,
            'id' => Session::get("cust_id"),
            'dataApplicant' => $db,
            'modulDibatalkan' => $modulDibatalkan
        ]);
    }

    public function ajukancv(Request $request)
    {
        if ($request->input('checkambil') == null) {
            return Redirect::back()->with("error", 'Anda belum memilih modul!');
        } else {
            if (!empty($request->file("filecv"))) {
                DB::beginTransaction();
                //var_dump($request->input('checkambil'));
                $date = date('Y-m-d_H_i_s');
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

                        $modul = modul::where("modul_id", $diambil)->first();
                        $proyek = proyek::where("proyek_id", $modul->proyek_id)->first();


                        $newNotif = new notificationModel();
                        $newNotif->customer_id = $proyek->cust_id;
                        $newNotif->message = session::get("name") . " telah mendaftar untuk modul " . $modul->title;
                        $newNotif->status = "S";
                        $newNotif->save();
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
    }

    public function loadApplicant($modulId, $proyekId, $idCust)
    {
        $applicantModul = applicant::where('proyek_id', $proyekId)->where('modul_id', $modulId)->get('cust_id');
        $applicantModul = json_decode(json_encode($applicantModul), true);

        $customerList = customer::whereIn('cust_id', $applicantModul)->get();
        $customerList = json_decode(json_encode($customerList), true);

        $applicantModul = applicant::where('proyek_id', $proyekId)->where('modul_id', $modulId)->get();
        $applicantModul = json_decode(json_encode($applicantModul), true);

        $judulModul = modul::where('modul_id', $modulId)->first();

        //var_dump($customerList);

        return view(
            'applicant',
            [
                "applicantList" => $customerList,
                "cv" => $applicantModul,
                "modulId" => $modulId,
                "proyekId" => $proyekId,
                'idCust' => $idCust,
                'judul' => $judulModul
            ]
        );
    }

    public function terimaApplicant($custId, $modulId, $proyekId, $applicantId)
    {
        $dateTime = date('Y-m-d');
        $date= Carbon::now()->format('dmYHis');
        $nama = customer::where('cust_id', $custId)->first();
        $modul = modul::where('modul_id', $modulId)->first();
        $nama_kontrak = 'Modul ' . $modul->title . $date .'.pdf';
        DB::beginTransaction();
        $insertTakenModul = new modulDiambil();
        $insertTakenModul->cust_id = $custId;
        $insertTakenModul->proyek_id = $proyekId;
        $insertTakenModul->modul_id = $modulId;
        $insertTakenModul->kontrak_kerja = $nama_kontrak;
        $insertTakenModul->urlkontrak = str_replace(' ', '%20', $nama_kontrak);
        $insertTakenModul->status = 'pengerjaan';
        $insertTakenModul->save();

        if ($insertTakenModul) {
            $delApplicant = applicant::find($applicantId);
            $delApplicant->status = 'diterima';
            $delApplicant->save();
            if ($delApplicant) {
                $newNotif = new notificationModel();
                $newNotif->customer_id = $custId;
                $newNotif->message = "CV anda untuk modul " . $modul->title . " telah di terima, Silahkan melakukan tanda tangan pada kontrak untuk menjamin keamanan.";
                $newNotif->status = "S";
                $newNotif->save();

                $newNotifClient = new notificationModel();
                $newNotifClient->customer_id = Session::get("cust_id");
                $newNotifClient->message = "Anda berhasil menerima " . $nama->nama . " sebagai freelancer, Silahkan melakukan tanda tangan pada kontrak untuk menjamin keamanan.";
                $newNotifClient->status = "S";
                $newNotifClient->save();

                $delApplicant->delete();
                $upModul = modul::where('modul_id', $modulId)->first();
                $upModul->start = $dateTime;
                $upModul->status = 'taken';
                $upModul->save();


                if ($upModul && $newNotif) {
                    DB::commit();
                    Session::put('idProyek', $proyekId);
                    Session::put('idModulDiambil', $modulId);
                    return Redirect::to("/generatePDF/$custId/$nama_kontrak");
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with('error', 'Freelancer gagal di terima!');
            }
        }
    }

    public function loadListProyekFreelancer(Request $request, $mode)
    {
        $proyek = proyek::get();
        $modulDiambil = modulDiambil::where('cust_id', Session::get("cust_id"))->where('status', "!=", 'dibatalkan')->get('modul_id');
        $modulDiambil = json_decode(json_encode($modulDiambil), true);
        $selected = "";
        if ($mode == "sort") {
            if ($request->input("rbsort") == "tanggalDeadline") {
                $modulFreelancer = modul::whereIn('modul_id', $modulDiambil)->orderBy('end', 'ASC')->paginate(5);
            } else if ($request->input("rbsort") == "tanggalMulai") {
                $modulFreelancer = modul::whereIn('modul_id', $modulDiambil)->orderBy('start', 'ASC')->paginate(5);
            }
            $selected = $request->input("rbsort");
        } else if ($mode == "default") {
            $selected = "reset";
            $modulFreelancer = modul::whereIn('modul_id', $modulDiambil)->paginate(5);
        }

        // $modulFreelancer=json_decode(json_encode($modulFreelancer),true);
        $listProgress = array();
        foreach ($modulDiambil as $modul) {
            $progress = progress::where('modul_id', $modul['modul_id'])->orderBy('updated_at', 'DESC')->first();
            array_push($listProgress, $progress);
        }
        $listProgress = json_decode(json_encode($listProgress), true);
        //$modulFreelancer = json_decode(json_encode($modulFreelancer), true);
        //dd($listProgress);

        $listPayment = payment::whereIn("modul_id", $modulDiambil)->get('modul_id');
        $listPayment = json_decode(json_encode($listPayment), true);

        $listId = array();
        foreach ($listPayment as $pay) {
            array_push($listId, $pay['modul_id']);
        }
        //dd($listId);
        return view('listProyekFreelancer', [
            'listproyek' => $modulFreelancer,
            'custId' => Session::get("cust_id"),
            'listProgress' => $listProgress,
            'allproyek' => $proyek,
            'checkedRb' => $selected,
            "listPayment" => $listId
        ]);
    }

    public function loadDetailModulFreelancer($modulId, $custId)
    {
        $status = "";
        $tooltip = "";
        $dataModul = modul::where('modul_id', $modulId)->first();
        $dataModul = json_decode(json_encode($dataModul), true);

        $dataProyek = proyek::where('proyek_id', $dataModul['proyek_id'])->first();
        $dataProyek = json_decode(json_encode($dataProyek), true);
        //dd($dataModul);

        $kontrak = modulDiambil::where('modul_id', $modulId)->first();
        $kontrak = json_decode(json_encode($kontrak), true);

        $paymentStatus = payment::where('modul_id', $modulId)->first();
        // $paymentStatus = json_decode(json_encode($paymentStatus), true);
        // dd($paymentStatus);

        $progress = progress::where('modul_id', $modulId)->orderBy('progress_id', 'desc')->first();
        if ($paymentStatus == null) {
            $status = "Tidak ada Pembayaran";
            $tooltip = "Data pembayaran tidak ditemukan.";
        } else if ($paymentStatus->status == "Paid") {
            $status = "Pembayaran Selesai";
            $tooltip = "Pembayaran telah dilakukan oleh client & Proyek sedang diperiksa.";
        } else if ($paymentStatus->status == "unpaid") {
            $status = "Belum Dibayar";
            $tooltip = "Tagihan telah dibuat tetapi pembayaran belum dilakukan.";
        } else if ($paymentStatus->status == "closed") {
            $status = "Penutupan pembayaran diajukan";
            $tooltip = "Client telah mengajukan permohonan penutupan pembayaran kepada admin.";
        } else if ($paymentStatus->status == "Completed") {
            $status = "Pembayaran Ditutup";
            $tooltip = "Penutupan pembayaran disetujui dan pembayaran telah di teruskan kepada freelancer.";
        }

        return view('detailModulFreelancer', [
            'dataModul' => $dataModul,
            'dataProyek' => $dataProyek,
            'kontrak' => $kontrak,
            'custId' => $custId,
            'statusPay' => $status,
            'tooltip' => $tooltip,
            'progress' => $progress
        ]);
    }

    public function updateProgress(Request $request, $modulId, $tipeProyek)
    {
        $formValidate = $request->validate(
            [
                'progDesc' => 'required|max:1000'
            ],
            [
                'progDesc.required' => "Deskripsi Progress tidak dapat kosong",
                'progDesc.max' => 'Panjang maksimal nama modul adalah 1000 karakter!'
            ]
        );

        DB::beginTransaction();
        $filename = "";
        $status = '';
        $message = "";
        if ($request->has('cb')) {
            $status = 'finish';
            $message = " telah menyelesaikan proyek untuk modul ";
        } else {
            $status = 'progress';
            $message = " telah menyerahkan progress untuk modul ";
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
        $modul = modul::where('modul_id', $modulId)->first();
        $proyek = proyek::where("proyek_id", $modul->proyek_id)->first();
        $custname = customer::where("cust_id", $proyek->cust_id)->first();
        $updateProgress = new progress();
        $updateProgress->modul_id = $modulId;
        $updateProgress->upload_time = $UploadDate;
        $updateProgress->file_dir = $filename;
        $updateProgress->progress = $request->input('progDesc');
        $updateProgress->status = $status;
        $updateProgress->save();

        $newNotif = new notificationModel();
        $newNotif->customer_id = $custname->cust_id;
        $newNotif->message = Session::get("name") . $message . $modul->title;
        $newNotif->status = "S";
        $newNotif->save();
        if ($updateProgress) {
            if ($status == "finish") {

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

    public function permohonanPembatalanFreelancer(Request $request, $modulTakenId)
    {
        DB::beginTransaction();
        $pembatalan = new pembatalanFreelancer();
        $pembatalan->modultaken_id = $modulTakenId;
        $pembatalan->alasan = $request->input('alasan');
        $pembatalan->status = 'pend';
        $pembatalan->save();

        if ($pembatalan) {
            DB::commit();

            return redirect()->back()->with('success', 'Permohonan telah di ajukan!');
        } else {
            DB::rollback();

            return redirect()->back()->with('error', 'Permohonan gagal di ajukan!');
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
        $cust = customer::where("cust_id", $clientId)->first();
        $modul = modul::where("modul_id", $modulId)->first();
        $newNotif = new notificationModel();
        $newNotif->customer_id = $freelancerId;
        $newNotif->message = "Review telah di berikan kepada anda oleh " . $cust->nama . " untuk modul " . $modul->title;
        $newNotif->status = "S";
        $newNotif->save();

        if ($insertReview && $newNotif) {
            DB::commit();
            return Redirect::back()->with('success', 'Review Berhasil Diberikan!');
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Review Gagal Diberikan!');
        }
    }

    public function reportError(Request $request, $modulId, $freelancerId, $progressId)
    {
        DB::beginTransaction();
        $filename = "";
        $date = date('Y-m-d H:i:s');
        if (!empty($request->file("fileError"))) {

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
        }
        $progressDate = progress::find($progressId);



        $insertError = new error_report();
        $insertError->modul_id = $modulId;
        $insertError->freelancer_id = $freelancerId;
        $insertError->tanggal_progress = $progressDate->upload_time;
        $insertError->halaman_error = $request->input('errPage');
        $insertError->aksi = $request->input('errAct');
        $insertError->report_desc = $request->input('errDesc');
        $insertError->report_data = $filename;
        $insertError->report_time = $date;
        $insertError->save();

        $modul = modul::where("modul_id", $modulId)->first();

        $newNotif = new notificationModel();
        $newNotif->customer_id = $freelancerId;
        $newNotif->message = "Error telah di laporkan untuk modul " . $modul->title;
        $newNotif->status = "S";
        $newNotif->save();
        if ($insertError && $newNotif) {
            DB::commit();
            return Redirect::back()->with('success', 'Error Telah Berhasil Di Laporkan!');
        } else {
            DB::rollback();
            return Redirect::back()->with('success', 'Error Gagal Di Laporkan!');
        }
    }

    public function loadErrorReport($modulId, $progressId)
    {
        $freelancerId = modulDiambil::where('modul_id', $modulId)->where('status', '!=', 'dibatalkan')->first();
        $progressData = progress::where('progress_id', $progressId)->first();
        $namaModul = modul::find($modulId);
        return view('errorReportPage', [
            'modulId' => $modulId,
            'freelancerId' => $freelancerId->cust_id,
            'progressData' => $progressData,
            'modulTitle' => $namaModul->title
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
        $filename = '';
        if (!empty($request->file("fileSelesai"))) {

            $date = date('Y-m-dH_i_s');
            $filename = $modulId . $date . "." . $request->file("fileSelesai")->getClientOriginalExtension();
            $path = $request->file('fileSelesai')->storeAs("progress", $filename, 'public');
        }

        foreach ($request->input('checkError') as $errorSelesai) {
            DB::beginTransaction();
            $error = error_report::where('report_id', $errorSelesai)->first();

            $progress = new progress();

            $progress->modul_id = $modulId;
            $progress->upload_time = $UploadDate;
            $progress->file_dir = $filename;
            $progress->progress = "[Perbaikan Untuk Laporan Error Tanggal " . $error['report_time'] . "] " . $request->input("descPerbaikan");
            $progress->status = 'Error Fix';
            $progress->save();

            $modul = modul::where("modul_id", $modulId)->first();
            $proyek = proyek::where("proyek_id", $modul->proyek_id)->first();

            $newNotif = new notificationModel();
            $newNotif->customer_id = $proyek->cust_id;
            $newNotif->message = "Perbaikan laporan error tanggal " . $error['report_time'] . " untuk modul " . $modul->title . " telah di berikan";
            $newNotif->status = "S";
            $newNotif->save();

            if ($progress && $newNotif) {
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
        $proyekAmbil = modulDiambil::where("cust_id", Session::get('cust_id'))->count();
        if ($proyekAmbil > 0) {
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
                $listKategoriJob = jobKategori::get();
                $listKategoriJob = json_decode(json_encode($listKategoriJob), true);

                //dd($proyekList);
                $recommendedProyek = proyek::where('project_active', 'true')->where('start_proyek', '>=', Carbon::now())->whereIn('kategorijob_id', $proyekList)->get();
                $listTag = tag::get();
                $listTag = json_decode(json_encode($listTag), true);

                $listKategori = kategori::get();
                $listKategori = json_decode(json_encode($listKategori), true);
                //dd();
                return view('RekomendasiProyek', [
                    'recomendProyek' => $recommendedProyek,
                    'listkategori' => $listKategori,
                    'listkategoriJob' => $listKategoriJob,
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

                //dd($proyekCount);
                $recommendedProyek = proyek::where('project_active', 'true')->where('start_proyek', '>=', Carbon::now())->whereIn('proyek_id', $tag)->get();

                $listTag = tag::get();
                $listTag = json_decode(json_encode($listTag), true);
                $listKategoriJob = jobKategori::get();
                $listKategoriJob = json_decode(json_encode($listKategoriJob), true);
                $listKategori = kategori::get();
                $listKategori = json_decode(json_encode($listKategori), true);

                //dd($recommendedProyek);
                return view('RekomendasiProyek', [
                    'recomendProyek' => $recommendedProyek,
                    'listkategori' => $listKategori,
                    'listkategoriJob' => $listKategoriJob,
                    'listtag' => $listTag,
                    'tipeRekomen' => 'Tag'
                ]);
            }
        } else {
            return Redirect::back()->with('error', 'Oops, Kita belum bisa memberi rekomendasi dikarenakan anda belum pernah mengambil proyek sebelumnya');
        }
    }

    public function reviewClientPage($modulId, $proyekId, $freelancerId, $clientId)
    {
        $clientNama = customer::where('cust_id', $clientId)->first();
        $proyekNama = proyek::where('proyek_id', $proyekId)->first();
        $modulNama = modul::where('modul_id', $modulId)->first();
        return view('reviewPageClient', [
            'freelancerId' => $freelancerId,
            'clientId' => $clientId,
            'modulId' => $modulId,
            'proyekId' => $proyekId,
            'clientName' => $clientNama,
            'proyekName' => $proyekNama,
            'modulName' => $modulNama
        ]);
    }

    public function submitReviewClient(Request $request, $modulId, $proyekId, $freelancerId, $clientId)
    {
        $reviewClient = new reviewClient();
        $reviewClient->freelancer_id = $freelancerId;
        $reviewClient->client_id = $clientId;
        $reviewClient->modul_id = $modulId;
        $reviewClient->proyek_id = $proyekId;
        $reviewClient->review = $request->input('revDesc');
        $reviewClient->bintang = $request->input('rate');
        $reviewClient->save();

        $cust = customer::where("cust_id", $freelancerId)->first();
        $modul = modul::where("modul_id", $modulId)->first();
        $newNotif = new notificationModel();
        $newNotif->customer_id = $clientId;
        $newNotif->message = "Review telah di berikan kepada anda oleh " . $cust->nama . " untuk modul " . $modul->title;
        $newNotif->status = "S";
        $newNotif->save();

        if ($reviewClient && $newNotif) {
            DB::commit();
            return Redirect::back()->with('success', 'Review Berhasil Diberikan!');
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Review Gagal Diberikan!');
        }
    }

    public function loadModulPengerjaan()
    {
        //dari cust id get proyek id
        $proyek = proyek::where('cust_id', Session::get('cust_id'))->get('proyek_id');
        $proyek = json_decode(json_encode($proyek), true);
        $modulDiambil = modulDiambil::whereIn('proyek_id', $proyek)->where('status', 'pengerjaan')->get();
        $modulDiambil = json_decode(json_encode($modulDiambil), true);

        $listProgress = array();
        foreach ($modulDiambil as $modulAmbil) {
            $progress = progress::where('modul_id', $modulAmbil['modul_id'])->orderBy('updated_at', 'DESC')->first();
            array_push($listProgress, $progress);
        }

        $proyekCust = proyek::where('cust_id', Session::get('cust_id'))->get();

        $modul = modul::whereIn('proyek_id', $proyek)->get();

        // dd(
        //     $listProgress
        // );
        return view('modulPengerjaan', [
            'modulDiambil' => $modulDiambil,
            'listProgress' => $listProgress,
            'proyekCust' => $proyekCust,
            'modul' => $modul
        ]);
    }
    public function loadModulSelesai()
    {
        //dari cust id get proyek id
        $proyek = proyek::where('cust_id', Session::get('cust_id'))->get('proyek_id');
        $proyek = json_decode(json_encode($proyek), true);
        $modulDiambil = modulDiambil::whereIn('proyek_id', $proyek)->where('status', 'selesai')->get();
        $modulDiambil = json_decode(json_encode($modulDiambil), true);

        $listProgress = array();
        foreach ($modulDiambil as $modulAmbil) {
            $progress = progress::where('modul_id', $modulAmbil['modul_id'])->orderBy('updated_at', 'DESC')->first();
            array_push($listProgress, $progress);
        }

        $listPembayaran = array();
        foreach ($modulDiambil as $modulAmbil) {
            $dataPembayaran = payment::where('modul_id', $modulAmbil['modul_id'])->first();

            if ($dataPembayaran == null) {
                $arrData = [
                    "modul_id" => $modulAmbil['modul_id'],
                    'status' => 'Belum Terbayar'
                ];
                array_push($listPembayaran, $arrData);
            } else {
                if (!str_contains($dataPembayaran['external_id'], 'pm')) {
                    $arrData = [
                        'payment_id' => $dataPembayaran['payment_id'],
                        "modul_id" => $dataPembayaran['modul_id'],
                        'status' => $dataPembayaran['status']
                    ];
                    array_push($listPembayaran, $arrData);
                }
            }
        }

        $proyekCust = proyek::where('cust_id', Session::get('cust_id'))->get();

        $modul = modul::whereIn('proyek_id', $proyek)->get();

        // dd(
        //     $listPembayaran
        // );
        return view('modulSelesai', [
            'modulDiambil' => $modulDiambil,
            'listProgress' => $listProgress,
            'proyekCust' => $proyekCust,
            'modul' => $modul,
            'listPembayaran' => $listPembayaran
        ]);
    }

    public function nonAktifkanProyek($proyekId, $status)
    {

        DB::beginTransaction();
        $proyek = proyek::where("proyek_id", $proyekId)->first();
        $proyek->project_active = $status;
        $proyek->save();


        if ($proyek) {
            DB::commit();
            if ($status == "true") {
                $msg = "Proyek Berhasil Di Aktifkan";
            } else {
                $msg = "Proyek Berhasil Di Non-Aktifkan";
            }
            return redirect()->back()->with("success", $msg);
        } else {
            DB::rollBack();
            if ($status == "true") {
                $msg = "Proyek Gagal Di Aktifkan";
            } else {
                $msg = "Proyek Gagal Di Non-Aktifkan";
            }
            return redirect()->back()->with("error", $msg);
        }
    }
}
