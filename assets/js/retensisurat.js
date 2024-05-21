function changeDateAccept() {
    var t_diterima = new Date(document.getElementById('diterima').value);
    var retensi_aktif = document.getElementById('retensi_aktif').value;
    var retensi_inaktif = document.getElementById('retensi_inaktif').value;

    let tdt = t_diterima.getFullYear();

    var tr_aktif = parseInt(tdt) + parseInt(retensi_aktif);
    var tr_inaktif = parseInt(tr_aktif) + parseInt(retensi_inaktif);

    $('#tahun_aktif').val(tr_aktif);
    $('#tahun_inaktif').val(tr_inaktif);
}