<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\chatController;
use App\Http\Controllers\cvController;
use App\Http\Controllers\emailController;
use App\Http\Controllers\firebaseController;
use App\Http\Controllers\gCalendarController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\pdfController;
use App\Http\Controllers\profilController;
use App\Http\Controllers\projectController;
use App\Http\Controllers\registerController;
use App\Http\Controllers\sertifikatController;
use App\Http\Controllers\signContoller;
use App\Http\Controllers\xenditController;
use App\Http\Middleware\cekCLient;
use App\Http\Middleware\cekFreelancer;
use App\Http\Middleware\cekLogin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//========================================================================================================================
//Route Global
//========================================================================================================================


//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route tanpa perlu pengecekan middleware
Route::get("/", function(){
    return view("landing");
});

Route::get('/loginMember', function () {
    return view('login');
});
Route::get('/freelanceropsadminlogin', function () {
    return view('loginAdmin');
});
Route::get('/header', function(){
    return view('header');
});
Route::get('/loadKontrakPDF',function(){

    return view("kontrak", [
        "date"=>Carbon::now()->format("d-m-Y H:i"),
        "freelancer"=>"Enrico",
        "sign"=>"Maxmillan",
        "proyek"=>"proyek 1",
        "modul"=>"modul 1",
        "tanggalMulai"=>Carbon::now(),
        "deskripsi"=>"membuat web interaktif untuk landing page perusahaan",
        "deadline"=>Carbon::now(),
        "total_pembayaran"=>10000000
    ]);
});
Route::get('/create-symlink', function () {
    symlink(storage_path('/app/public'), public_path('storage'));
    echo "Symlink Created. Thanks";
});
Route::get('/offline', function () {
    return view('vendor/laravelpwa/offline');
});
Route::post('/loginops', [loginController::class, 'login']);
Route::get('/loginopsAdmin', [loginController::class, 'loginAdmin']);
Route::get('/register', [registerController::class, 'loadRegister']);

Route::get('/back', function () {
    return redirect()->back();
});

Route::post('/submitregister', [registerController::class, 'generateCode']);
Route::get('/registerUser', [registerController::class, 'registerUser']);
Route::get('/logout', [loginController::class, 'logout']);
Route::post('/submitregisterAdmin', [registerController::class, 'generateCode']);
Route::View('/registerAdmin', 'PendaftaranAdmin');
Route::post('/api/save-token', [chatController::class, 'testChat']);
Route::get('/testChatPage', [chatController::class, 'getTestChat']);
Route::post('/createChat', [chatController::class, 'createChat']);
Route::get('/setAppBadge', [loginController::class, 'setAppBadge']);

Route::get('/sendEmail/{mail}/{type}', [emailController::class, 'sendEmail']);
Route::post('/verify', [emailController::class, 'verify']);
Route::post('/verifyPassChange', [emailController::class, 'verifyPassChange']);

Route::view('/lupaPass', 'passChange');
Route::post('/submitPassChange', [registerController::class, 'submitPassChange']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//CEK STATUS LOGIN USER YANG INGIN AKSES HALAMAN LAIN SELAIN YANG DI ATAS
Route::middleware([cekLogin::class])->group(function () {

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route dengan pengecekan middleware
    Route::get('/loadNotif/{idCust}', [profilController::class, 'loadNotif']);
    Route::get('/clearNotif/{idCust}', [profilController::class, 'clearNotif']);
    Route::get('/cariproyek', [projectController::class, 'cariProyek']);
    Route::get('/browse', [projectController::class, 'loadBrowseProject']);
    Route::get('/backSyncProject', [projectController::class, 'backSyncProject']);
    Route::get('/autoRejectApplicant', [loginController::class, 'autoRejectApplicant']);
    Route::get('/batalFreelancerAdmin/{modulTakenId}/{pembatalanId}/{mode}',[adminController::class,'batalkanFreelancerAdmin']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Review
    Route::get('/review/{modulId}/{freelancerId}/{clientId}', [projectController::class, 'reviewPage']);
    Route::post('/submitReview/{freelancerId}/{clientId}/{modulId}', [projectController::class, 'submitReview']);
    Route::get('/reviewClient/{modulId}/{proyekId}/{freelancerId}/{clientId}', [projectController::class, 'reviewClientPage']);
    Route::post('/submitReviewClient/{modulId}/{proyekId}/{freelancerId}/{clientId}', [projectController::class, 'submitReviewClient']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    //========================================================================================================================
    //ROUTE CLIENT
    //========================================================================================================================
    Route::middleware([cekCLient::class])->group(function () {

        Route::get('/nonaktifkanProyek/{proyekId}/{status}', [projectController::class, 'nonAktifkanProyek']);
        //Dashboard client
        Route::get('/dashboardClient', [loginController::class, 'loadDashboardClient']);
        //-----------------------------------------------------------------------------
        Route::get('/modulPengerjaan', [projectController::class, 'loadModulPengerjaan']);
        Route::get('/modulSelesai', [projectController::class, 'loadModulSelesai']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Load Profil Applicant
        Route::get(
            '/loadProfilApplicant/{role}/{custId}/{applicantId}/{modulId}/{proyekId}',
            [profilController::class, 'loadProfilApplicant']
        );
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route permohonan penutupan modul
        Route::get('/closeModul/{paymentId}', [projectController::class, 'closeModul']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Histori Transaksi
        Route::get('/loadHistoriTransaksi/{filterStatus}', [profilController::class, 'loadHistoriTransaksi']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Error Report
        Route::get('/errorReport/{modulId}/{progressId}', [projectController::class, 'loadErrorReport']);
        Route::post('/fileError/{modulId}/{freelancerId}/{progressId}', [projectController::class, 'reportError']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Proyek Client load browse dan list proyek
        Route::get('/listprojectclient', [projectController::class, 'loadBrowseProjectClient']);
        Route::get('/loadDetailProyekClient/{id}/{accessor}', [projectController::class, 'loadProyekClient']);
        Route::get('/postproject', [projectController::class, 'loadPostProject']);
        Route::post('/postmodul', [projectController::class, 'loadPostModul']);
        Route::post('/submitpostproject', [projectController::class, 'submitPostProject']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Applicant Terima dan load
        Route::get('/terimaApplicant/{custId}/{modulId}/{proyekId}/{applicantId}', [projectController::class, 'terimaApplicant']);
        Route::get('/loadApplicantModul/{modulId}/{proyekId}/{idCust}', [projectController::class, 'loadApplicant']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Progress Client load, download
        Route::get('/loadProgress/{modulId}/{modulTakenId}', [projectController::class, 'loadProgress']);
        Route::get('/downloadProgress/{status}/{filename}', [projectController::class, 'downloadProgress']);
        Route::get('/batalFreelancer/{modulTakenId}', [projectController::class, 'batalkanFreelancer']);
        Route::get('/permohonanPembatalanFreelancer/{modulTakenId}', [projectController::class, 'permohonanPembatalanFreelancer']);
        Route::get('/batalFreelancer/{modulTakenId}', [projectController::class, 'batalkanFreelancer']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    });
    //========================================================================================================================
    //========================================================================================================================



    //========================================================================================================================
    //ROUTE UNTUK USER FREELANCER
    //========================================================================================================================
    Route::middleware([cekFreelancer::class])->group(function () {
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Dashboard Freelancer
        //Route::get('/dashboardfreelancer', [loginController::class,'loadDashboardFreelancer'])->middleware(['auth'])->name('dashboardfreelancer');
        Route::get('/dashboardfreelancer', [loginController::class, 'loadDashboardFreelancer']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Submit Request Penarikan Dana Freelancer
        Route::post('/submittarikdana', [adminController::class, 'subTarikDana']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Load Error dan download file error
        Route::get('/loadError/{modulId}', [projectController::class, 'loadLaporanError']);
        Route::get('/downloadFileError/{reportData}', [projectController::class, 'downloadFileError']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Update Progress Freelancer
        Route::post('/updateProgress/{modulId}/{tipeProyek}', [projectController::class, 'updateProgress']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Histori Proyek
        Route::get('/loadHistoriProyek/{custId}', [profilController::class, 'loadHistoriProyek']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route penyelesaian error
        Route::post('/errorSubmit/{modulId}', [projectController::class, 'selesaikanError']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route penarikan saldo freelancer
        Route::get('/loadRequestTarik', [xenditController::class, 'loadRequestTarik']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Proyek freelancer submit, cari, post, browse, load
        Route::get('/detailproyek', function () {
            return view('detailproyek');
        });
        Route::get('/loadProyek/{id}/{custId}', [projectController::class, 'loadProyek']);
        Route::get('/listProyekFreelancer/{mode}', [projectController::class, 'loadListProyekFreelancer']);
        Route::get('/loadDetailModulFreelancer/{modulId}/{custId}', [projectController::class, 'loadDetailModulFreelancer']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route load browse project recommended
        Route::get('/loadRecomend/{tipeRecommend}', [projectController::class, 'loadRecomendedProject']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route histori
        Route::get('/histori/{isFiltered}', [profilController::class, 'loadHistori']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route Tambah nomor rekening Freelancer
        Route::get('/loadTambahRekening', [xenditController::class, 'loadTambahRekening']);
        Route::post('/tambahRekening', [profilController::class, 'tambahRekening']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //Route CV Load, pengajuan, upload, dan preview
        Route::get('/previewcv/{direktori}', [cvController::class, 'previewCV']);
        Route::post('/ajukancv', [projectController::class, 'ajukancv']);
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    });



    //========================================================================================================================
    //ROUTE GLOBAL BISA FREELANCER / CLIENT
    //========================================================================================================================

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Sertifikat Load, pengajuan, upload, dan preview
    Route::view('/loadUploadSertifikat', 'formSertifikat');
    Route::post('/insertSertifikat', [sertifikatController::class, 'insertSertifikat']);
    Route::get('/downloadSertifikat/{direktori}', [sertifikatController::class, 'downloadSertifikat']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route PDF
    Route::get('/generatePDF/{freelanceId}/{namaKontrak}', [pdfController::class, 'generatePDF']);
    Route::get('/downloadKontrak/{kontrakKerja}', [pdfController::class, 'downloadKontrak']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route E-sign Create, upload
    Route::get('/esign/{idModultaken}', [signContoller::class, 'loadESign']);
    Route::post('/uploadsign/{idModultaken}', [signContoller::class, 'uploadSign']);
    Route::get('/reUploadPDF/{idModultaken}', [pdfController::class, 'reUploadPDF']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route List Kontrak
    Route::get('/listKontrak/{statusKontrak}', [pdfController::class, 'loadListKontrak']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route untuk Chatting
    Route::get('/loadChatroom', [chatController::class, 'loadChatroom']);
    Route::get('/loadChatbox/{roomId}', [chatController::class, 'loadChatbox']);
    Route::get('/loadKirimPesan', [chatController::class, 'loadKirimPesan']);
    Route::post('/kirimPesan', [chatController::class, 'sendMessage']);
    Route::post('/submitChat/{roomId}', [chatController::class, 'sendChat']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Load Review Freelancer
    Route::get('/loadReview/{cust_id}', [profilController::class, 'loadReview']);
    Route::get('/loadReviewClient/{cust_id}', [profilController::class, 'loadReviewClient']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    Route::get('/loadProfil/{role}/{custId}', [profilController::class, 'loadProfil']);
    Route::get('/loadProfilKontrak/{role}/{custId}', [profilController::class, 'loadProfilKontrak']);
    Route::get('/loadEditProfil/{custId}', [profilController::class, 'loadEditProfil']);
    Route::post('/submiteditprofil/{custId}', [profilController::class, 'editProfil']);

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Calendar load calendar
    Route::get('/cekEvent', [gCalendarController::class, 'testGetEventCalendar']);
    Route::post('/insertEvent', [gCalendarController::class, 'insertEvent']);
    Route::get('/loadEditCalendar', [gCalendarController::class, 'loadEditCalendar']);
    Route::get('/tutorialCalendar/{tutorType}/{page}', function ($tutorType, $page) {
        $Ttype = $tutorType;
        $pg = $page;
        return view('tutorialCalendar', [
            'tutorType' => (string)$Ttype,
            'page' => (string)$pg
        ]);
    });
    Route::view('/calendarId', 'calendarId');
    Route::post('/updateCalendarId', [gCalendarController::class, 'changeENV']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //========================================================================================================================
    //========================================================================================================================


    //========================================================================================================================
    //ROUTE PEMBAYARAN
    //========================================================================================================================
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Xendit Cek Balance, generate VA, callback VA, simulate payment, load payment
    Route::get('/loadPembayaran/{modulId}', [xenditController::class, 'loadPembayaran']);
    Route::get('/loadPembayaranMagang/{modulId}', [xenditController::class, 'loadPembayaranMagang']);
    Route::get('/loadPembayaranPostMagang/{proyekId}', [xenditController::class, 'loadPembayaranPostMagang']);
    Route::get('/balance', [xenditController::class, 'getBalance']);
    Route::get('/generateva/{modulId}', [xenditController::class, 'createVA']);
    Route::get('/generatevaPostMagang/{proyekId}', [xenditController::class, 'createVAMagang']);
    Route::get('/callbackva/{externalId}', [xenditController::class, 'callbackVA']);
    Route::get('/simulatepayment/{externalId}', [xenditController::class, 'simulatePayment']);
    Route::get('/simulatepaymentPostMagang/{externalId}', [xenditController::class, 'simulatePaymentPostMagang']);
    //Route::get('/createInvoice/{modulId}',[xenditController::class,'createInvoice']);
    Route::get('/autoClosePayment', [xenditController::class, 'autoClosePayment']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //========================================================================================================================
    //========================================================================================================================


    //========================================================================================================================
    //ROUTE ADMIN
    //========================================================================================================================

    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Dashboard Admin
    Route::get('/adminDashboard', [adminController::class, 'loadAdminDashboard']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Membuat disbursement untuk penarikan dana
    Route::post('/createDisb/{custName}/{penarikanId}', [xenditController::class, 'createDisburse']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Submit Request penarikan dana & Load halaman request penarikan dana
    Route::get('/penarikanDanaCustomer', [adminController::class, 'loadPenarikanDana']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    Route::get('/loadPembatalanFreelancer', [adminController::class, 'loadPembatalanFreelancer']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route load halaman modul di tutup
    Route::get('/loadClosePayment', [adminController::class, 'loadClosedModul']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //+++++++++++++++++++++++++++++++++++++,+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route meneruskan pembayaran ke freelancer
    Route::get('/teruskanPembayaran/{paymentId}', [adminController::class, 'teruskanPembayaran']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Tambah nomor rekening Freelancer
    Route::get('/loadTambahRekening', [xenditController::class, 'loadTambahRekening']);
    Route::post('/tambahRekening', [profilController::class, 'tambahRekening']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    Route::post('/tambahTag', [adminController::class, 'tambahTag']);
    Route::post('/tambahKategori', [adminController::class, 'tambahKategori']);
    Route::view('/openTag', 'tambahTag');
    Route::view('/openKategori', 'tambahKategori');
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //Route Laporan Admin
    Route::get('/loadLaporanPendapatan', [adminController::class, 'loadLaporanPendapatan']);
    Route::get('/loadLaporanBulanAktif', [adminController::class, 'loadLaporanBulanAktif']);
    Route::get('/loadProyekBerhasil', [adminController::class, 'loadProyekBerhasil']);
    Route::get('/loadFreelancerClientAktif', [adminController::class, 'freelancerAktif']);
    Route::get('/nonAktifkanAkun/{emailFreelancer}', [adminController::class, 'nonAktifkanAkun']);
    Route::get('/aktifkanAkun/{emailFreelancer}', [adminController::class, 'aktifkanAkun']);
    Route::get('/loadLaporanProyekTidakBayar', [adminController::class, 'proyekTidakTerbayar']);
    Route::get('/chartProyekTidakBayar', [adminController::class, 'chartProyekTidakBayar']);
    Route::get('/laporanFreelancer', [adminController::class, 'laporanFreelancer']);
    Route::get('/laporanClient', [adminController::class, 'laporanClient']);
    Route::get('/ketepatanPembayaran', [adminController::class, 'ketepatanPembayaran']);
    Route::get('/listProyekBulan/{months}', [adminController::class, 'listProyekBulan']);
    Route::get('/loadLaporanBelumBayar/{status}', [adminController::class, 'loadLaporanBelumBayar']);
    Route::get('/detailLaporanFreelancerAktif/{custId}', [adminController::class, 'detailLaporanFreelancerAktif']);
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    Route::get('/laporanProyekAdmin/{status}', [adminController::class, 'laporanProyekAdmin']);
    Route::get('/loadPenarikanDanaAdmin', [xenditController::class, 'loadPenarikanDanaAdmin']);
    Route::get('/historiSaldoAdmin', [adminController::class, 'historiSaldoAdmin']);
    Route::post('/penarikanDanaAdmin', [xenditController::class, 'penarikanDanaAdmin']);
    Route::post('/filterLaporanAdmin/{status}', [adminController::class, 'filterLaporanAdmin']);

    //========================================================================================================================
    //========================================================================================================================



    //========================================================================================================================
    //ROUTE FIREBASE
    //========================================================================================================================
    Route::get('/firebaseIndex', [firebaseController::class, 'index']);
    Route::get('/firebaseImg', function () {
        return view('testview');
    });
    Route::POST('/uploadImg', [firebaseController::class, 'store']);
    Route::get('/info', [firebaseController::class, 'info']);
    //========================================================================================================================
    //========================================================================================================================

});

// Route::get('/', function () {
//     return view('welcome');
// });



require __DIR__ . '/auth.php';
