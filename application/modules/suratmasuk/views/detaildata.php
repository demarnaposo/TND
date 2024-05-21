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
$suratid = $this->uri->segment(4);
$jabatanid = $this->session->userdata('jabatan_id');
$get = $this->db->query("SELECT lampiran, lampiran_lain,opd_id FROM surat_masuk WHERE suratmasuk_id='$suratid'")->row_array();
$cekselesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid, 'status' => 'Selesai'))->num_rows();
$qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid));
$pengembalian = $this->db->query("SELECT * FROM disposisi_suratmasuk a WHERE a.aparatur_id = '$jabatanid' AND a.suratmasuk_id = '$suratid' AND a.status='Dikembalikan'")->num_rows();
// ============ START [UPDATE] Fikri Egov 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================
$riwayatkembali = $this->db->query("SELECT * FROM disposisi_suratmasuk a WHERE a.suratmasuk_id = '$suratid' AND a.status='Riwayat'")->num_rows();
// ============ END [UPDATE] Fikri Egov 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================
?>
<!--BUILD QUERY-->

<div class="page-content-wrap">
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-primary" role="alert">
                <h4><b>Keterangan :</b> Tanda <i class="fa fa-check"></i> artinya surat sudah diteruskan/didisposisikan</h4>
            </div>
        </div>
        <div class="col-lg-6">
            <a href="<?php echo site_url('suratmasuk/surat') ?>" class="btn btn-primary">Kembali</a>
            <?php if (empty($get['lampiran_lain'])) { ?>
                <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                <?php if (substr($get['lampiran'], 0, 2) == 'SB') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SE') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SU') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 5) == 'PNGMN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'LAP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'REK') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'INT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PNG') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 5) == 'NODIN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SK') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'SPT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'IZN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PJL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'KSA') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'MKT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PGL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'NTL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'MMO') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'LMP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <?php } else {
                    // @MpikEgov 9 Juni 2023
                    if (file_exists(FCPATH . "assets/lampiransuratmasuk/" . $get['lampiran'])) {
                    ?>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <?php } else { ?>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk1/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                <?php } //Selesai @MpikEgov 9 Mei 2023
                } ?>
                <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->

            <?php } else { ?>
                <?php if (substr($get['lampiran'], 0, 2) == 'SB') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/biasa/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SE') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/edaran/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SU') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/undangan/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 5) == 'PNGMN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/pengumuman/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'LAP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/laporan/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'REK') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/rekomendasi/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'INT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/instruksi/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PNG') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/pengantar/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 5) == 'NODIN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/notadinas/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SK') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/keterangan/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'SPT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/perintahtugas/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 2) == 'SP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/perintah/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'IZN') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/izin/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PJL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/perjalanandinas/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'KSA') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/kuasa/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'MKT') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/melaksanakantugas/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'PGL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/panggilan/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'NTL') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/notulen/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'MMO') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/memo/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } elseif (substr($get['lampiran'], 0, 3) == 'LMP') { ?>
                    <a href="<?php echo site_url('uploads/SIGNED/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                    <a href="<?php echo site_url('assets/lampiransurat/lampiran/' . $get['lampiran_lain']) ?>" class="btn btn-info" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                    <?php } else {
                    // @MpikEgov 9 Juni 2023
                    if (file_exists(FCPATH . "assets/lampiransuratmasuk/" . $get['lampiran'])) {
                    ?>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk/' . $get['lampiran_lain']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                    <?php } else { ?>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk1/' . $get['lampiran']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lihat Surat</a>
                        <a href="<?php echo site_url('assets/lampiransuratmasuk1/' . $get['lampiran_lain']) ?>" class="btn btn-success" target="_blank"><i class="fa fa-file-text-o"></i>Lampiran Surat</a>
                <?php } //Selesai @MpikEgov 9 Juni 2023
                } ?>
            <?php }
            // ============ START [UPDATE] Fikri Egov 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================
            if ($riwayatkembali >= 1) {
            ?>
                <a href="<?php echo site_url('export/lembar_pengembalian/' . $suratid) ?>" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i>Lembar Disposisi Lama</a>
                <a href="<?php echo site_url('export/lembar_disposisi/' . $suratid) ?>" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i>Lembar Disposisi Baru</a>
            <?php } else { ?>
                <a href="<?php echo site_url('export/lembar_disposisi/' . $suratid) ?>" class="btn btn-warning" target="_blank"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
            <?php } ?>
            <!--============ END [UPDATE] Fikri Egov 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
            <a href="<?php echo site_url('export/tandaterima/' . $suratid) ?>" class="btn btn-danger" target="_blank"><i class="fa fa-file-text-o"></i>Tanda Terima</a>
            <?php
            if ($cekselesai == 0) {
                if ($qdisposisi->num_rows() == 0) { ?>
                    <?php if ($this->session->userdata('level') == 18) { ?>
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $suratid ?>" title="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
                    <?php } else { ?>
                        <a href="<?php echo site_url('suratmasuk/surat/disposisi/' . $suratid) ?>" titl="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
                <?php }
                }
            }
            //============ START [UPDATE] Fikri Egov Perubahan >= 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================
            if ($pengembalian >= 1) {
                //============ START [UPDATE] Fikri Egov 10 Feb 2022 ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================
                ?>
                <a href="<?php echo site_url('suratmasuk/surat/disposisi/' . $suratid) ?>" titl="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
            <?php } ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                    <?php if (substr($get['lampiran'], 0, 2) == 'SB') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 2) == 'SE') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 2) == 'SU') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 5) == 'PNGMN') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'LAP') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'REK') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'INT') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'PNG') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 5) == 'NODIN') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 2) == 'SK') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'SPT') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 2) == 'SP') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'IZN') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'PJL') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'KSA') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'MKT') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'PGL') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'NTL') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'MMO') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 3) == 'LMP') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } elseif (substr($get['lampiran'], 0, 2) == 'SL') { ?>
                        <embed src="<?= base_url('uploads/SIGNED/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                        <?php } else {
                        // @MpikEgov 9 Juni 2023
                        if (file_exists(FCPATH . "assets/lampiransuratmasuk/" . $get['lampiran'])) {
                        ?>
                            <embed src="<?= base_url('assets/lampiransuratmasuk/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                        <?php } else { ?>
                            <embed src="<?= base_url('assets/lampiransuratmasuk1/') . $get['lampiran'] ?>" type="application/pdf" width="100%" height="700px" />
                    <?php } //Selesai @Mpik Egov 9 Juni 2023
                    } ?>
                    <!-- Fikri Egov 26022022 - 9:31 Kondisi Preview Surat dari Surat TND -->
                    <a href="<?php echo site_url('suratmasuk/surat/delete/' . $suratid) ?>" onclick="return confirm('Apakah anda yakin akan menghapus?')" class="btn btn-danger"><i class="fa fa-file-text-o"></i>Hapus Data</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="main-card mb-12 card">
                <div class="card-body">
                    <h3 class="card-title">Riwayat Terusan</h3>
                    <h4 class="card-title">Surat berada di :<br>
                        <!--Build Query - Pemanggilan nama surat berada di-->
                        <?php
                        $queryDinas = $this->db->query("
                                SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id ='$suratid' AND disposisi_suratmasuk.status ='Belum Selesai' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                 AND aparatur.statusaparatur='Aktif'
                                 ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();

                        $queryAdminTuSekda = $this->db->query("
                                SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id ='$suratid' AND disposisi_suratmasuk.status ='Belum Selesai' AND users.level_id != 4 AND users.level_id != 18 AND opd.opd_id IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                 AND aparatur.statusaparatur='Aktif'
                                 ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
                        $nomor = 1;
                        $cekAtasanJabatan = $this->db->get_where('jabatan', array('atasan_id' => $qdisposisi->row_array()['users_id']));
                        if ($this->session->userdata('jabatan_id') == 1473) {
                            if (empty($cekAtasanJabatan->num_rows())) {
                                $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                $atasan_id = $statusTU['atasan_id'];
                                foreach ($queryAdminTuSekda as $key => $tu) {
                                    echo "<font style='font-size:14px; color:#598eff;'><b>" . $nomor . '. ' . $tu->nama . ' - ' . $tu->nama_jabatan . " </b></font><br>";
                                    $nomor++;
                                }
                            } else {
                                foreach ($queryAdminTuSekda as $key => $b) {
                                    echo "<font style='font-size:14px; color:#598eff;'><b>" . $nomor . '. ' . $b->nama . ' - ' . $b->nama_jabatan . " </b></font><br>  ";
                                    $nomor++;
                                }
                                foreach ($queryDinas as $key => $qd) {
                                    echo "<font style='font-size:14px; color:#598eff;'><b>" . $nomor . '. ' . $qd->nama_pd . " </b></font><br>  ";
                                    $nomor++;
                                }
                            }
                        } else {
                            if (empty($cekAtasanJabatan->num_rows())) {
                                $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                $atasan_id = $statusTU['atasan_id'];
                                foreach ($queryDinas as $key => $tu) {
                                    echo "<font style='font-size:14px; color:#598eff;'><b>" . $nomor . '. ' . $tu->nama . ' - ' . $tu->nama_jabatan . " </b></font><br>  ";
                                    $nomor++;
                                }
                            } else {
                                foreach ($queryDinas as $key => $b) {
                                    echo "<font style='font-size:14px; color:#598eff;'><b>" . $nomor . '. ' . $b->nama . ' - ' . $b->nama_jabatan . " </b></font><br>  ";
                                    $nomor++;
                                }
                            }
                        }
                        ?>
                        <!-- end -->
                    </h4><br>
                    <div class="scroll-area">
                        <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                            <!--BUILD QUERY - Pemanggilan Detail Riwayat Disposisi-->
                            <?php
                            if ($this->session->userdata('jabatan_id') == 1473) {
                                $qdinas = $this->db->query("SELECT aparatur.nama,opd.nama_pd,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id ='$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND opd.opd_id NOT IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY opd.opd_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();

                                $qketdis = $this->db->query("
                                     SELECT jabatan.atasan_id,users.level_id as lvlid,aparatur.nama,disposisi_suratmasuk.harap, opd.opd_induk,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi FROM disposisi_suratmasuk
                                     LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                     LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                     LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                     LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                     WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 18 AND opd.opd_id IN (2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19)
                                     AND aparatur.statusaparatur='Aktif'
                                     GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                foreach ($qketdis as $key => $kd) {
                            ?>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <?php if ($kd->status == 'Selesai Disposisi') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <i class="fa fa-check"></i></b><br><?php echo $kd->nama_jabatan; ?><br><b><?php echo $kd->harap; ?></b></p>
                                                <?php } elseif ($kd->status == 'Selesai') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b><br><?php echo $kd->nama_jabatan; ?></p>
                                                    <?php } else {
                                                    if ($kd->lvlid == 4) {
                                                        $jabatanatasan = $this->db->query("SELECT * FROM aparatur LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id where jabatan.jabatan_id='$kd->atasan_id'")->result();
                                                        foreach ($jabatanatasan as $key => $j) {
                                                    ?>
                                                            <p style="font-size:14px; margin-left:15px;"><b><?php echo $j->nama; ?></b><br><?php echo $j->nama_jabatan; ?></p>
                                                        <?php }
                                                    } else { ?>
                                                        <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?></b><br><?php echo $kd->nama_jabatan; ?></p>
                                                <?php }
                                                } ?>
                                                <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal; ?><br><?php $time = strtotime($kd->waktudisposisi);
                                                                                                                                                                        echo date("h:ia", $time); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                foreach ($qdinas as $key => $qd) {
                                ?>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <?php if ($kd->status == 'Selesai Disposisi') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?> <i class="fa fa-check"></i></b></p>
                                                <?php } elseif ($kd->status == 'Selesai') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b></p>
                                                <?php } else { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $qd->nama_pd; ?></b></p>
                                                <?php } ?>
                                                <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal; ?><br><?php $time = strtotime($kd->waktudisposisi);
                                                                                                                                                                        echo date("h:ia", $time); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                $qketdis2 = $this->db->query("
                                     SELECT * FROM disposisi_suratmasuk
                                LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                    ")->result();
                                foreach ($qketdis2 as $key => $kd) {
                                ?>
                                    <div class="vertical-timeline-item vertical-timeline-element">
                                        <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <?php if ($kd->status == 'Selesai Disposisi') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <i class="fa fa-check"></i></b><br><?php echo $kd->nama_jabatan; ?><br><b><?php echo $kd->harap; ?></b></p>
                                                <?php } elseif ($kd->status == 'Selesai') { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?> <span class="badge badge-success"><i class="fa fa-check"></i> Diterima</span></b><br><?php echo $kd->nama_jabatan; ?></p>
                                                <?php } else { ?>
                                                    <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama; ?></b><br><?php echo $kd->nama_jabatan; ?></p>
                                                <?php } ?>
                                                <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal; ?><br><?php $time = strtotime($kd->waktudisposisi);
                                                                                                                                                                        echo date("h:ia", $time); ?></span>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                        <br>
                        <h4><span class="badge badge-pill badge-primary"><i class="fa fa-check"></i> Catatan/Keterangan :</span></h4>
                        <?php
                        if ($this->session->userdata('jabatan_id') == 1473) {
                            $ketdisposisi = $this->db->query("
                                 SELECT aparatur.nama, jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi, aparatur.statusaparatur FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 LEFT JOIN opd ON opd.opd_id=jabatan.opd_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 18 AND opd.opd_induk=4 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
                        } else {
                            $ketdisposisi = $this->db->query("
                                 SELECT aparatur.nama,jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id,disposisi_suratmasuk.status, disposisi_suratmasuk.waktudisposisi, aparatur.statusaparatur FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND disposisi_suratmasuk.status !='Riwayat' AND users.level_id != 4 AND users.level_id != 18 AND aparatur.statusaparatur='Aktif' GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
                        }
                        foreach ($ketdisposisi as $key => $d) {
                            if (!empty($d->keterangan)) {
                        ?>
                                <h5>- <b><?= $d->nama ?> (<?= $d->nama_jabatan ?>)</b> : <u><?= $d->keterangan ?></u></h5>
                            <?php
                            }
                        }
                        $catatan = $this->db->query("SELECT * FROM disposisi_suratmasuk
                                LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
                        foreach ($catatan as $key => $c) {
                            if (!empty($c->catatan)) {
                            ?>
                                <h5>- <b><?= $c->nama_jabatan ?></b> : <u><?= $c->catatan ?></u></h5>
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

<!-- Modal Disposisi -->
<div class="modal fade" id="modalDiposisi<?php echo $suratid ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
            </div>
            <form action="<?php echo site_url('suratmasuk/surat/disposisi') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="suratmasuk_id" value="<?php echo $suratid ?>">
                    <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                    <label class="control-label">Tujuan</label>
                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                        <option value="1">Walikota Kota Bogor</option>
                        <option value="2">Wakil Walikota Kota Bogor</option>
                        <option value="16291">PJ Walikota Kota Bogor</option>
                        <option value="5">Sekretariat Daerah Kota Bogor</option>
                        <option value="1475">Sekpri Walikota</option>
                        <option value="1476">Sekpri Wakil Walikota</option>
                        <option value="1477">Sekpri Sekretariat Daerah</option>
                    </select>
                    <br><br>
                    <label class="control-label">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>" />
                    <br>
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