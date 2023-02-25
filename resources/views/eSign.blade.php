@extends('header')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    canvas#canvas{
        margin-top: 5%;
        background: #fff;
        cursor: crosshair;
        border: 1px solid orange;
    }

    button#clear {
        height: 100%;
        border: 1px solid transparent;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }

    button#save {
        height: 100%;
        border: 1px solid transparent;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
    }

</style>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<center>
    <canvas id="canvas" name="canvas"></canvas>
    <div class="clear-btn">
        <form method="POST" action={{ url("/uploadsign/$idModultaken") }}>
            @csrf
            <button id="save" class="btn btn-success" type="submit"> <span>Save</span> </button>
            <button id="clear" type="button" class="btn btn-danger"><span> Clear </span></button>

            <input type="hidden" name="hid" id="hidURL" value="">
        </form>
    </div>
</center>


<script>
    (function() {
        const canvas = document.getElementById("canvas");
        const signaturePad = new SignaturePad(canvas);
        signaturePad.minWidth = 1;
        signaturePad.maxWidth = 1;
        signaturePad.penColor = "rgb(0, 0, 0)";

        const saveBTN = document.getElementById("save");
        saveBTN.addEventListener("click", function() {
            const hidVal = document.getElementById("hidURL");
            hidVal.value = signaturePad.toDataURL();
        });
        const clearBTN = document.getElementById("clear");
        clearBTN.addEventListener("click", function() {
            signaturePad.clear();
        });
    })();
</script>
@endsection
