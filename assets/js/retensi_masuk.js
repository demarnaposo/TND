$(document).ready(function() {

$('#kodesurat_id').on('change', function() {
        var kodesuratID = document.getElementById('kodesurat_id');

        $('#series').val(kodesuratID.options[kodesuratID.selectedIndex].text);
    });
});
    
function clearRetensi() {
    var t_diterima  = new Date(document.getElementById('diterima').value);

    var t_now       = new Date();
    let tn          = t_now.getFullYear();
    let tdt         = t_diterima.getFullYear();
    
    if (tn != tdt) {
    
        $('#retensi_aktif').val('');
        $('#tahun_aktif').val('');
        $('#retensi_inaktif').val('');
        $('#tahun_inaktif').val('');
    }
}

function getTahunAktif() {
    let r_aktif     = document.getElementById('retensi_aktif').value;
    var t_diterima  = document.getElementById('diterima').value;
    
    var d           = new Date(t_diterima);
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