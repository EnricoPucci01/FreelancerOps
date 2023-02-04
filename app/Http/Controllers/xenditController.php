<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\modul;
use App\Models\modulDiambil;
use App\Models\payment;
use App\Models\penarikan;
use App\Models\proyek;
use App\Models\tambahRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Xendit\Xendit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Svg\Tag\Rect;

class xenditController extends Controller
{
    private $privateKey = 'xnd_development_uREgjsmrZKHohnFS0ZjD2KvHrrPdD8RRAX0DUH7DzEq1KUevIqzfqxOUnNZkgDdd';

    public function getBalance()
    {
        Xendit::setApiKey($this->privateKey);
        $getBalance = \Xendit\Balance::getBalance('CASH');
        var_dump($getBalance);
    }

    public function getListVA()
    {
        Xendit::setApiKey($this->privateKey);
        $getList = \Xendit\VirtualAccounts::getVABanks();
        return response()->json([
            'data' => $getList
        ])->setStatusCode(200);
    }

    public function createVA(Request $request, $modulId)
    {
        Xendit::setApiKey($this->privateKey);
        DB::beginTransaction();
        $generateExternalId = 'va-' . date('dmYHis');
        $modulAmount = modul::where('modul_id', $modulId)->first();
        $proyek = proyek::where('proyek_id', $modulAmount->proyek_id)->first();
        $cust = customer::where('cust_id', $proyek->cust_id)->first();
        $modulAmount = json_decode(json_encode($modulAmount), true);
        $serviceFee = 0;
        $grandTotal = 0;

        $tipeProyek = proyek::where('proyek_id', $modulAmount['proyek_id'])->first();
        $tipeProyek = json_decode(json_encode($tipeProyek), true);

        if ($tipeProyek['tipe_proyek'] == 'magang') {

            $grandTotal = (int)$request->input('grand_total');

            $param = [
                'external_id' => $generateExternalId,
                'bank_code' => 'BCA',
                'name' => Session::get('name'),
                'expected_amount' => $grandTotal,
                'is_closed' => true,
                'expiration_date' => Carbon::now()->addDays(7)->toISOString(),
                'is_single_use' => true
            ];
        } else {
            $serviceFee = ((int)$modulAmount['bayaran'] * 5) / 100;
            $grandTotal = (int)$modulAmount['bayaran'] + $serviceFee;
            $param = [
                'external_id' => $generateExternalId,
                'bank_code' => 'BCA',
                'name' => Session::get('name'),
                'expected_amount' => $grandTotal,
                'is_closed' => true,
                'expiration_date' => Carbon::now()->addDays(30)->toISOString(),
                'is_single_use' => true
            ];
        }

        $insertPayment = new payment();
        $insertPayment->external_id = $generateExternalId;
        $insertPayment->modul_id = $modulId;
        $insertPayment->cust_id = ($tipeProyek["tipe_proyek"] == 'magang') ? Session::get('idCustFreelancer') : Session::get('cust_id');
        $insertPayment->payment_channel = 'Virtual Account';
        $insertPayment->amount = ($tipeProyek["tipe_proyek"] == 'magang') ? $request->input('grand_total') : $modulAmount['bayaran'];
        $insertPayment->service_fee = $serviceFee;
        $insertPayment->grand_total = $grandTotal;
        $insertPayment->email = $cust->email;
        $insertPayment->status = 'unpaid';
        $insertPayment->save();
        $createVA = \Xendit\VirtualAccounts::create($param);
        if ($insertPayment) {
            DB::commit();
            if ($tipeProyek['tipe_proyek'] == 'magang') {
                return redirect("/loadPembayaran/$modulId");
            } else {
                return redirect()->back()->with('success', 'Progress Berhasil Di Update!');
            }
        }
    }

    public function createVAMagang($proyekId)
    {
        Xendit::setApiKey($this->privateKey);
        DB::beginTransaction();
        $generateExternalId = 'vapm-' . date('dmYHis');
        $proyek = proyek::where('proyek_id', $proyekId)->first();
        $cust = customer::where('cust_id', $proyek->cust_id)->first();

        $param = [
            'external_id' => $generateExternalId,
            'bank_code' => 'BCA',
            'name' => Session::get('name'),
            'expected_amount' => '100000',
            'is_closed' => true,
            'expiration_date' => Carbon::now()->addDays(7)->toISOString(),
            'is_single_use' => true
        ];

        $insertPayment = new payment();
        $insertPayment->external_id = $generateExternalId;
        $insertPayment->modul_id = $proyekId;
        $insertPayment->cust_id = Session::get('cust_id');
        $insertPayment->payment_channel = 'Virtual Account';
        $insertPayment->amount = '0';
        $insertPayment->service_fee = '100000';
        $insertPayment->grand_total = '100000';
        $insertPayment->email = $cust->email;
        $insertPayment->status = 'unpaid';
        $insertPayment->save();
        $createVA = \Xendit\VirtualAccounts::create($param);
        if ($insertPayment) {
            DB::commit();
            return redirect("/loadPembayaranPostMagang/$proyekId");
        }else{
            DB::rollBack();
            return Redirect::back()->with('error',"Pembayaran gagal dibuat, silahkan coba lagi setelah beberapa saat!");
        }
    }

    public function callbackVA($externalId)
    {
        $dateTime = date('Y-m-d H:i:s');
        DB::beginTransaction();
        $dataPayment = payment::where('external_id', $externalId)->first();
        $dataPayment = json_decode(json_encode($dataPayment), true);

        if (!is_null($dataPayment)) {
            $dataPayment = payment::where('external_id', $externalId)->first();
            if (Session::get('paymentType') == 'normal') {
                $dataPayment->status = 'Paid';
            } else {
                $dataPayment->status = 'Completed';
            }
            $dataPayment->payment_time = $dateTime;
            $dataPayment->email = Session::get('active');
            $dataPayment->save();

            if ($dataPayment) {
                DB::commit();
                $proyekId = Session::get('pembayaranProyek');
                $custId = Session::get('cust_id');
                if (Session::get('paymentType') == 'normal') {
                    return redirect("/loadDetailProyekClient/$proyekId/c")->with('success', 'Pembayaran Berhasil!');
                } else {
                    $updateProyek = proyek::where('proyek_id', Session::get('pembayaranProyek'))->first();
                    $updateProyek->project_active = 'true';
                    $updateProyek->save();
                    if ($updateProyek) {
                        $updateSaldoAdmin = customer::where('cust_id', "14")->first();
                        $updateSaldoAdmin->saldo = $updateSaldoAdmin->saldo + 100000;
                        $updateSaldoAdmin->save();
                        DB::commit();
                        return redirect("/loadDetailProyekClient/$proyekId/c")->with('success', 'Pembayaran Berhasil!');
                    } else {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Pembayaran Gagal!');
                    }
                }
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Pembayaran Gagal!');
            }
        } else {
            return redirect()->back()->with('error', 'Data Tidak Ditemukan!');
        }
    }

    public function autoClosePayment(){
        $getExternalId=Payment::where("status","Paid")->get();
        $getExternalId=json_decode(json_encode($getExternalId),true);
        foreach($getExternalId as $item){
            if(Carbon::now()->gt(Carbon::parse($item['created_at'])->addDay(7))){
                $dateTime = date('Y-m-d H:i:s');
                DB::beginTransaction();
                $dataPayment = payment::where('external_id', $item['external_id'])->first();
                $dataPayment = json_decode(json_encode($dataPayment), true);
                $updateSaldoFreelancer=customer::where('cust_id',$item['cust_id'])->first();
                $updateSaldoFreelancer = json_decode(json_encode($updateSaldoFreelancer), true);
                if (!is_null($dataPayment)) {
                    if(!is_null($updateSaldoFreelancer)){
                        $dataPayment = payment::where('external_id', $item['external_id'])->first();
                        if (Session::get('paymentType') == 'normal') {
                            $dataPayment->status = 'Paid';
                        } else {
                            $dataPayment->status = 'Completed';
                        }
                        $dataPayment->payment_time = $dateTime;
                        $dataPayment->email = Session::get('active');
                        $dataPayment->save();

                        $updateSaldoAdmin = customer::where('cust_id', "14")->first();
                        $updateSaldoAdmin->saldo = $updateSaldoAdmin->saldo + (int)$item['service_fee'];
                        $updateSaldoAdmin->save();

                        $updateSaldoFreelancer=customer::where('cust_id',$item['cust_id'])->first();
                        $updateSaldoFreelancer->saldo=$updateSaldoFreelancer->saldo + (int)$item['amount'];
                        $updateSaldoFreelancer->save();

                        if($dataPayment && $updateSaldoAdmin && $updateSaldoFreelancer){
                            DB::commit();
                        }else{
                            DB::rollBack();
                        }
                    }
                }
            }
        }
        var_dump('true');
        return \redirect('/adminDashboard');
    }

    public function simulatePayment(Request $request, $externalId)
    {
        $username = $this->privateKey . ':';
        $username = base64_encode($username);

        $response = Http::withHeaders([
            'Authorization' => ' Basic ' . $username
        ])->post("https://api.xendit.co/callback_virtual_accounts/external_id=$externalId/simulate_payment", [
            'amount' => $request->input('grand_total')
        ]);
        $response = json_decode($response, true);

        if (key_exists('status', $response)) {
            if ($response['status'] == 'COMPLETED') {
                Session::put('paymentType', 'normal');
                return redirect("/callbackva/$externalId");
            }
        } else {
            return 'Fail ' . $response["error_code"];
        }
    }

    public function simulatePaymentPostMagang(Request $request, $externalId)
    {
        $username = $this->privateKey . ':';
        $username = base64_encode($username);

        $response = Http::withHeaders([
            'Authorization' => ' Basic ' . $username
        ])->post("https://api.xendit.co/callback_virtual_accounts/external_id=$externalId/simulate_payment", [
            'amount' => $request->input('grand_total')
        ]);
        $response = json_decode($response, true);

        if (key_exists('status', $response)) {
            if ($response['status'] == 'COMPLETED') {
                Session::put('paymentType', 'postMagang');
                return redirect("/callbackva/$externalId");
            }
        } else {
            return 'Fail ' . $response["error_code"];
        }
    }


    public function loadPembayaran($modulId)
    {
        $paymentCheck = payment::where('modul_id', $modulId)->first();
        $paymentCheck = json_decode(json_encode($paymentCheck), true);
        if(Carbon::parse($paymentCheck['created_at'])->addDays(30) <= Carbon::now()){
            DB::beginTransaction();
            $paymentExp= payment::find($paymentCheck['payment_id']);
            $paymentExp->delete();
            if($paymentExp){
                Xendit::setApiKey($this->privateKey);
                $generateExternalId = 'va-' . date('dmYHis');
                $serviceFee = $paymentCheck['service_fee'];
                $grandTotal = (int)$paymentCheck['grand_total'];
                $param = [
                    'external_id' => $generateExternalId,
                    'bank_code' => 'BCA',
                    'name' => Session::get('name'),
                    'expected_amount' => $grandTotal,
                    'is_closed' => true,
                    'expiration_date' => Carbon::now()->addDays(30)->toISOString(),
                    'is_single_use' => true
                ];

                $insertPayment = new payment();
                $insertPayment->external_id = $generateExternalId;
                $insertPayment->modul_id = $modulId;
                $insertPayment->cust_id = Session::get('cust_id');
                $insertPayment->payment_channel = 'Virtual Account';
                $insertPayment->amount = $paymentCheck['amount'];
                $insertPayment->service_fee = $serviceFee;
                $insertPayment->grand_total = $grandTotal;
                $insertPayment->email = $paymentCheck["email"];
                $insertPayment->status = 'unpaid';
                $insertPayment->save();

                if($insertPayment){
                    $createVA = \Xendit\VirtualAccounts::create($param);

                    $modul = modul::where('modul_id', $modulId)->first();
                    $modul = json_decode(json_encode($modul), true);

                    $paymentDetail = payment::where('modul_id', $modulId)->first();
                    $paymentDetail = json_decode(json_encode($paymentDetail), true);

                    $detailProyek = proyek::where('proyek_id', $modul['proyek_id'])->first();
                    $detailProyek = json_decode(json_encode($detailProyek), true);

                    Session::put('pembayaranProyek', $modul['proyek_id']);
                    DB::commit();
                    return view('pembayaran', [
                        'dataModul' => $modul,
                        'dataPayment' => $paymentDetail,
                        'dataProyek' => $detailProyek,
                    ]);
                }else{
                    DB::rollBack();
                    return Redirect::back()->with('error','Gagal membuat ulang tagihan, silahkan coba lagi');
                }
            }else{
                DB::rollBack();
                return Redirect::back()->with('error','Gagal membuat ulang tagihan, silahkan coba lagi');
            }

        }else{
            $modul = modul::where('modul_id', $modulId)->first();
            $modul = json_decode(json_encode($modul), true);

            $paymentDetail = payment::where('modul_id', $modulId)->first();
            $paymentDetail = json_decode(json_encode($paymentDetail), true);

            $detailProyek = proyek::where('proyek_id', $modul['proyek_id'])->first();
            $detailProyek = json_decode(json_encode($detailProyek), true);

            Session::put('pembayaranProyek', $modul['proyek_id']);
            return view('pembayaran', [
                'dataModul' => $modul,
                'dataPayment' => $paymentDetail,
                'dataProyek' => $detailProyek,
            ]);
        }
    }

    public function loadPembayaranMagang($modulId)
    {
        $modul = modul::where('modul_id', $modulId)->first();
        $modul = json_decode(json_encode($modul), true);

        $detailProyek = proyek::where('proyek_id', $modul['proyek_id'])->first();
        $detailProyek = json_decode(json_encode($detailProyek), true);

        $modulDiambil = modulDiambil::where("modul_id", $modulId)->where('proyek_id', $detailProyek['proyek_id'])->where('status', '!=', 'dibatalkan')->first();

        Session::put('pembayaranProyek', $modul['proyek_id']);
        Session::put('idCustFreelancer', $modulDiambil->cust_id);

        return view('pembayaranMagang', [
            'dataModul' => $modul,
            'dataProyek' => $detailProyek,
        ]);
    }

    public function loadPembayaranPostMagang($proyekId)
    {
        $detailProyek = proyek::where('proyek_id', $proyekId)->first();
        $detailProyek = json_decode(json_encode($detailProyek), true);
        $paymentDetail = payment::where('modul_id', $proyekId)->first();
        $paymentDetail = json_decode(json_encode($paymentDetail), true);

        Session::put('pembayaranProyek', $detailProyek['proyek_id']);
        Session::put('idCustFreelancer', $detailProyek['cust_id']);

        return view('pembayaranPostMagang', [
            'dataProyek' => $detailProyek,
            'dataPayment' => $paymentDetail
        ]);
    }

    public function createDisburse(Request $request, $custName, $penarikanId)
    {
        Xendit::setApiKey($this->privateKey);
        $externalId = 'disb-' . date('dmYHis');
        $tanggalAdmit = date('Y-m-d H:i:s');
        $date = date('d-m-YHis');
        $filename = 'BuktiTransfer' . $date . "." . $request->file("buktiTF")->getClientOriginalExtension();
        $path = '';
        if (!empty($request->file("buktiTF"))) {
            $path = $request->file('buktiTF')->storeAs("buktiTransfer", $filename, 'public');

            if ($path != "") {
                $image = $request->file('buktiTF'); //image file from frontend
                $firebase_storage_path = 'buktiTransfer/';
                $localfolder = public_path('firebase-temp-uploads') . '/';
                if ($image->move($localfolder, $filename)) {
                    $uploadedfile = fopen($localfolder . $filename, 'r');
                    app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $filename]);
                    //will remove from local laravel folder
                    unlink($localfolder . $filename);
                }
            }
        }
        if ($path != '') {
            DB::beginTransaction();
            $dataPenarikan = penarikan::where('penarikan_id', $penarikanId)->first();
            $dataPenarikan = json_decode(json_encode($dataPenarikan), true);

            $UpdateSaldoCust = customer::where('cust_id', $dataPenarikan['cust_id'])->first();
            $dataSaldo = $UpdateSaldoCust->saldo;
            $UpdateSaldoCust->saldo = (int)$dataSaldo - (int)$dataPenarikan['jumlah'];
            $UpdateSaldoCust->save();

            if ($UpdateSaldoCust) {
                $penarikan = penarikan::where('penarikan_id', $penarikanId)->first();
                $penarikan->tanggal_admit = $tanggalAdmit;
                $penarikan->bukti_tf = $filename;
                $penarikan->save();

                if ($penarikan) {

                    $penarikan->delete();
                    if ($penarikan) {
                        DB::commit();
                        $disbParam = [
                            'external_id' => $externalId,
                            'amount' => $dataPenarikan['jumlah'],
                            'bank_code' => $dataPenarikan['bank'],
                            'account_holder_name' => $custName,
                            'account_number' => $dataPenarikan['no_rek'],
                            'description' => 'Penarikan Dana Tanggal ' . $dataPenarikan['tanggal_request'] . ' oleh ' . $custName,
                            'X-IDEMPOTENCY-KEY' => $externalId
                        ];

                        $createDisb = \Xendit\Disbursements::create($disbParam);

                        return Redirect::back()->with('success', 'Penarikan Berhasil Di Admit!');
                    } else {
                        DB::rollback();

                        return Redirect::back()->with('error', 'Penarikan Gagal Di Admit!');
                    }
                } else {
                    DB::rollback();

                    return Redirect::back()->with('error', 'Penarikan Gagal Di Admit!');
                }
            } else {
                DB::rollback();

                return Redirect::back()->with('error', 'Penarikan Gagal Di Admit!');
            }
        }
    }

    public function createInvoice($modulId)
    {
        $modulAmount = modul::where('modul_id', $modulId)->first();
        $modulAmount = json_decode(json_encode($modulAmount), true);

        $serviceFee = ((int)$modulAmount['bayaran'] * 5) / 100;
        $grandTotal = (int)$modulAmount['bayaran'] + $serviceFee;

        $dataCust = customer::where('cust_id', Session::get('cust_id'))->first();
        $dataCust = json_decode(json_encode($dataCust), true);

        Xendit::setApiKey($this->privateKey);

        $externalId = 'inv-' . date('dmYHis');
        $params = [
            'external_id' => $externalId,
            'amount' => $grandTotal,
            'description' => 'Invoice ' . $modulAmount['title'],
            'invoice_duration' => 604800,
            'customer' => [
                'given_names' => $dataCust['nama'],
                'surname' => "",
                'email' => $dataCust['email'],
                'mobile_number' => '+62' . $dataCust['nomorhp'],
                'address' => [
                    [
                        'city' => $dataCust['tempat_lahir'],
                        'country' => '',
                        'postal_code' => '',
                        'state' => '',
                        'street_line1' => '',
                        'street_line2' => ''
                    ]
                ]
            ],
            'customer_notification_preference' => [
                'invoice_created' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_reminder' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_paid' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ],
                'invoice_expired' => [
                    'whatsapp',
                    'sms',
                    'email',
                    'viber'
                ]
            ],
            'success_redirect_url' => 'https=>//www.google.com',
            'failure_redirect_url' => 'https=>//www.google.com',
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => $modulAmount['title'],
                    'quantity' => 1,
                    'price' => $modulAmount['bayaran'],
                    'category' => 'Electronic',
                    'url' => null
                ],
            ],
            'fees' => [
                [
                    'type' => 'Service Fee',
                    'value' => $serviceFee
                ]
            ]
        ];


        $insertPayment = new payment();
        $insertPayment->external_id = $externalId;
        $insertPayment->modul_id = $modulId;
        $insertPayment->cust_id = Session::get('cust_id');
        $insertPayment->payment_channel = 'Virtual Account';
        $insertPayment->amount = $modulAmount['bayaran'];
        $insertPayment->service_fee = $serviceFee;
        $insertPayment->grand_total = $grandTotal;
        $insertPayment->status = 'unpaid';
        $insertPayment->save();
        if ($insertPayment) {
            DB::commit();
            $createInvoice = \Xendit\Invoice::create($params);
            return redirect()->back()->with('success', 'Progress Berhasil Di Update!');
        }
        //var_dump($createInvoice);
    }

    public function loadRequestTarik()
    {
        Xendit::setApiKey($this->privateKey);
        $getList = \Xendit\VirtualAccounts::getVABanks();
        $getList = json_decode(json_encode($getList), true);

        $rekeningpenarikan = tambahRekening::where('cust_id', Session::get('cust_id'))->get();
        $rekeningpenarikan = json_decode(json_encode($rekeningpenarikan), true);
        return view('requestTarik', [
            'dataBank' => $getList,
            'dataRekening' => $rekeningpenarikan
        ]);
    }

    public function loadPenarikanDanaAdmin()
    {
        Xendit::setApiKey($this->privateKey);
        $getList = \Xendit\VirtualAccounts::getVABanks();
        $getList = json_decode(json_encode($getList), true);

        $norek = tambahRekening::where('cust_id', session()->get('cust_id'))->get();
        $norek = json_decode(json_encode($norek), true);
        return view('penarikanDanaAdmin', [
            'dataBank' => $getList,
            'dataRekening' => $norek
        ]);
    }

    public function penarikanDanaAdmin(Request $request)
    {
        $SaldoAdmin = customer::where('cust_id', '14')->first();

        if((int)$SaldoAdmin->saldo >= (int)str_replace(".","",$request['total_penarikan'])){
            Xendit::setApiKey($this->privateKey);
            $externalId = 'disbadm-' . date('dmYHis');

            $tanggalAdmit = date('Y-m-d H:i:s');
            DB::beginTransaction();
        $inPenarikan = new penarikan();
        $inPenarikan->cust_id = session()->get('cust_id');
        $inPenarikan->no_rek = $request['no_rek'];
        $inPenarikan->bank = $request['bank'];
        $inPenarikan->jumlah = str_replace(".","",$request['total_penarikan']);
        $inPenarikan->tanggal_request = $tanggalAdmit;
        $inPenarikan->save();

        if ($inPenarikan) {
            $disbParam = [
                'external_id' => $externalId,
                'amount' => str_replace(".","",$request['total_penarikan']),
                'bank_code' => $request['bank'],
                'account_holder_name' => 'Admin',
                'account_number' => $request['no_rek'],
                'description' => 'Penarikan Dana Tanggal ' . $tanggalAdmit . ' oleh ' . session()->get('name'),
                'X-IDEMPOTENCY-KEY' => $externalId
            ];

            $createDisb = \Xendit\Disbursements::create($disbParam);

            $upSaldoAdmin = customer::where('cust_id', '14')->first();
            $upSaldoAdmin->saldo = (int)$upSaldoAdmin->saldo - (int)str_replace(".","",$request['total_penarikan']);
            $upSaldoAdmin->save();

            if ($upSaldoAdmin) {
                DB::commit();
                return Redirect::back()->with('success', 'Penarikan Saldo Berhasil!');
            } else {
                DB::rollBack();
                return Redirect::back()->with('error', 'Penarikan Saldo Gagal Dilakukan!');
            }
        } else {
            DB::rollBack();
            return Redirect::back()->with('error', 'Penarikan Saldo Gagal Dilakukan!');
        }
        }else{
            return Redirect::back()->with('error', 'Jumlah penarikan melebihi total saldo!');
        }

    }

    public function loadTambahRekening()
    {
        Xendit::setApiKey($this->privateKey);
        $getList = \Xendit\VirtualAccounts::getVABanks();
        return view('tambahRekening', [
            'dataBank' => $getList
        ]);
    }
}
