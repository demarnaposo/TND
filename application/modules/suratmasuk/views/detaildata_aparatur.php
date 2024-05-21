<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratmasuk/inbox/surat') ?>">Surat Masuk</a></li>
    <li class="active">Detail Data Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Detail Data Surat</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->

<!--BUILD QUERY-->
<?php
$jabatanid=$this->session->userdata('jabatan_id');
$suratid= $this->uri->segment(4);
$cekselesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid, 'status' => 'Selesai'))->num_rows();
$qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid));
// Lembaran Pengembalian
$opdid=$this->session->userdata('opd_id');
$lembardikembalikan = $this->db->query("
SELECT * FROM disposisi_suratmasuk
LEFT JOIN aparatur
ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
WHERE aparatur.opd_id = '$opdid'
AND disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status='Riwayat'
")->num_rows();

// Pengembalian
$pengembalian = $this->db->query("
    SELECT * FROM disposisi_suratmasuk a
    WHERE a.aparatur_id = '$jabatanid'
    AND a.suratmasuk_id = '$suratid' AND a.status='Dikembalikan'
")->num_rows();
foreach($index as $key => $cd){
?>
<!--BUILD QUERY-->
<div class="page-content-wrap">                
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-primary" role="alert">
              <h4><b>Keterangan :</b> Tanda <i class="fa fa-check"></i> artinya surat sudah diteruskan/didisposisikan</h4>
            </div>
            <?php if($this->session->userdata('level') == 6){?>
            <div class="alert alert-warning" role="alert">
              <h4><b>Catatan :</b> Jika di riwayat terusan sudah tertera nama jabatan yang ingin dituju, maka klik tombol selesai disposisi untuk menyimpan surat di menu riwayat terusan</h4>
            </div>
            <?php } ?>
        </div>
        <div class="col-lg-6">
        <a href="<?= $this->agent->referrer(); ?>" class="btn btn-primary">Kembali</a>
                        <?php
                         if($lembardikembalikan != 0){
                        ?>
                        <a href="javascript:void()" data-toggle="modal" data-target="#modalLembarDisposisi<?php echo $cd->suratmasuk_id ?>" title="Lihat Lembar Disposisi" class="btn btn-warning"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
                        <!-- Modal Fade -->
                        <div class="modal fade" id="modalLembarDisposisi<?php echo $cd->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Lembar Disposisi Surat</h5>
                              </div>
                              <div class="modal-body">
                                <a href="<?php echo site_url('export/lembar_disposisi/'.$suratid) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi" target="_blank" class="btn btn-warning">Lihat Lembar Disposisi</a>
                                <a href="<?php echo site_url('export/lembar_pengembalian/'.$suratid) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi Pengembalian" target="_blank" class="btn btn-danger">Lihat Lembar Diposisi Pengembalian</a>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Modal Fade -->
                        <?php }else{ ?>
                        <a href="<?php echo site_url('export/lembar_disposisi/'.$suratid) ?>" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
                        <?php }
                        ?>
                        <!-- JIKA TIDAK ADA LAMPIRAN LAIN -->
                        <?php if(!empty($cd->lampiran_lain)){ ?>
                            <?php if(substr($cd->lampiran, 0,2) == 'SB'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/biasa/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,2) == 'SE'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/edaran/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,2) == 'SU'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/undangan/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,5) == 'PNGMN'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/pengumuman/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'LAP'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/laporan/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'REK'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/rekomendasi/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'INT'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/instruksi/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'PNG'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/pengantar/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,5) == 'NODIN'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/notadinas/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,2) == 'SK'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/keterangan/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'SPT'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/perintahtugas/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,2) == 'SP'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/perintah/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'IZN'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/izin/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'PJL'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/perjalanan/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'KSA'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/kuasa/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'MKT'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/melaksanakantugas/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'PGL'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/panggilan/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'NTL'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/notulen/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'MMO'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/memo/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,3) == 'LMP'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                                <a href="<?php echo site_url('assets/lampiransurat/lampiran/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                            <?php }elseif(substr($cd->lampiran, 0,2) == 'SL'){ ?>
                                <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                            <?php }else{
                                    // @MpikEgov 12 Juni 2023
                                    if(file_exists(FCPATH."assets/lampiransuratmasuk/".$cd->lampiran)){
                            ?>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$cd->lampiran) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surats</a>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surats</a>
                            <?php   }else{ ?>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk1/'.$cd->lampiran) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surats</a>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk1/'.$cd->lampiran_lain) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surats</a>
                        <?php } // Selsai @Mpikegov 12 Juni 2023
                         } }else{?>
                        <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                          <?php if(substr($cd->lampiran, 0,2) == 'SB'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,2) == 'SE'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,2) == 'SU'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,5) == 'PNGMN'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'LAP'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'REK'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'INT'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'PNG'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,5) == 'NODIN'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,2) == 'SK'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'SPT'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,2) == 'SP'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'IZN'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'PJL'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'KSA'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'MKT'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'PGL'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'NTL'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'MMO'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,3) == 'LMP'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }elseif(substr($cd->lampiran, 0,2) == 'SL'){ ?>
                            <a href="<?php echo site_url('uploads/SIGNED/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }else{ 
                            // @MpikEgov 12 Juni 2023
                            if(file_exists(FCPATH."assets/lampiransuratmasuk/".$cd->lampiran)){
                        ?>
                            <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php }else{ ?>
                            <a href="<?php echo site_url('assets/lampiransuratmasuk1/'.$cd->lampiran) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <?php } } ?>
                        <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                        
                        <!-- LIHAT LEMBAR DISPOSISI BARU DAN LEMBAR DISPOSISI LAMA-->
                        <?php } ?>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalKembalikan<?php echo $suratid; ?>" titl="Kembalikan Surat" class="btn btn-danger"><i class="fa fa-reply"></i>Kembalikan</a>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalCatatan<?php echo $suratid ?>" class="btn btn-success"><i class="fa fa-check"></i>Diterima</a>
                        <?php if($this->session->userdata('level') == 12){ }else{?>
                        <?php ?>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDisposisi<?php echo $cd->dsuratmasuk_id ?>" title="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
                            <?php if($this->session->userdata('level') == 6){ ?>
                            <form action="<?= site_url('suratmasuk/inbox/disposisi');?>" method="post">
                                <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
                                <input type="hidden" name="suratmasuk_id" value="<?php echo $suratid ?>">
                                <input type="submit" name="selesaidisposisi" value="Selesai Disposisi" class="btn btn-danger" title="Selesai Disposisi" onclick="return confirm('Apakah anda yakin ingin menyelesaikan disposisi ?')"> <!-- Update @Mpik Egov 30/06/2022 -->
                            </form>
                        <?php } } ?>
                            <!-- Modal Dikembalikan -->
                            <div class="modal fade" id="modalKembalikan<?php echo $suratid; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pengembalian Surat</h5>
                                  </div>
                                  <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
                                  <div class="modal-body">
                                        <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
                                        <input type="hidden" name="suratmasuk_id" value="<?php echo $cd->suratmasuk_id ?>">
                                        <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <label class="control-label">Catatan</label>                             
                                        <textarea type="text" name="keterangan" class="form-control"></textarea>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <input type="submit" name="kembalikan" class="btn btn-primary" value="Kembalikan">
                                  </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!-- End Modal Dikembalikan -->
                            
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                    </div>
                </div><br>
                <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                <?php if(substr($cd->lampiran, 0,2) == 'SB'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,2) == 'SE'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,2) == 'SU'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,5) == 'PNGMN'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'LAP'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'REK'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'INT'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'PNG'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,5) == 'NODIN'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,2) == 'SK'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'SPT'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,2) == 'SP'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'IZN'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'PJL'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'KSA'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'MKT'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'PGL'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'NTL'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'MMO'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,3) == 'LMP'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }elseif(substr($cd->lampiran, 0,2) == 'SL'){ ?>
                    <embed src="<?=base_url('uploads/SIGNED/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }else{
                // @MpikEgov 12 Juni 2023
                if(file_exists(FCPATH."assets/lampiransuratmasuk/".$cd->lampiran)){
                ?>
                    <embed src="<?=base_url('assets/lampiransuratmasuk/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php }else{ ?>
                    <embed src="<?=base_url('assets/lampiransuratmasuk1/').$cd->lampiran?>" type="application/pdf" width="100%" height="700px" />
                <?php } //@MpikEgov 12 Juni 2023
                } ?>
                <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
                 <div class="main-card mb-12 card">
                     <div class="card-body">
                         <h3 class="card-title">Riwayat Terusan</h3>
                            <h4 class="card-title">Surat berada di : <br>
                            <!--Build Query - Pemanggulan nama surat berada di-->
                            <?php 
                                $nomor=1;
                                $opdid=$this->session->userdata('opd_id');
                                     $qdinas=$this->db->query("SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id ='$suratid' AND disposisi_suratmasuk.status ='Belum Selesai' AND opd.opd_id NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
                                     
                                     $qketdis = $this->db->query("
                                     SELECT aparatur.nama,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status ='Belum Selesai' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                    
                                    $qketdis1 = $this->db->query("
                                     SELECT aparatur.nama,disposisi_suratmasuk.harap, jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status ='Belum Selesai' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id=$opdid
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                $cekAtasanJabatan = $this->db->get_where('jabatan', array('atasan_id' => $qdisposisi->row_array()['users_id']));
                                if($this->session->userdata('jabatan_id')==5 || $this->session->userdata('jabatan_id')==1 || $this->session->userdata('jabatan_id')==2){
                                    if (empty($cekAtasanJabatan->num_rows())) {
                                        $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                        $atasan_id = $statusTU['atasan_id'];
                                        foreach($qketdis as $key =>$tu){
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$nomor.'. '.$tu->nama.' - '.$tu->nama_jabatan." </b></font><br>";
                                            $nomor++;
                                        }
                                    }else{
                                        foreach ($qketdis as $key => $b) {
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$nomor.'. '.$b->nama_jabatan.' - '.$b->nama_jabatan." </b></font><br>  ";
                                            $nomor++;
                                        }
                                        foreach ($qdinas as $key => $qd) {
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$nomor.'. '.$qd->nama_pd." </b></font><br>  ";
                                            $nomor++;
                                        }
                                    }   
                                }else{
                                    if (empty($cekAtasanJabatan->num_rows())) {
                                        $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                        $atasan_id = $statusTU['atasan_id'];
                                        foreach($qketdis1 as $key =>$tu){
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$nomor.'. '.$tu->nama.' - '.$tu->nama_jabatan." </b></font><br>  ";
                                            $nomor++;
                                        }
                                    }else{
                                        foreach ($qketdis1 as $key => $b) {
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$nomor.'. '.$b->nama.' - '.$b->nama_jabatan." </b></font><br>  ";
                                            $nomor++;
                                        }
                                    }   
                                }
                            ?>
                            <!-- end -->
                            </h4><br>
                            <!--<h4><span class="badge badge-pill badge-success"><i class="fa fa-check"></i> Surat Sudah Diselesaikan</span></h4>-->
                         <div class="scroll-area">
                             <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                 <!--BUILD QUERY - Pemanggilan Detail Riwayat Disposisi-->
                                 <?php
                                 $opdid=$this->session->userdata('opd_id');
                                     $qdinas1=$this->db->query("SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id ='$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND opd.opd_id NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
                                     
                                     $qketdis1 = $this->db->query("
                                     SELECT aparatur.nama,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                    
                                    $qketdis2 = $this->db->query("
                                     SELECT aparatur.nama,disposisi_suratmasuk.harap, jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id=$opdid
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                 if($this->session->userdata('jabatan_id') == 5){
                                    foreach ($qketdis1 as $key => $kd) {
                                ?>
                                <div class="vertical-timeline-item vertical-timeline-element">
                                     <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                         <div class="vertical-timeline-element-content bounce-in">
                                             <?php if($kd->status =='Selesai Disposisi'){?>
                                                <p style="font-size:14px; margin-left:15px;">
                                                    <b><?php echo $kd->nama; ?> <i class="fa fa-check"></i></b><br><?php echo $kd->nama_jabatan; ?>
                                                    <br><b>Dengan Harap Hormat : </b><?php echo $kd->harap; ?><br>
                                                </p>
                                            <?php }elseif($kd->status =='Selesai'){?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama_jabatan; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b><br><?php echo $kd->nama; ?></p>
                                             <?php }else{?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?></b><br><?php echo $kd->nama_jabatan; ?></p>
                                             <?php } ?>
                                            <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal;?><br><?php $time=strtotime($kd->waktudisposisi); echo date("h:ia",$time);?></span>
                                         </div>
                                     </div>
                                 </div>
                                 <?php }
                                 foreach($qdinas1 as $key => $qd){
                                 ?>
                                 <div class="vertical-timeline-item vertical-timeline-element">
                                     <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                         <div class="vertical-timeline-element-content bounce-in">
                                             <?php if($kd->status =='Selesai Disposisi'){?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?> <i class="fa fa-check"></i></b></p>
                                            <?php }elseif($kd->status =='Selesai'){?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b></p>
                                             <?php }else{?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?></b></p>
                                             <?php } ?>
                                            <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal;?><br><?php $time=strtotime($kd->waktudisposisi); echo date("h:ia",$time);?></span>
                                         </div>
                                     </div>
                                 </div>
                                <?php
                                 }
                                 }else{
                                foreach ($qketdis2 as $key => $kd) {
                                 ?>
                                 <div class="vertical-timeline-item vertical-timeline-element">
                                     <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                         <div class="vertical-timeline-element-content bounce-in">
                                             <?php if($kd->status =='Selesai Disposisi'){?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <i class="fa fa-check"></i></b><br><?php echo $kd->nama_jabatan; ?><br><b>Dengan Harap Hormat :</b> <?php echo $kd->harap; ?></p>
                                            <?php }elseif($kd->status =='Selesai'){?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b><br><?php echo $kd->nama_jabatan; ?></p>
                                             <?php }else{?>
                                                <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?></b><br><?php echo $kd->nama_jabatan; ?></p>
                                             <?php } ?>
                                            <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal;?><br><?php $time=strtotime($kd->waktudisposisi); echo date("h:ia",$time);?></span>
                                         </div>
                                     </div>
                                 </div>
                                 <?php } }?>
                             </div>
                             <br><h4><span class="badge badge-pill badge-primary"><i class="fa fa-check"></i> Catatan/Keterangan :</span></h4>
                             <?php 
                                if($this->session->userdata('jabatan_id')==5){
                                    $ketdisposisi = $this->db->query("
                                 SELECT aparatur.nama,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 18 AND opd.opd_induk=4 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
                                }else{
                                 $ketdisposisi = $this->db->query("
                                 SELECT aparatur.nama,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();   
                                }
                                foreach ($ketdisposisi as $key => $d) {
                                    if (!empty($d->keterangan)) {
                            ?>
                             <h5>- <b><?= $d->nama?> (<?= $d->nama_jabatan?>)</b> : <br><u><?= $d->keterangan?></u></h5>
                            <?php
                                    }
                                } 
                                $catatan=$this->db->query("SELECT * FROM disposisi_suratmasuk
                                LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id  ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
                                foreach ($catatan as $key => $c) {
                                    if (!empty($c->catatan)) {
                            ?>
                            <h5>- <b><?= $c->nama?> (<?= $c->nama_jabatan?>)</b> : <br><u><?= $c->catatan?></u></h5>
                            <?php 
                                    }
                                }
                            ?>
                         </div>
                     </div>
                 </div>
             </div>
    </div>
</div>

<?php if($this->session->userdata('users_id') == 1414 || $this->session->userdata('users_id') == 2131 || $this->session->userdata('users_id') == 2310 || $this->session->userdata('users_id') == 2311 || $this->session->userdata('users_id') == 5404) { ?>
<!-- Jika Login SEKDA -->
<!-- Modal Disposisi -->
<div class="modal fade" id="modalDisposisi<?php echo $cd->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
      </div>
      <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-body">
              <div class="alert alert-warning" role="alert">
                  <h5><b>Keterangan</b><br><b><font style="size:30px;color:red;">*</font></b> Wajib diisi<br><b><font style="size:30px;color:red;">**</font></b> Wajib diisi Pilih Salah Satu/Keduanya, Sesuai Keperluan</h5>
              </div>
            <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
            <input type="hidden" name="suratmasuk_id" value="<?php echo $suratid ?>">
            <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                <center><label class="control-label">Perangkat Daerah Tujuan<b><font style="size:30px;color:red;">**</font></b></label></center>
                <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                    <?php foreach ($opd as $key1 => $pdt) { 
                        $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$pdt->atasan_id'");
                        foreach($query->result() as $ky => $q){
                    ?> 
                        <option value="<?php echo $pdt->jabatan_id ?>"><?php echo $pdt->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                    <?php } }?>
                </select>
                <p style="color:red;"><b>Keterangan :</b> <u>Pilihlah perangkat daerah tujuan jika ingin meneruskan surat kepada perangkat daerah yang lain</u></p>
                <center><label class="control-label">Aparatur Tujuan<b><font style="size:30px;color:red;">**</font></b></label></center>
                <input id="chkall" type="checkbox"  style="width:17px; height:17px;"> Pilih Semua
                <select multiple name="aparatur_id[]" id="aparatur" class="form-control select" data-live-search="true">
                    <!-- <option value=""> Pilih Aparatur </option> -->
                    <?php foreach ($aparatur as $key2 => $a) { ?>
                        <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                    <?php } ?>
                </select><br><br>
            <center><label class="control-label">Dengan Hormat harap<b><font style="size:30px;color:red;">*</font></b></label></center>
            <select name="harap" class="form-control select" data-live-search="true" required>
                    <option value=""> Tidak Ada Yang Dipilih </option>
                    <option value="Saya Hadir"> Saya Hadir</option>
                    <option value="Hadiri/Wakili"> Hadiri/Wakili</option>
                    <option value="Pertimbangkan"> Pertimbangkan</option>
                    <option value="Pedomani"> Pedomani</option>
                    <option value="Sarankan"> Sarankan</option>
                    <option value="Koordinasikan"> Koordinasikan</option>
                    <option value="Tindak Lanjuti"> Tindak Lanjuti</option>
                    <option value="Selesaikan"> Selesaikan</option>
                    <option value="Siapkan"> Siapkan</option>
                    <option value="Untuk Diketahui"> Untuk Diketahui</option>
                    <option value="Untuk Diproses"> Untuk Diproses</option>
                    <option value="Menghadap Saya"> Menghadap Saya</option>
                    <option value="Laporakan Hasilnya"> Laporakan Hasilnya</option>
                    <option value="Monitor Pelaksanaannya"> Monitor Pelaksanaannya</option>
                    <option value="Sampaikan Kepada ybs"> Sampaikan Kepada ybs</option>
                    <option value="Agendakan"> Agendakan</option>
                    <option value="Lain - Lain"> Lain - Lain</option>
            </select><br><br>
            <center><label class="control-label">Tanggal<b><font style="size:30px;color:red;">*</font></b></label></center>
            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
            <br>
            <center><label class="control-label">Keterangan<b><font style="size:30px;color:red;">*</font></b></label></center>                           
            <textarea type="text" name="keterangan" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <input type="submit" name="disposisi" class="btn btn-primary" value="Disposisi">
      </div>
      </form>
    </div>
  </div>
</div>
 <?php }elseif($this->session->userdata('level') == 26){ ?>
         <!-- Jika Login SEKDA -->
<!-- Modal Disposisi -->
<div class="modal fade" id="modalDisposisi<?php echo $cd->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
      </div>
      <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-body">
              <div class="alert alert-warning" role="alert">
                  <h5><b>Keterangan</b><br><b><font style="size:30px;color:red;">*</font></b> Wajib diisi<br><b><font style="size:30px;color:red;">**</font></b> Wajib diisi Pilih Salah Satu/Keduanya, Sesuai Keperluan</h5>
              </div>
            <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
            <input type="hidden" name="suratmasuk_id" value="<?php echo $suratid ?>">
            <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                <center><label class="control-label">Pilih Kelurahan<b><font style="size:30px;color:red;">**</font></b></label></center>
                <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                    <?php foreach ($opd as $key1 => $pdt) { 
                        $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$pdt->atasan_id'");
                        foreach($query->result() as $ky => $q){
                    ?> 
                        <option value="<?php echo $pdt->jabatan_id ?>"><?php echo $pdt->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                    <?php } }?>
                </select>
                <p style="color:red;"><b>Keterangan :</b> <u>Pilihlah perangkat daerah tujuan jika ingin meneruskan surat kepada perangkat daerah yang lain</u></p>
                <center><label class="control-label">Aparatur Tujuan<b><font style="size:30px;color:red;">**</font></b></label></center>
                <input id="chkall" type="checkbox"  style="width:17px; height:17px;"> Pilih Semua
                <select multiple name="aparatur_id[]" id="aparatur" class="form-control select" data-live-search="true">
                    <!-- <option value=""> Pilih Aparatur </option> -->
                    <?php foreach ($aparatur as $key2 => $a) { ?>
                        <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                    <?php } ?>
                </select><br><br>
            <center><label class="control-label">Dengan Hormat harap<b><font style="size:30px;color:red;">*</font></b></label></center>
            <select name="harap" class="form-control select" data-live-search="true" required>
                    <option value=""> Tidak Ada Yang Dipilih </option>
                    <option value="Saya Hadir"> Saya Hadir</option>
                    <option value="Hadiri/Wakili"> Hadiri/Wakili</option>
                    <option value="Pertimbangkan"> Pertimbangkan</option>
                    <option value="Pedomani"> Pedomani</option>
                    <option value="Sarankan"> Sarankan</option>
                    <option value="Koordinasikan"> Koordinasikan</option>
                    <option value="Tindak Lanjuti"> Tindak Lanjuti</option>
                    <option value="Selesaikan"> Selesaikan</option>
                    <option value="Siapkan"> Siapkan</option>
                    <option value="Untuk Diketahui"> Untuk Diketahui</option>
                    <option value="Untuk Diproses"> Untuk Diproses</option>
                    <option value="Menghadap Saya"> Menghadap Saya</option>
                    <option value="Laporakan Hasilnya"> Laporakan Hasilnya</option>
                    <option value="Monitor Pelaksanaannya"> Monitor Pelaksanaannya</option>
                    <option value="Sampaikan Kepada ybs"> Sampaikan Kepada ybs</option>
                    <option value="Agendakan"> Agendakan</option>
                    <option value="Lain - Lain"> Lain - Lain</option>
            </select><br><br>
            <center><label class="control-label">Tanggal<b><font style="size:30px;color:red;">*</font></b></label></center>
            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
            <br>
            <center><label class="control-label">Keterangan<b><font style="size:30px;color:red;">*</font></b></label></center>                           
            <textarea type="text" name="keterangan" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <input type="submit" name="disposisi" class="btn btn-primary" value="Disposisi">
      </div>
      </form>
    </div>
  </div>
</div>
 <?php }else{ ?>                     

<!-- Modal Disposisi -->
<div class="modal fade" id="modalDisposisi<?php echo $cd->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
      </div>
      <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-body">
            <div class="col-lg-12">
              <div class="alert alert-warning" role="alert">
                  <h5>Keterangan <b><font style="size:30px;color:red;">*</font></b> : Wajib diisi</h5>
              </div>
            </div>
            <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
            <input type="hidden" name="suratmasuk_id" value="<?php echo $suratid ?>">
            <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
            <input type="hidden" name="nama_pengirim" value="<?php echo $this->session->userdata('nama') ?>">
                <label class="control-label">Aparatur Tujuan<font style="size:30px;color:red;">*</font></b></label>
                <input id="chkall" type="checkbox"  style="width:17px; height:17px;"> Pilih Semua
                <select multiple name="aparatur_id[]" id="aparatur" class="form-control select" data-live-search="true" required>
                    <!-- <option value=""> Pilih Aparatur </option> -->
                    <?php foreach ($aparatur as $key => $a) { ?>
                        <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                    <?php } ?>
                </select> <br><br>
            <label class="control-label">Dengan Hormat Harap<font style="size:30px;color:red;">*</font></b></label>
            <select name="harap" class="form-control select" data-live-search="true" required>
                    <option value=""> Tidak Ada Yang Dipilih </option>
                    <option value="Saya Hadir"> Saya Hadir</option>
                    <option value="Hadiri/Wakili"> Hadiri/Wakili</option>
                    <option value="Pertimbangkan"> Pertimbangkan</option>
                    <option value="Pedomani"> Pedomani</option>
                    <option value="Sarankan"> Sarankan</option>
                    <option value="Koordinasikan"> Koordinasikan</option>
                    <option value="Tindak Lanjuti"> Tindak Lanjuti</option>
                    <option value="Selesaikan"> Selesaikan</option>
                    <option value="Siapkan"> Siapkan</option>
                    <option value="Untuk Diketahui"> Untuk Diketahui</option>
                    <option value="Untuk Diproses"> Untuk Diproses</option>
                    <option value="Menghadap Saya"> Menghadap Saya</option>
                    <option value="Laporakan Hasilnya"> Laporakan Hasilnya</option>
                    <option value="Monitor Pelaksanaannya"> Monitor Pelaksanaannya</option>
                    <option value="Sampaikan Kepada ybs"> Sampaikan Kepada ybs</option>
                    <option value="Agendakan"> Agendakan</option>
                    <option value="Lain - Lain"> Lain - Lain</option>
            </select><br><br>
            <label class="control-label">Tanggal<font style="size:30px;color:red;">*</font></b></label>
            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
            <br>
            <label class="control-label">Keterangan<font style="size:30px;color:red;">*</font></b></label>                             
            <textarea type="text" name="keterangan" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <input type="submit" name="disposisi" class="btn btn-primary" value="Disposisi">
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Disposisi -->
<?php }?>

<!-- MODAL SURAT DITERIMA-->
<div class="modal fade" id="modalCatatan<?php echo $suratid ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
      </div>
      <form action="<?= site_url('suratmasuk/inbox/disposisi');?>" method="post">
      <div class="modal-body">
            <label class="control-label">Catatan</label>                             
            <textarea type="text" name="catatan" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
          <input type="hidden" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
          <input type="hidden" name="suratmasuk_id" value="<?php echo $cd->suratmasuk_id ?>">
        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-primary" value="Diterima" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
      </div>
      </form>
    </div>
  </div>
</div>
<!--END MODAL SURAT DITERIMA-->
<?php } ?>

<!-- Select all -->
<script type="text/javascript">
$(document).ready(function() {
    $("#chkall").click(function(){
        if($("#chkall").is(':checked')){
            $("#aparatur > option").prop("selected", "selected");
            $("#aparatur").trigger("change");
        } else {
            $("#aparatur > option").removeAttr("selected");
            $("#aparatur").trigger("change");
        }
    });
});
</script>
<!-- Select all -->
