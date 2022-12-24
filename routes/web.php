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
use App\Models\profil;
use Google\Service\MyBusinessAccountManagement\Admin;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
Route::get('/', function () {
    return view('login');
});
Route::get('/loginops',[loginController::class,'login']);
Route::get('/register', [registerController::class,'loadRegister']);
Route::post('/submitregister',[registerController::class,'generateCode']);
Route::get('/sendEmail/{mail}/{type}',[emailController::class,'sendEmail']);
Route::post('/verify',[emailController::class,'verify']);
Route::get('/registerUser',[registerController::class,'registerUser']);
Route::get('/logout',[loginController::class,'logout']);
Route::post('/submitregisterAdmin',[registerController::class,'generateCode']);
Route::View('/registerAdmin','PendaftaranAdmin');
Route::post('/api/save-token',[chatController::class,'testChat']);
Route::get('/testChatPage',[chatController::class,'getTestChat']);
Route::post('/createChat',[chatController::class,'createChat']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//CEK STATUS LOGIN USER YANG INGIN AKSES HALAMAN LAIN SELAIN YANG DI ATAS
Route::middleware([cekLogin::class])->group(function(){

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route dengan pengecekan middleware

Route::get('/cariproyek',[projectController::class,'cariProyek']);
Route::get('/browse',[projectController::class,'loadBrowseProject']);
Route::get('/autoRejectApplicant',[loginController::class,'autoRejectApplicant']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//========================================================================================================================
//ROUTE UNTUK USER CLIENT
//========================================================================================================================
Route::middleware([cekCLient::class])->group(function(){

//Dashboard client
Route::get('/dashboardClient',[loginController::class,'loadDashboardClient']);
//-----------------------------------------------------------------------------

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Load Profil Applicant
Route::get('/loadProfilApplicant/{role}/{custId}/{applicantId}/{modulId}/{proyekId}',
[profilController::class,'loadProfilApplicant']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route permohonan penutupan modul
Route::get('/closeModul/{paymentId}',[projectController::class,'closeModul']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Histori Transaksi
Route::get('/loadHistoriTransaksi',[profilController::class,'loadHistoriTransaksi']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Error Report
Route::get('/errorReport/{modulId}/{progressId}',[projectController::class,'loadErrorReport']);
Route::post('/fileError/{modulId}/{freelancerId}/{progressId}',[projectController::class,'reportError']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Review
Route::get('/review/{modulId}/{freelancerId}/{clientId}',[projectController::class,'reviewPage']);
Route::post('/submitReview/{freelancerId}/{clientId}/{modulId}',[projectController::class,'submitReview']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Proyek Client load browse dan list proyek
Route::get('/listprojectclient',[projectController::class,'loadBrowseProjectClient']);
Route::get('/loadDetailProyekClient/{id}/{accessor}',[projectController::class,'loadProyekClient']);
Route::get('/postproject',[projectController::class,'loadPostProject']);
Route::post('/postmodul',[projectController::class,'loadPostModul']);
Route::post('/submitpostproject',[projectController::class,'submitPostProject']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Applicant Terima dan load
Route::get('/terimaApplicant/{custId}/{modulId}/{proyekId}/{applicantId}',[projectController::class,'terimaApplicant']);
Route::get('/loadApplicantModul/{modulId}/{proyekId}/{idCust}',[projectController::class,'loadApplicant']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Progress Client load, download
Route::get('/loadProgress/{modulId}/{modulTakenId}',[projectController::class,'loadProgress']);
Route::get('/downloadProgress/{status}/{filename}',[projectController::class,'downloadProgress']);
Route::get('/batalFreelancer/{modulTakenId}',[projectController::class,'batalkanFreelancer']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
});
//========================================================================================================================
//========================================================================================================================



//========================================================================================================================
//ROUTE UNTUK USER FREELANCER
//========================================================================================================================
Route::middleware([cekFreelancer::class])->group(function(){
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Dashboard Freelancer
//Route::get('/dashboardfreelancer', [loginController::class,'loadDashboardFreelancer'])->middleware(['auth'])->name('dashboardfreelancer');
Route::get('/dashboardfreelancer',[loginController::class,'loadDashboardFreelancer']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Submit Request Penarikan Dana Freelancer
Route::post('/submittarikdana',[adminController::class,'subTarikDana']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Load Error dan download file error
Route::get('/loadError/{modulId}',[projectController::class,'loadLaporanError']);
Route::get('/downloadFileError/{reportData}',[projectController::class,'downloadFileError']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Update Progress Freelancer
Route::post('/updateProgress/{modulId}/{tipeProyek}',[projectController::class,'updateProgress']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Histori Proyek
Route::get('/loadHistoriProyek/{custId}',[profilController::class,'loadHistoriProyek']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route penyelesaian error
Route::post('/errorSubmit/{modulId}',[projectController::class,'selesaikanError']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route penarikan saldo freelancer
Route::get('/loadRequestTarik',[xenditController::class,'loadRequestTarik']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Proyek freelancer submit, cari, post, browse, load
Route::get('/detailproyek',function(){
    return view('detailproyek');
});
Route::get('/loadProyek/{id}/{custId}',[projectController::class,'loadProyek']);
Route::get('/listProyekFreelancer/{custId}',[projectController::class,'loadListProyekFreelancer']);
Route::get('/loadDetailModulFreelancer/{modulId}/{custId}',[projectController::class,'loadDetailModulFreelancer']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route load browse project recommended
Route::get('/loadRecomend/{tipeRecommend}',[projectController::class,'loadRecomendedProject']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route histori
Route::get('/histori',[profilController::class,'loadHistori']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Tambah nomor rekening Freelancer
Route::get('/loadTambahRekening',[xenditController::class,'loadTambahRekening']);
Route::post('/tambahRekening',[profilController::class,'tambahRekening']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route CV Load, pengajuan, upload, dan preview
Route::get('/previewcv/{direktori}',[cvController::class,'previewCV']);
Route::post('/ajukancv',[projectController::class,'ajukancv']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
});



//========================================================================================================================
//ROUTE GLOBAL BISA FREELANCER / CLIENT
//========================================================================================================================

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Sertifikat Load, pengajuan, upload, dan preview
Route::view('/loadUploadSertifikat','formSertifikat');
Route::post('/insertSertifikat',[sertifikatController::class,'insertSertifikat']);
Route::get('/downloadSertifikat/{direktori}',[sertifikatController::class,'downloadSertifikat']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route PDF
Route::get('/generatePDF/{freelanceId}/{namaKontrak}',[pdfController::class,'generatePDF']);
Route::get('/downloadKontrak/{kontrakKerja}',[pdfController::class,'downloadKontrak']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route E-sign Create, upload
Route::get('/esign/{idModultaken}',[signContoller::class,'loadESign']);
Route::post('/uploadsign/{idModultaken}',[signContoller::class,'uploadSign']);
Route::get('/reUploadPDF/{idModultaken}',[pdfController::class,'reUploadPDF']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route List Kontrak
Route::get('/listKontrak/{statusKontrak}',[pdfController::class,'loadListKontrak']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route untuk Chatting
Route::get('/loadChatroom',[chatController::class,'loadChatroom']);
Route::get('/loadChatbox/{roomId}',[chatController::class,'loadChatbox']);
Route::get('/loadKirimPesan',[chatController::class,'loadKirimPesan']);
Route::post('/kirimPesan',[chatController::class,'sendMessage']);
Route::post('/submitChat/{roomId}',[chatController::class,'sendChat']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Load Review Freelancer
Route::get('/loadReview/{cust_id}',[profilController::class,'loadReview']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Route::get('/loadProfil/{role}/{custId}',[profilController::class,'loadProfil']);
Route::get('/loadEditProfil/{custId}',[profilController::class,'loadEditProfil']);
Route::post('/submiteditprofil/{custId}',[profilController::class,'editProfil']);

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Calendar load calendar
Route::get('/cekEvent',[gCalendarController::class,'testGetEventCalendar']);
Route::post('/insertEvent',[gCalendarController::class,'insertEvent']);
Route::get('/loadEditCalendar',[gCalendarController::class,'loadEditCalendar']);
Route::view('/tutorialCalendar','tutorialCalendar');
Route::view('/calendarId','calendarId');
Route::post('/updateCalendarId',[gCalendarController::class,'changeENV']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//========================================================================================================================
//========================================================================================================================


//========================================================================================================================
//ROUTE PEMBAYARAN
//========================================================================================================================
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Xendit Cek Balance, generate VA, callback VA, simulate payment, load payment
Route::get('/loadPembayaran/{modulId}',[xenditController::class,'loadPembayaran']);
Route::get('/loadPembayaranMagang/{modulId}',[xenditController::class,'loadPembayaranMagang']);
Route::get('/loadPembayaranPostMagang/{proyekId}',[xenditController::class,'loadPembayaranPostMagang']);
Route::get('/balance',[xenditController::class,'getBalance']);
Route::get('/generateva/{modulId}',[xenditController::class,'createVA']);
Route::get('/generatevaPostMagang/{proyekId}',[xenditController::class,'createVAMagang']);
Route::get('/callbackva/{externalId}',[xenditController::class,'callbackVA']);
Route::get('/simulatepayment/{externalId}',[xenditController::class,'simulatePayment']);
Route::get('/simulatepaymentPostMagang/{externalId}',[xenditController::class,'simulatePaymentPostMagang']);
//Route::get('/createInvoice/{modulId}',[xenditController::class,'createInvoice']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//========================================================================================================================
//========================================================================================================================


//========================================================================================================================
//ROUTE ADMIN
//========================================================================================================================

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Dashboard Admin
Route::get('/adminDashboard',[adminController::class,'loadAdminDashboard']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Membuat disbursement untuk penarikan dana
Route::post('/createDisb/{custName}/{penarikanId}',[xenditController::class,'createDisburse']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Submit Request penarikan dana & Load halaman request penarikan dana
Route::get('/penarikanDanaCustomer',[adminController::class,'loadPenarikanDana']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route load halaman modul di tutup
Route::get('/loadClosePayment',[adminController::class,'loadClosedModul']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//+++++++++++++++++++++++++++++++++++++,+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route meneruskan pembayaran ke freelancer
Route::get('/teruskanPembayaran/{paymentId}',[adminController::class,'teruskanPembayaran']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Tambah nomor rekening Freelancer
Route::get('/loadTambahRekening',[xenditController::class,'loadTambahRekening']);
Route::post('/tambahRekening',[profilController::class,'tambahRekening']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//Route Laporan Admin
Route::get('/loadLaporanPendapatan',[adminController::class,'loadLaporanPendapatan']);
Route::get('/loadLaporanBulanAktif',[adminController::class,'loadLaporanBulanAktif']);
Route::get('/loadProyekBerhasil',[adminController::class,'loadProyekBerhasil']);
Route::get('/loadFreelancerClientAktif/{custType}',[adminController::class,'freelancerAktif']);
Route::get('/loadLaporanProyekTidakBayar',[adminController::class,'proyekTidakTerbayar']);
Route::get('/chartProyekTidakBayar',[adminController::class,'chartProyekTidakBayar']);
Route::get('/laporanFreelancer',[adminController::class,'laporanFreelancer']);
Route::get('/laporanClient',[adminController::class,'laporanClient']);
Route::get('/ketepatanPembayaran',[adminController::class,'ketepatanPembayaran']);
Route::get('/listProyekBulan/{months}',[adminController::class,'listProyekBulan']);
Route::get('/loadLaporanBelumBayar/{status}',[adminController::class,'loadLaporanBelumBayar']);
Route::get('/detailLaporanFreelancerAktif/{custId}',[adminController::class,'detailLaporanFreelancerAktif']);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Route::get('/laporanProyekAdmin/{status}',[adminController::class,'laporanProyekAdmin']);
Route::get('/loadPenarikanDanaAdmin',[xenditController::class,'loadPenarikanDanaAdmin']);
Route::get('/historiSaldoAdmin',[adminController::class,'historiSaldoAdmin']);
Route::post('/penarikanDanaAdmin',[xenditController::class,'penarikanDanaAdmin']);
Route::post('/filterLaporanAdmin/{status}',[adminController::class,'filterLaporanAdmin']);

//========================================================================================================================
//========================================================================================================================



//========================================================================================================================
//ROUTE FIREBASE
//========================================================================================================================
Route::get('/firebaseIndex',[firebaseController::class,'index']);
Route::get('/firebaseImg',function () {
    return view('testview');
});
Route::POST('/uploadImg',[firebaseController::class,'store']);
Route::get('/info',[firebaseController::class,'info']);
//========================================================================================================================
//========================================================================================================================

});

// Route::get('/', function () {
//     return view('welcome');
// });



require __DIR__.'/auth.php';
