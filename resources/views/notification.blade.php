@extends('header')
@section('content')
<div style="width:90%; top:0; bottom: 0; left: 0; right: 0; margin: auto;">

        <div class="card mt-3 mb-3" style="padding: 10px">
            <h3 class="card-title">Notification</h3>
            <a href={{url("/clearNotif/".session()->get('cust_id'))}} class="btn btn-danger">Clear Notification</a>
                <table class="table table-striped table-bordered mt-3">

                    <tbody>
                        @foreach ($dataNotif as $notif)
                        <tr>
                            <td>
                                {{$notif->message}}
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {{ $dataNotif->links("pagination::bootstrap-4") }}

            </div>
        </div>
</div>
@endsection
