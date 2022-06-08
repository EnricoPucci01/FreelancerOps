(function(){
    var jenisProyek=document.getElementById("tipeProyek");
    var select=document.getElementById('btnok');
    var table = document.getElementById("modul_table");
    var table_magang=document.getElementById("modul_table_Magang");
    select.addEventListener('click', function(){
        if(jenisProyek.value=="magang"){
            var hid_val=document.getElementById('hid_val');
            var row = table_magang.insertRow(1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            cell1.innerHTML = '<input type="text" name="nama_modul'+hid_val.value+'">';
            cell2.innerHTML = '<textarea name="desc_modul'+hid_val.value+'"></textarea>';
            cell3.innerHTML = '<input type="number" value="0" onkeyup="editTotal()" id="rentang1_bayaran'+hid_val.value+'" name="rentang1_bayaran'+hid_val.value+'">';
            cell4.innerHTML = '<input type="number" value="0" onkeyup="editTotal()" id="rentang2_bayaran'+hid_val.value+'" name="rentang2_bayaran'+hid_val.value+'">';
            cell5.innerHTML = '<input type="date" name="deadline_modul'+hid_val.value+'">';

            hid_val.value= parseInt(hid_val.value)+1;
            console.log("hidval: "+hid_val.value);
        }else{
            var hid_val=document.getElementById('hid_val');
            var row = table.insertRow(1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML = '<input type="text" name="nama_modul'+hid_val.value+'">';
            cell2.innerHTML = '<textarea name="desc_modul'+hid_val.value+'"></textarea>';
            cell3.innerHTML = '<input type="number" value="0" onkeyup="editTotal()" id="bayaran'+hid_val.value+'" name="bayaran'+hid_val.value+'">';
            cell4.innerHTML = '<input type="date" name="deadline_modul'+hid_val.value+'">';

            hid_val.value= parseInt(hid_val.value)+1;
            console.log("hidval: "+hid_val.value);
        }
    }, false);

    var jenisProyek=document.getElementById("tipeProyek");
    jenisProyek.addEventListener('change',function(){
        console.log("jenis: "+jenisProyek.value);
        if(jenisProyek.value=="magang"){
            document.getElementById("rentang_pembayaran_label").style.visibility = "visible";
            document.getElementById("rentang_pembayaran_input").style.visibility = "visible";
            document.getElementById("modul_table_Magang").style.visibility = "visible";

            document.getElementById("rentang_pembayaran_label").style.display = "block";
            document.getElementById("rentang_pembayaran_input").style.display = "block";
            document.getElementById("modul_table_Magang").style.display = "block";

            document.getElementById("modul_table").style.visibility = "hidden";
            document.getElementById("total_pembayaran_label").style.visibility = "hidden";
            document.getElementById("totalPembayaran").style.visibility = "hidden";

            document.getElementById("modul_table").style.display = "none";
            document.getElementById("total_pembayaran_label").style.display = "none";
            document.getElementById("totalPembayaran").style.display = "none";
        }else{
            document.getElementById("rentang_pembayaran_label").style.visibility = "hidden";
            document.getElementById("rentang_pembayaran_input").style.visibility = "hidden";
            document.getElementById("modul_table_Magang").style.visibility = "hidden";

            document.getElementById("rentang_pembayaran_label").style.display = "none";
            document.getElementById("rentang_pembayaran_input").style.display = "none";
            document.getElementById("modul_table_Magang").style.display = "none";

            document.getElementById("modul_table").style.visibility = "visible";
            document.getElementById("total_pembayaran_label").style.visibility = "visible";
            document.getElementById("totalPembayaran").style.visibility = "visible";

            document.getElementById("modul_table").style.display = "block";
            document.getElementById("total_pembayaran_label").style.display = "block";
            document.getElementById("totalPembayaran").style.display = "block";
        }
    },false);

})();


function editTotal(){
    var jenisProyek=document.getElementById("tipeProyek");
    var hidVal=document.getElementById('hid_val');
    var totalBayar=0;
    var totalBayar1=0;
    var totalBayar2=0;
    if(jenisProyek.value=='magang'){
        var rentangpembayaran1= document.getElementById('rentangPembayaran1');
        var rentangpembayaran2= document.getElementById('rentangPembayaran2');

        for (let index = 0; index < hidVal.value; index++) {
            var bayar1=document.getElementById('rentang1_bayaran'+index);
            var bayar2=document.getElementById('rentang2_bayaran'+index);
            totalBayar1=totalBayar1+parseInt(bayar1.value);
            totalBayar2=totalBayar2+parseInt(bayar2.value);
        }
        rentangpembayaran1.value=totalBayar1;
        rentangpembayaran2.value=totalBayar2;
    }else{
        var pembayaran= document.getElementById('totalPembayaran');

        for (let index = 0; index < hidVal.value; index++) {
            var bayar=document.getElementById('bayaran'+index);
            totalBayar=totalBayar+parseInt(bayar.value);
        }
        pembayaran.value=totalBayar;
    }

    console.log("totalBayar: "+totalBayar);
}


    // var select=document.getElementById('tambah');
    // var table = document.getElementById("modul_table");
    // var hidVal=document.getElementById('hid_val');

    // select.addEventListener('click', function(){
    //     for (let index = 0; index < hidVal.value; index++) {
    //         var bayar=document.getElementById('bayaran'+index);
    //         totalBayar=totalBayar+parseInt(bayar.value);
    //     }
    //     console.log("totalBayar: "+totalBayar);

    // }, false);
