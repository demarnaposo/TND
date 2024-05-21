$(document).ready(function() {

$('#kodesurat_id').on('change', function() {
        var kodesuratID = document.getElementById('kodesurat_id');

        $('#series').val(kodesuratID.options[kodesuratID.selectedIndex].text);
    });
});

function getTahunAktif() {
    let r_aktif     = document.getElementById('retensi_aktif').value;
    var t_surat  = document.getElementById('tanggal').value;
    
    var d           = new Date(t_surat);
    let dt          = d.getFullYear();

    var tr_aktif    = parseInt(dt)+ parseInt(r_aktif);

    $('#tahun_aktif').val(tr_aktif);
    $('#retensi_inaktif').val('');
    $('#tahun_inaktif').val('');
}

function getTahunInaktif() {
    let r_inaktif   = document.getElementById('retensi_inaktif').value;
    var t_aktif     = document.getElementById('tahun_aktif').value;

    var tr_inaktif  = parseInt(r_inaktif)+ parseInt(t_aktif);

    $('#tahun_inaktif').val(tr_inaktif);
}