<?php

namespace App\Http\Controllers;

use App\Charts\chartControl;
use App\Charts\chartGen;
use App\Models\customer;
use App\Models\jobKategori;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\payment;
use App\Models\penarikan;
use App\Models\proyek;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Google\Service\Monitoring\Custom;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Xendit\Xendit;

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
            'total_penarikan' => 'required|numeric'
        ], [
            'no_rek.required' => 'Nomor Rekening tidak dapat kosong!',
            'total_penarikan.required' => 'Total Penarikan tidak dapat kosong!',
            'no_rek.numeric' => 'Nomor Rekening hanya boleh berisi angka!',
            'total_penarikan.numeric' => 'Total Penarikan hanya boleh berisi angka!',
        ]);

        $tanggalReq = date('Y-m-d H:i:s');

        DB::beginTransaction();

        $insertPenarikan = new penarikan();
        $insertPenarikan->cust_id = Session::get('cust_id');
        $insertPenarikan->no_rek = $request->input('no_rek');
        $insertPenarikan->bank = $request->input('bank');
        $insertPenarikan->jumlah = $request->input('total_penarikan');
        $insertPenarikan->tanggal_request = $tanggalReq;
        $insertPenarikan->save();

        if ($insertPenarikan) {
            DB::commit();
            return Redirect::back()->with('success', 'Permohonan penarikan telah di buat!');
        } else {
            DB::rollback();
            return Redirect::back()->with('success', 'Permohonan penarikan gagal di buat!');
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
        $updateSaldoCust->saldo = $updateSaldoCust->saldo + $paymentData->amount;
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
        $chart_options = [
            'chart_title' => 'Laporan Pendapatan',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\payment',
            'group_by_field' => 'payment_time',
            'group_by_period' => 'month',
            'aggregate_function' => 'sum',
            'aggregate_field' => 'service_fee',
            'chart_type' => 'bar',
            'chart_color' => '245, 0, 30, 1'
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('chart', [
            'chart1' => $chart1,
            'chart2' => '0',
            'chart' => '0',
        ]);
    }

    public function loadLaporanBulanAktif()
    {

        $query = "SELECT modul_diambil.created_at as tanggal ,kategori_job.judul_kategori,count(proyek.created_at) as counts
        FROM proyek,kategori_job,modul_diambil
        WHERE proyek.kategorijob_id=kategori_job.kategorijob_id and proyek.proyek_id=modul_diambil.proyek_id
        GROUP BY modul_diambil.created_at, kategori_job.judul_kategori";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);

        $newSortedArray = [];
        $kategorijob = jobKategori::get();
        $sameDate = false;

        foreach ($db as $dataArr) {
            if ($newSortedArray != null) {
                foreach ($newSortedArray as $sortArr) {
                    if ($sortArr['tanggal'] == $dataArr['tanggal']) {
                        $data = [
                            "judul_kategori" => $dataArr['judul_kategori'],
                            "counts" => $dataArr['counts']
                        ];
                        array_pop($newSortedArray);
                        array_push($sortArr['data'], $data);
                        $newArrData = [];
                        $newArrData['tanggal'] = $dataArr['tanggal'];
                        $newArrData["data"] = $sortArr['data'];
                        array_push($newSortedArray, $newArrData);
                        $sameDate = true;
                    } else {
                        $sameDate = false;
                    }
                }
            }

            if (!$sameDate) {
                $data = [
                    [
                        "judul_kategori" => $dataArr['judul_kategori'],
                        "counts" => $dataArr['counts']
                    ]
                ];
                $newArr = [];
                $newArr['tanggal'] = $dataArr['tanggal'];
                $newArr['data'] = $data;
                array_push($newSortedArray, $newArr);
            }
        }


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
            "dataBulan" => $newSortedArray,
            "kategoriJob" => $kategorijob,
            'rekap' => $dbSortedMonth,
            'rekapKategori' => $dbKategori
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
            'month'=>Carbon::parse($months)->translatedFormat('F')
        ]);
    }

    public function laporanFreelancer()
    {
        $queryUmur = "SELECT FLOOR(DATEDIFF(CURDATE(),customer.tanggal_lahir)/365) AS umur, count(customer.cust_id) AS Jumlah
            FROM customer
            WHERE customer.role='freelancer'
            Group By umur";
        $dbumur = DB::select($queryUmur);
        $dbumur = json_decode(json_encode($dbumur), true);
        $umur = array();
        $jumlah = array();
        foreach ($dbumur as $Valnama) {
            array_push($umur, $Valnama['umur']);
            array_push($jumlah, $Valnama['Jumlah']);
        }


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


        $chart_options = [
            'chart_title' => 'Tingkat Edukasi',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\customer',
            'group_by_field' => 'pendidikan',
            'aggregate_function' => 'count',
            'aggregate_field' => 'pendidikan',
            'chart_type' => 'bar',
            'chart_color' => '255, 0, 30, 1',
            'where_raw' => "role = 'freelancer'"
        ];
        $chart1 = new LaravelChart($chart_options);


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

        return view("chart", [
            'chart' => $chartumur,
            'judul' => 'Grafik Umur Freelancer',
            'chart1' => $chart1,
            'chart2' => $chartspesialisasi,
            'judul2' => 'Grafik Spesialisasi Freelancer'
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

    public function proyekTidakTerbayar()
    {
        $payment = payment::paginate(10);
        return view('laporanProyekTidakBayar', [
            'dataPayment' => $payment
        ]);
    }

    public function chartProyekTidakBayar()
    {
        $chart_options = [
            'chart_title' => 'Laporan Proyek Belum Di Bayar',
            'report_type' => 'group_by_string',
            'model' => 'App\Models\payment',
            'group_by_field' => 'status',
            'aggregate_function' => 'count',
            'aggregate_field' => 'status',
            'chart_type' => 'pie',
            'chart_color' => '255, 0, 30, 1'
        ];
        $chart1 = new LaravelChart($chart_options);

        return view('chart', [
            'chart1' => $chart1,
            'chart2' => '0',
            'chart' => '0'
        ]);
    }

    public function laporanProyekAdmin($status)
    {
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
        $query = "SELECT TableAktif.*
            FROM (
                SELECT customer.nama, 0 as Jumlah
                FROM customer,modul_diambil
                WHERE customer.role = 'freelancer' AND customer.cust_id NOT IN(Select(modul_diambil.cust_id)FROM modul_diambil)
                Group By customer.nama

                UNION ALL

                SELECT customer.nama as nama, count(modul_diambil.cust_id) as Jumlah
                FROM customer,modul_diambil
                WHERE customer.role = 'freelancer' AND customer.cust_id=modul_diambil.cust_id
                Group By customer.nama
            ) as TableAktif
            ORDER BY TableAktif.Jumlah DESC";
        $db = DB::select($query);
        $db = json_decode(json_encode($db), true);


        return view('LaporanFreelancerAktif', [
            "dataFreelancer" =>$db
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
        $dataPayment = payment::where('status', 'Completed')->get();
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

        return view('detailSaldoAdmin', [
            'dataPayment' => $dataPayment,
            'total' => $total,
            'dataPenarikan' => $dataPenarikan,
            'totalPenarikan' => $totalPenarikan
        ]);
    }
}
