@extends('header')
@section('content')
    <div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div class="card mt-3 mb-3">
            <h5 class="card-header">Permohonan Pembatalan Freelancer</h5>

            <div class="card-body" style="text-align: center">
                <table class="table table-striped">
                    <thead class="fw-bold">
                        <tr>
                            <td>
                                Client
                            </td>
                            <td>
                                Freelancer
                            </td>
                            <td>
                                Proyek
                            </td>
                            <td>
                                Modul
                            </td>
                            <td>
                                Alasan
                            </td>
                            <td>

                            </td>
                        </tr>
                    </thead>
                    @foreach ($arrDataPembatalan as $data)
                        <tr>
                            <td>
                                {{ $data['client'] }}
                            </td>
                            <td>
                                {{ $data['freelancer'] }}

                            </td>
                            <td>
                                <p class="text-primary fw-bold">{{ $data['proyek'] }}</p>

                            </td>
                            <td>
                                <p class="fw-bold">{{ $data['modul'] }}</p>
                            </td>
                            <td>
                                <p style="word-wrap: break-word">{{ $data['alasan'] }}</p>
                            </td>
                            <td>
                                <button class="btn btn-success" data-bs-target={{ '#modalPost' . $data['modulTakenId'] }}
                                    data-bs-toggle="modal">Terima</button>
                                    <button class="btn btn-danger" data-bs-target={{ '#modalTolak' . $data['modulTakenId'] }}
                                    data-bs-toggle="modal">Tolak</button>

                                {{-- Modal Post --}}
                                <div class="modal fade" tabindex="-1" aria-hidden="true"
                                    id={{ 'modalPost' . $data['modulTakenId'] }}>

                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalToggleLabel2">
                                                    Setujui Pembatalan?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Anda akan menyetujui pembatalan untuk freelancer
                                                <b>{{ $data['freelancer'] }}</b>
                                                dari proyek <b>{{ $data['proyek'] }}</b> dengan modul
                                                <b>{{ $data['modul'] }}</b>
                                            </div>
                                            <div class="modal-footer">
                                                <a class='btn btn-info btn-sm'
                                                    href={{url("/batalFreelancerAdmin/$data[modulTakenId]/$data[pembatalanId]/acc")}}>Ya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" tabindex="-1" aria-hidden="true"
                                    id={{ 'modalTolak' . $data['modulTakenId'] }}>

                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalToggleLabel2">
                                                    Tolak Pembatalan?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Anda akan menolak pembatalan untuk freelancer
                                                <b>{{ $data['freelancer'] }}</b>
                                                dari proyek <b>{{ $data['proyek'] }}</b> dengan modul
                                                <b>{{ $data['modul'] }}</b>
                                            </div>
                                            <div class="modal-footer">
                                                <a class='btn btn-info btn-sm'
                                                    href={{url("/batalFreelancerAdmin/$data[modulTakenId]/$data[pembatalanId]/dec")}}>Ya</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
@endsection
