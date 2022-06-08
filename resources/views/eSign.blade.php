@extends('header')
@section('content')
<style>
    .kbw-signature { width: 100%; height: 200px;}
    #sig canvas{
        width: 100% !important;
        height: auto;
    }
</style>
<center>
    <div class="card mt-3" style="width: 30%">
        <div class="card-header">
            Tanda Tangan Kontrak
        </div>
        <div class="card-body">
            <h5 class="card-title">Masukan Tanda Tangan Di Sini</h5>
            <form method="POST" action={{ url("/uploadsign/$idModultaken") }}>
                @csrf
                <div class="col-md-12">
                    <div id="sig" ></div>
                    <br/>
                    <textarea id="signature64" name="signed" style="display: none"></textarea>
                </div>
                <br/>
                <button type='submit' class="btn btn-success">Simpan</button>
                <button id="clear" type='button' class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>
    </div>
</center>
<script type="text/javascript">
var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG'});
$('#clear').click(function(e) {
    e.preventDefault();
    sig.signature('clear');
    $("#signature64").val('');
});
</script>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
@endsection
