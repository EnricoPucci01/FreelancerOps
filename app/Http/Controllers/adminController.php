<?php

namespace App\Http\Controllers;

use App\Charts\chartControl;
use App\Models\customer;
use App\Models\jobKategori;
use App\Models\kategori;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\payment;
use App\Models\penarikan;
use App\Models\proyek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class adminController extends Controller
{
    public function loadAdminDashboard()
    {
        $pendapatanTotal = 0;

        $saldoAdmin = customer::where('cust_id', Session::get('adminCustId'))->first();

        $query = "SELECT customer.nama AS nama, penarikan.jumlah AS jumlah, penarikan.tanggal_request AS tanggal, penarikan.bank AS bank
            FROM customer,penarikan
            WHERE penarikan.tanggal_admit is NULL AND penarikan.cust_id=customer.cust_id ";
        $db = DB::select($query);

        return view('adminDashboard', [
            'total' => $saldoAdmin->saldo,
            'dataPenarikan' => $db
        ]);
    }

    public function subTarikDana(Request $request)
    {

        $formValidate = $request->validate([
            'no_rek' => 'required|numeric',
            'total_penarikan' => 'required|'
        ], [
            'no_rek.required' => 'Nomor Rekening tidak dapat kosong!',
            'total_penarikan.required' => 'Total Penarikan tidak dapat kosong!',
            'no_rek.numeric' => 'Nomor Rekening hanya boleh berisi angka!',
            'total_penarikan.numeric' => 'Total Penarikan hanya boleh berisi angka!',
        ]);


        $tanggalReq = date('Y-m-d H:i:s');

        $saldoPenarik = customer::where('cust_id',Session::get('cust_id'))->first();

        if((int)$saldoPenarik->saldo >= (int)str_replace(".","",$request->input('total_penarikan'))){
            DB::beginTransaction();

            $insertPenarikan = new penarikan();
            $insertPenarikan->cust_id = Session::get('cust_id');
            $insertPenarikan->no_rek = $request->input('no_rek');
            $insertPenarikan->bank = $request->input('bank');
            $insertPenarikan->jumlah = str_replace(".","",$request->input('total_penarikan'));
            $insertPenarikan->tanggal_request = $tanggalReq;
            $insertPenarikan->save();

            if ($insertPenarikan) {
                DB::commit();
                return Redirect::back()->with('success', 'Permohonan penarikan telah di buat!');
            } else {
                DB::rollback();
                return Redirect::back()->with('error', 'Permohonan penarikan gagal di buat!');
            }
        }else{
            DB::rollback();
            return Redirect::back()->with('error', 'Jumlah penarikan anda lebih besar dari saldo anda!');
        }

    }

    public function loadPenarikanDana()
    {
        $dataPenarikan = penarikan::where('cust_id', "!=", '0')->get();
        $dataPenarikan = json_decode(json_encode($dataPenarikan), true);

        $custId = penarikan::where('cust_id', "!=", '0')->get('cust_id');
        $custId = json_decode(json_encode($custId), true);

        $dataCust = customer::whereIn('cust_id', $custId)->get();
        $dataCust = json_decode(json_encode($dataCust), true);

        //dd($dataCust);
        return view('listRequestPenarikan', [
            'dataPenarikan' => $dataPenarikan,
            'dataCust' => $dataCust
        ]);
    }

    public function loadClosedModul()
    {
        $dataClosedPayment = payment::where('status', 'close')->get();
        $dataClosedPayment = json_decode(json_encode($dataClosedPayment), true);

        $dataModul = modul::get();
        $dataModul = json_decode(json_encode($dataModul), true);

        return view('closedPayment', [
            'dataClosedPayment' => $dataClosedPayment,
            'dataModul' => $dataModul
        ]);
    }

    public function teruskanPembayaran($paymentId)
    {
        DB::beginTransaction();
        $paymentData = payment::where('payment_id', $paymentId)->first();
        $paymentData->status = 'Completed';
        $paymentData->save();

        $updateSaldoCust = customer::where('cust_id', $paymentData->cust_id)->first();
        $updateSaldoCust->saldo = (int)$updateSaldoCust->saldo + (int)$paymentData->amount;
        $updateSaldoCust->save();

        $updateSaldoAdmin = customer::where('cust_id', Session::get('adminCustId'))->first();
        $updateSaldoAdmin->saldo = $updateSaldoAdmin->saldo + $paymentData->service_fee;
        $updateSaldoAdmin->save();

        if ($paymentData && $updateSaldoCust && $updateSaldoAdmin) {
            DB::commit();
            return Redirect::back()->with('success', 'Pembayaran Berhasil Di Teruskan Ke Freelancer!');
        } else {
            DB::rollback();
            return Redirect::back()->with('error', 'Pembayaran Gagal Di Teruskan Ke Freelancer!');
        }
    }

    public function loadLaporanPendapatan()
    {
    }

    public function loadLaporanBulanAktif(Request $request)
    {
        //dd($request);
        $kategoriJobId = ($request->has('ddKategori')) ? $request->input('ddKategori') : "1";
        $query = "SELECT MONTHNAME(modul_diambil.created_at) as Bulan, count(proyek.created_at) as counts
        FROM proyek,kategori_job,modul_diambil
        WHERE proyek.kategorijob_id = $kategoriJobId AND proyek.proyek_id= modul_diambil.proyek_id AND kategori_job.kategorijob_id = proyek.kategorijob_id
        GROUP BY bulan";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);
        $bulan = array();
        $count = array();
        foreach ($db as $Valnama) {
            array_push($bulan, $Valnama['Bulan']);
            array_push($count, $Valnama['counts']);
        }
        $judulKategori = jobKategori::find($kategoriJobId);
        $itemKategori = jobKategori::get();
        $chartBulan = new chartControl;
        $chartBulan->labels($bulan);
        $chartBulan->dataset('', 'bar', $count)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        $query = "SELECT MONTHNAME(modul_diambil.created_at) as months ,count(modul_diambil.modultaken_id) as counts
        FROM modul_diambil
        GROUP BY months
        ORDER BY counts DESC";
        $dbSortedMonth = DB::select($query);
        $dbSortedMonth = json_decode(json_encode($dbSortedMonth), true);


        $query = "SELECT kategori_job.judul_kategori as judul,count(proyek.created_at) as counts
        FROM proyek,kategori_job,modul_diambil
        WHERE proyek.kategorijob_id=kategori_job.kategorijob_id and proyek.proyek_id=modul_diambil.proyek_id
        GROUP BY kategori_job.judul_kategori
        ORDER BY counts DESC";
        $dbKategori = DB::select($query);
        $dbKategori = json_decode(json_encode($dbKategori), true);
        //dd($dbKategori);

        return view('laporanBulanAktif', [
            "chart2" => $chartBulan,
            "judul" => $judulKategori->judul_kategori,
            'rekap' => $dbSortedMonth,
            'rekapKategori' => $dbKategori,
            'itemKategori' => $itemKategori,
            'selected' => $kategoriJobId
        ]);
    }

    public function listProyekBulan($months)
    {
        $query = "SELECT modul.title as title , customer.nama as nama , modul_diambil.created_at as tanggal
        FROM modul_diambil, modul, customer
        WHERE  MONTHNAME(modul_diambil.created_at) = '$months' and modul_diambil.modul_id= modul.modul_id and modul_diambil.cust_id= customer.cust_id";
        $db = DB::select($query);
        $db = collect($db);
        $page = LengthAwarePaginator::resolveCurrentPage('page');
        $paginationData = new LengthAwarePaginator(
            $db->forPage($page, 5),
            $db->count(),
            5,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
        //dd($paginationData);
        Carbon::setLocale('id');
        return view('listProyekBulan', [
            "listproyek" => $paginationData,
            'month' => Carbon::parse($months)->translatedFormat('F')
        ]);
    }

    public function laporanFreelancer()
    {
        // $queryUmur = "SELECT FLOOR(DATEDIFF(CURDATE(),customer.tanggal_lahir)/365) AS umur, count(customer.cust_id) AS Jumlah
        //     FROM customer
        //     WHERE customer.role='freelancer'
        //     Group By umur";
        // $dbumur = DB::select($queryUmur);
        // $dbumur = json_decode(json_encode($dbumur), true);
        // $umur = array();
        // $jumlah = array();
        // foreach ($dbumur as $Valnama) {
        //     array_push($umur, $Valnama['umur']);
        //     array_push($jumlah, $Valnama['Jumlah']);
        // }
        $queryKebutuhan = "SELECT kategori.nama_kategori AS nama,Count(tag.kategori_id) AS jumlah
        FROM tag, kategori
        WHERE kategori.kategori_id = tag.kategori_id
        Group By nama";
        $dbKebutuhan = DB::select($queryKebutuhan);
        $dbKebutuhan = json_decode(json_encode($dbKebutuhan), true);
        //dd($dbKebutuhan);

        $querySpesialisasi = "SELECT skill.nama_skill AS nama, count(spesialisasi.spesialisasi_id) AS jumlah
            FROM skill, spesialisasi
            WHERE skill.skill_id=spesialisasi.skill_id
            Group By nama";
        $dbspesialisasi = DB::select($querySpesialisasi);
        $dbspesialisasi = json_decode(json_encode($dbspesialisasi), true);
        $skill = array();
        $count = array();
        foreach ($dbspesialisasi as $Valnama) {
            array_push($skill, $Valnama['nama']);
            array_push($count, $Valnama['jumlah']);
        }


        // $chart_options = [
        //     'chart_title' => 'Tingkat Edukasi',
        //     'report_type' => 'group_by_string',
        //     'model' => 'App\Models\customer',
        //     'group_by_field' => 'pendidikan',
        //     'aggregate_function' => 'count',
        //     'aggregate_field' => 'pendidikan',
        //     'chart_type' => 'bar',
        //     'chart_color' => '255, 0, 30, 1',
        //     'where_raw' => "role = 'freelancer'"
        // ];
        // $chart1 = new LaravelChart($chart_options);


        // $chartumur = new chartControl;
        // $chartumur->labels($umur);
        // $chartumur->dataset('Umur Customer', 'pie', $jumlah)->options(
        //     [
        //         'backgroundColor' => [
        //             "rgb(54, 162, 235)",
        //             'rgb(255, 99, 132)',
        //             'rgb(255, 205, 86)',
        //             'rgb(55, 212, 79)',
        //             'rgb(60, 66, 61)',
        //             'rgb(245, 118, 7)',
        //             'rgb(8, 69, 115)',
        //             'rgb(88, 8, 115)'
        //         ]
        //     ]
        // );

        $chartspesialisasi = new chartControl;
        $chartspesialisasi->labels($skill);
        $chartspesialisasi->dataset('Skill Freelancer', 'pie', $count)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        return view("laporanFreelancer", [
            'chart2' => $chartspesialisasi,
            'judul2' => 'Grafik Spesialisasi Freelancer',
            'dataFreelancer' => $dbspesialisasi,
            'dataKebutuhan' =>$dbKebutuhan
        ]);
    }

    public function laporanClient()
    {
        $queryUmur = "SELECT FLOOR(DATEDIFF(CURDATE(),customer.tanggal_lahir)/365) AS umur, count(customer.cust_id) AS Jumlah
            FROM customer
            WHERE customer.role='client'
            Group By umur";
        $dbumur = DB::select($queryUmur);
        $dbumur = json_decode(json_encode($dbumur), true);
        $umur = array();
        $jumlah = array();
        foreach ($dbumur as $Valnama) {
            array_push($umur, $Valnama['umur']);
            array_push($jumlah, $Valnama['Jumlah']);
        }


        $querypekerjaan = "SELECT profil.pekerjaan AS pekerjaan, count(profil.cust_id) AS Total
            FROM customer,profil
            WHERE customer.role='client' AND customer.cust_id=profil.cust_id
            Group By profil.pekerjaan";
        $dbpekerjaan = DB::select($querypekerjaan);
        $dbpekerjaan = json_decode(json_encode($dbpekerjaan), true);
        $pekerjaan = array();
        $total = array();
        foreach ($dbpekerjaan as $Valnama) {
            array_push($pekerjaan, $Valnama['pekerjaan']);
            array_push($total, $Valnama['Total']);
        }

        $chartumur = new chartControl;
        $chartumur->labels($umur);
        $chartumur->dataset('Umur Customer', 'pie', $jumlah)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        $chartpekerjaan = new chartControl;
        $chartpekerjaan->labels($pekerjaan);
        $chartpekerjaan->dataset('Pekerjaan Client', 'pie', $total)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        return view("chart", [
            'chart' => $chartumur,
            'judul' => 'Grafik Umur Client',
            'chart1' => '0',
            'chart2' => $chartpekerjaan,
            'judul2' => 'Grafik Pekerjaan Client'
        ]);
    }

    public function ketepatanPembayaran()
    {
        $queryPembayaranTepat = "SELECT count(payment.payment_id) AS jumlah
            FROM payment
            WHERE payment.payment_time <= ADDDATE(payment.created_at, INTERVAL 7 day) AND
            (payment.status='Completed' OR payment.status='Paid')";
        $dbpembayaranTepat = DB::select($queryPembayaranTepat);
        $dbpembayaranTepat = json_decode(json_encode($dbpembayaranTepat), true);

        $queryPembayaranTerlambat = "SELECT count(payment.payment_id) AS jumlah
            FROM payment
            WHERE payment.payment_time >= ADDDATE(payment.created_at, INTERVAL 7 day) AND
            (payment.status='Completed' OR payment.status='Paid')";
        $dbpembayaranTerlambat = DB::select($queryPembayaranTerlambat);
        $dbpembayaranTerlambat = json_decode(json_encode($dbpembayaranTerlambat), true);

        $hasil = array();
        $hasil[0] = $dbpembayaranTepat[0]['jumlah'];
        $hasil[1] = $dbpembayaranTerlambat[0]['jumlah'];
        //dd($hasil);

        $chartPembayaran = new chartControl;
        $chartPembayaran->labels(['Tepat', 'Terlambat']);
        $chartPembayaran->dataset('Ketepatan Pembayaran', 'pie', $hasil)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        return view("chart", [
            'chart' => $chartPembayaran,
            'judul' => 'Grafik Ketepatan Pembayaran Proyek',
            'chart1' => '0',
            'chart2' => '0',
            'judul2' => ''
        ]);
    }

    public function proyekTidakTerbayar(Request $request)
    {
        if ($request->input('ddPeriode') == "Tahun" || $request->input('ddPeriode') == null) {
            $payment = payment::where('status', 'Completed')->orWhere('status', 'Paid')->paginate(10);
            $total = 0;
            $paymentitem = payment::where('status', 'Completed')->orWhere('status', 'Paid')->get();
            foreach ($paymentitem as $itemPay) {
                $total = $total + (int)$itemPay->service_fee;
            }
        } else {
            $payment = payment::where(DB::raw('MONTHNAME(created_at)'), $request->input('ddPeriode'))->where(function ($q) {
                $q->where('status', "Completed")
                    ->orWhere('status', "Paid");
            })->paginate(10);
            $total = 0;
            $paymentitem = payment::where(DB::raw('MONTHNAME(created_at)'), $request->input('ddPeriode'))->where(function ($q) {
                $q->where('status', "Completed")
                    ->orWhere('status', "Paid");
            })->get();
            foreach ($paymentitem as $itemPay) {
                $total = $total + (int)$itemPay->service_fee;
            }
        }

        //dd($paymentitem);
        return view('laporanProyekTidakBayar', [
            'dataPayment' => $payment,
            'totalSaldo' => $total,
            'bulan' => $request->input('ddPeriode')
        ]);
    }

    public function chartProyekTidakBayar()
    {
        // Grafik Pendapatan perBulan
        $query = "SELECT MONTHNAME(created_at) as bulan, SUM(service_fee) as total
        FROM payment
        WHERE payment.status='Completed' OR payment.status = 'Paid'
        GROUP BY MONTHNAME(created_at)
        ORDER BY total DESC";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);

        //dd($db);

        $bulan = array();
        $total = array();
        foreach ($db as $Valnama) {
            array_push($bulan, $Valnama['bulan']);
            array_push($total, $Valnama['total']);
        }


        $chartPendapatan = new chartControl;
        $chartPendapatan->labels($bulan);
        $chartPendapatan->dataset('', 'line', $total);
        // ->options(
        //     [
        //         'backgroundColor' => [
        //             "rgb(54, 162, 235)",
        //             'rgb(255, 99, 132)',
        //             'rgb(255, 205, 86)',
        //             'rgb(55, 212, 79)',
        //             'rgb(60, 66, 61)',
        //             'rgb(245, 118, 7)',
        //             'rgb(8, 69, 115)',
        //             'rgb(88, 8, 115)'
        //         ]
        //     ]
        // );

        $queryChart1 = "SELECT payment.status as statusPay,Count(payment_id) as jumlah
        FROM payment
        WHERE payment.deleted_at IS NULL
        GROUP BY statusPay
        ORDER BY jumlah DESC";
        $dbChart1 = DB::select($queryChart1);
        $dbChart1 = json_decode(json_encode($dbChart1), true);
        //dd($dbChart1);
        //Grafik Belum di proyek bayar
        $chart_options = [
            'chart_title' => 'Laporan Proyek Belum Di Bayar',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\payment',
            'group_by_field' => 'status',
            'aggregate_function' => 'count',
            'aggregate_field' => 'status',
            'chart_type' => 'pie',
            'chart_color' => '255, 0, 30, 1',
        ];

        $chart1 = new LaravelChart($chart_options);

        return view('chart', [
            'chart1' => $chart1,
            'chart2' => $chartPendapatan,
            'judul2' => 'Grafik Laporan Pendapatan',
            'chart' => '0',
            'statusChart1'=>$dbChart1
        ]);
    }

    public function laporanProyekAdmin($status)
    {
        // $query = "SELECT tanggalAmbil,client,freelancer,proyek,deadlineproyek,modul,deadlinemodul
        // FROM modul_diambil,proyek,customer,modul
        // WHERE";
        // $db = DB::select($query);
        // $db = json_decode(json_encode($db), true);


        $modulDiambil = modulDiambil::where('status', $status)->get();
        $proyekId = array();
        $modulId = array();

        foreach ($modulDiambil as $modul) {
            array_push($proyekId, $modul->proyek_id);
            array_push($modulId, $modul->modul_id);
        }

        $proyek = proyek::whereIn('proyek_id', $proyekId)->get();
        $modul = modul::whereIn('modul_id', $modulId)->get();
        $cust = customer::get();

        return view("laporanProyekAdmin", [
            'modulDiambil' => $modulDiambil,
            'proyek' => $proyek,
            'modul' => $modul,
            'cust' => $cust,
            'status' => $status
        ]);
    }

    public function loadLaporanBelumBayar($status)
    {
        $query = "SELECT customer.nama as namaClient, payment.email as email, customer.nomorhp as hp,
         modul.title as judul, payment.amount as hargamodul, payment.service_fee as servicefee,
         payment.grand_total as grand, payment.created_at as penagihan, payment.status as stat, payment.payment_id as idPay
        FROM payment, customer, modul
        WHERE payment.modul_id=modul.modul_id AND payment.email = customer.email AND payment.status = '$status'";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);
        //dd($db);
        return view("laporanBelumBayar", [
            'laporanBelumBayar' => $db,
            'status' => $status
        ]);
    }

    public function filterLaporanAdmin(Request $request, $status)
    {
        $modulDiambil = modulDiambil::where('status', $status)->where('created_at', '>=', $request->input('dateStart'))->where('created_at', '<=', $request->input('dateEnd'))->get();
        $proyekId = array();
        $modulId = array();

        //dd($modulDiambil);
        foreach ($modulDiambil as $modul) {
            array_push($proyekId, $modul->proyek_id);
            array_push($modulId, $modul->modul_id);
        }

        $proyek = proyek::whereIn('proyek_id', $proyekId)->get();
        $modul = modul::whereIn('modul_id', $modulId)->get();
        $cust = customer::get();

        return view("laporanProyekAdmin", [
            'modulDiambil' => $modulDiambil,
            'proyek' => $proyek,
            'modul' => $modul,
            'cust' => $cust,
            'status' => $status
        ]);
    }

    public function freelancerAktif()
    {
        $query = "SELECT DISTINCT TableAktif.*
        FROM (
            SELECT customer.cust_id as id,customer.nama as nama,customer.email as email, customer.nomorhp as hp, -1 as lastProject, customer.deleted_at as deleteStat
            FROM customer,modul_diambil
            WHERE customer.role = 'freelancer' AND customer.cust_id NOT IN(Select(modul_diambil.cust_id)FROM modul_diambil)


            UNION ALL

            SELECT customer.cust_id as id,customer.nama as nama,customer.email as email, customer.nomorhp as hp, DATEDIFF(NOW(),modul_diambil.created_at) as lastProject, customer.deleted_at as deleteStat
            FROM customer,modul_diambil
            WHERE customer.cust_id=modul_diambil.cust_id
        ) as TableAktif
        ORDER BY TableAktif.lastProject ASC";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);
        //$dbPro = proyek::get();
        $sortedArrNama = array();
        $sortedArr = array();
        foreach ($db as $key) {
            if (!in_array($key['nama'], $sortedArrNama)) {
                array_push($sortedArrNama, $key["nama"]);
                array_push($sortedArr, $key);
            }
        }

        $sortedArr =  array_reverse($sortedArr);
        //dd($sortedArr);
        return view('LaporanFreelancerAktif', [
            "dataFreelancerClient" => $sortedArr
        ]);
    }

    public function detailLaporanFreelancerAktif($custId)
    {
        $query = "SELECT kategori.nama_kategori as nama, COUNT(tag.tag_id) as jumlah
        FROM (
            SELECT DISTINCT modul_diambil.proyek_id as proid
            FROM modul_diambil
            WHERE cust_id=$custId
        ) as TableAktif, tag, kategori
        WHERE TableAktif.proid =  tag.proyek_id AND kategori.kategori_id = tag.kategori_id
        GROUP BY nama
        ORDER BY nama DESC";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);
        //dd($db);
        $nama = array();
        $jumlah = array();
        foreach ($db as $Valnama) {
            array_push($nama, $Valnama['nama']);
            array_push($jumlah, $Valnama['jumlah']);
        }

        $chartumur = new chartControl;
        $chartumur->labels($nama);
        $chartumur->dataset('Tag Proyek', 'pie', $jumlah)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );


        $query = "SELECT kategori_job.judul_kategori as nama, COUNT(kategori_job.kategorijob_id) as jumlah
        FROM (
            SELECT DISTINCT modul_diambil.proyek_id as proid, proyek.kategorijob_id as idjob
            FROM modul_diambil, proyek
            WHERE modul_diambil.cust_id=$custId And modul_diambil.proyek_id =  proyek.proyek_id
        ) as TableAktif, kategori_job
        WHERE kategori_job.kategorijob_id = TableAktif.idjob
        GROUP BY nama
        ORDER BY nama DESC";
        // $query=" SELECT DISTINCT modul_diambil.proyek_id as proid, proyek.kategorijob_id as idjob
        // FROM modul_diambil, proyek
        // WHERE modul_diambil.cust_id=$custId And modul_diambil.proyek_id =  proyek.proyek_id";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);
        //dd($db);

        $namaKategori = array();
        $jumlahKategori = array();
        foreach ($db as $Valnama) {
            array_push($namaKategori, $Valnama['nama']);
            array_push($jumlahKategori, $Valnama['jumlah']);
        }

        $chartKategori = new chartControl;
        $chartKategori->labels($namaKategori);
        $chartKategori->dataset('Kategori Proyek', 'pie', $jumlahKategori)->options(
            [
                'backgroundColor' => [
                    "rgb(54, 162, 235)",
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(55, 212, 79)',
                    'rgb(60, 66, 61)',
                    'rgb(245, 118, 7)',
                    'rgb(8, 69, 115)',
                    'rgb(88, 8, 115)'
                ]
            ]
        );

        return view("chart", [
            'chart' => $chartumur,
            'judul' => 'Grafik Tag Proyek Favorit',
            'chart1' => '0',
            'chart2' => $chartKategori,
            'judul2' => 'Grafik Kategori Proyek Favorit'
        ]);
    }

    public function loadProyekBerhasil()
    {
        $chart_options = [
            'chart_title' => 'Laporan Proyek Berhasil',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\modulDiambil',
            'group_by_field' => 'status',
            'aggregate_function' => 'count',
            'aggregate_field' => 'modultaken_id',
            'chart_type' => 'pie',
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('chart', [
            'chart1' => $chart1,
            'chart2' => '0',
            'chart' => "0",
        ]);
    }

    public function historiSaldoAdmin()
    {
        $dataPayment = payment::where('status', 'Completed')->orWhere('status','Paid')->orWhere('status','close')->get();
        $dataPayment = json_decode(json_encode($dataPayment), true);

        $dataPenarikan = penarikan::withTrashed()->where('cust_id', session()->get('cust_id'))->get();
        $dataPenarikan = json_decode(json_encode($dataPenarikan), true);

        $total = 0;
        foreach ($dataPayment as $key) {
            $total = $total + (int) $key['service_fee'];
        }

        $totalPenarikan = 0;
        foreach ($dataPenarikan as $penarikan) {
            $totalPenarikan = $totalPenarikan + (int) $penarikan['jumlah'];
        }

        $sisaSaldo = (int)$total - (int)$totalPenarikan;

        return view('detailSaldoAdmin', [
            'dataPayment' => $dataPayment,
            'total' => $total,
            'dataPenarikan' => $dataPenarikan,
            'totalPenarikan' => $totalPenarikan,
            'sisaSaldo'=>$sisaSaldo
        ]);
    }

    public function nonAktifkanAkun($emailFreelancer){
        DB::beginTransaction();
        $custFreelancer= customer::where('email',$emailFreelancer)->first();
        $custFreelancer->delete();
        if($custFreelancer){
            DB::commit();
            return Redirect::back()->with('success','Akun Berhasil Di Non-Aktifkan!');
        }else{
            DB::rollBack();
            return Redirect::back()->with('error','Akun Gagal Di Non-Aktifkan!');
        }
    }

    public function aktifkanAkun($emailFreelancer){
        DB::beginTransaction();
        $custFreelancer= customer::where('email',$emailFreelancer)->withTrashed()->first();
        $custFreelancer->restore();
        if($custFreelancer){
            DB::commit();
            return Redirect::back()->with('success','Akun Berhasil Di Aktifkan!');
        }else{
            DB::rollBack();
            return Redirect::back()->with('error','Akun Gagal Di Aktifkan!');
        }
    }

    public function tambahTag(Request $request){
        DB::beginTransaction();

        $newTag = new kategori();
        $newTag->nama_kategori = $request->input('tag');
        $newTag->save();

        if($newTag){
            DB::commit();
            return Redirect::back()->with('success','Tag baru berhasil di buat!');
        }else{
            DB::rollBack();
            return Redirect::back()->with('error','Tag gagal ditambahkan!');
        }
    }

    public function tambahKategori(Request $request){
        DB::beginTransaction();

        $newKategori = new jobKategori();
        $newKategori->judul_kategori = $request->input('judul');
        $newKategori->save();

        if($newKategori){
            DB::commit();
            return Redirect::back()->with('success','Kategori baru berhasil di buat!');
        }else{
            DB::rollBack();
            return Redirect::back()->with('error','Kategori gagal ditambahkan!');
        }
    }
}
