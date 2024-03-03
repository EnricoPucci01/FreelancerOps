<!DOCTYPE html>
<html>

<head>
    <title>FreelancerOPS</title>
</head>

<body>
    <center>
        <img src="{{ base_path() }}/public/images/LogoTA.png" width="350" height="80">
        <hr>
    </center>


    <p>
        Yang bertanda tangan dibawah ini:
    </p>
    <p>
        Nama:
    </p>
    <p>
        Peran: Client
    </p>
    <p>
        Dalam hal ini bertidak sebagai pemberi pekerjaan dan selanjutnya akan disebut <b>PIHAK PERTAMA</b>
    </p>
    <br>
    <p>
        Nama:
    </p>
    <p>
        Peran: Freelancer
    </p>
    <p>
        Dalam hal ini bertidak sebagai penerima pekerjaan dan selanjutnya akan disebut <b>PIHAK KEDUA</b>
    </p>

    <br>

    <p>
        Nama: FreelancerOPS
    </p>
    <p>
        Peran: Pengawas
    </p>
    <p>
        Dalam hal ini bertidak sebagai pengawas dan selanjutnya akan disebut <b>PIHAK KETIGA</b>
    </p>

    <br>
    <p>
        Pada tanggal <b>{{ $date }}</b> kedua belah pihak telah bersepakat untuk mengikat diri dalam perjanjian
        kerja jangka pendek untuk mengerjakan modul {{ $modul }} dari proyek {{ $proyek }} dengan
        pengawasan dari PIHAK KETIGA syarat
        dan ketentuan sebagai berikut:
    </p>

    <p>
        Rincian:
    </p>
    <p>
    <ul>
        <li>{{ $deskripsi }}</li>
        <li>Tenggat waktu: {{ Carbon\Carbon::parse($deadline)->format('d-m-Y') }}</li>
    </ul>
    </p>

    <p>
        Tanggal Mulai:
    </p>
    <p>
        Proyek ini dijadwalkan untuk mulai pada tanggal {{ Carbon\Carbon::parse($deadline)->format('d-m-Y') }}
    </p>
    <p>
        Pembayaran:
    </p>
    <p>
        PIHAK KEDUA telah menyetujui akan menerima bayaran sebesar @money($total_pembayaran, 'IDR', true) dari PIHAK PERTAMA setelah proyek
        diserahkan melalui web.
    </p>
    <p>
        Tanggung Jawab PIHAK PERTAMA:
    </p>
    <p>
    <ul>
        <li>Menyediakan informasi dan sumber daya yang diperlukan untuk menyelesaikan proyek/pekerjaan.</li>
        <li>Memberikan umpan balik secara berkala untuk memastikan proyek/pekerjaan berjalan sesuai harapan.</li>
        <li>Melakukan pembayaran tepat waktu setelah proyek dinyatakan selesai dan telah di serahkan oleh PIHAK KEDUA.
        </li>
    </ul>
    </p>
    <p>
        Tanggung Jawab PIHAK KEDUA:
    </p>
    <p>
    <ul>
        <li>Menyelesaikan proyek/pekerjaan sesuai dengan persyaratan yang telah disepakati.</li>
        <li>Melaporkan kemajuan proyek secara berkala.</li>
    </ul>
    </p>

    <p>
        Tanggung Jawab PIHAK KETIGA:
    </p>
    <p>
    <ul>
        <li>Menyediakan layanan kepada kedua belah pihak ketika terjadi masalah pada pembayaran proyek</li>
        <li>Memberikan sanksi kepada pihak yang melakukan kecurangan atau pelanggaran pada perjanjian</li>
        <li>Meneruskan pembayaran dari PIHAK PERTAMA ke PIHAK KEDUA ketika peroyek telah dinyatakan selesai dan di tutup
        </li>
        <li>PIHAK KETIGA tidak akan bertanggung jawab atas segala masalah yang terjadi pada penyerahan proyek jika
            penyerahan proyek dilakukan tidak menggunakan Applikasi Web FreelancerOPS</li>
        <li>PIHAK KETIGA tidak akan bertanggung jawab atas segala masalah yang terjadi pada pembayaran proyek jika
            pembayaran proyek dilakukan tidak menggunakan Applikasi Web FreelancerOPS</li>
    </ul>
    </p>
    <p>
        Pemutusan Kontrak:
    </p>
    <p>
        Kontrak ini akan berakhir ketika proyek telah selesai dan di tutup oleh PIHAK PERTAMA atau ketika PIHAK KEDUA
        menyatakan ingin membatalkan Freelancer dengan persetujuan dari PIHAK KETIGA
    </p>

    <div style="margin-top: 50px">
        Telah mengetahui dan menyetujui:
        <p>{{ $date }}</p>
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td style="text-align: center">PIHAK PERTAMA</td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    {{-- <img src={{ asset("/storage/sign/$sign.png") }}
                                        style="display:block; object-fit: cover;" width="300" height="100" /> --}}
                                        <img src="{{ base_path() }}/public/storage/sign/{{ $sign }}.png"
                                            style="display:block; object-fit: cover;" width="300" height="100" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">{{ $sign }}</td>
                        </tr>
                    </table>
                </td>

                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: center">PIHAK KEDUA</td>
                            </tr>
                            <tr>
                                <td>
                                    <div>

                                        {{-- <img src={{ asset("/storage/sign/$freelancer.png") }}
                                            style="display:block; object-fit: cover;" width="300" height="100" /> --}}
                                        <img src="{{ base_path() }}/public/storage/sign/{{ $freelancer }}.png"
                                            style="display:block; object-fit: cover;" width="300" height="100" />
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">{{ $freelancer }}</td>
                            </tr>

                        </tbody>
                    </table>
                </td>


            </tr>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="text-align: center">PIHAK KETIGA</td>
                            </tr>
                            <tr>
                                <td>
                                    <div>

                                        {{-- <img src={{ asset("/storage/sign/$freelancer.png") }}
                                            style="display:block; object-fit: cover;" width="300" height="100" /> --}}
                                        <img src="{{ base_path() }}/public/storage/sign/{{ $freelancer }}.png"
                                            style="display:block; object-fit: cover;" width="300" height="100" />
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center">FreelancerOPS</td>
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

    </div>


</body>

</html>
