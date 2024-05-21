<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li><a href="<?php echo site_url('suratmasuk/inbox/surat') ?>">Surat Masuk</a></li>
    <li class="active">Detail Data Surat Riwayat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Detail Data Surat Riwayat</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->

<!--BUILD QUERY-->
<?php
$suratid= $this->uri->segment(4);
$get=$this->db->query("SELECT lampiran, lampiran_lain,opd_id FROM surat_masuk WHERE suratmasuk_id='$suratid'")->row_array();
$cekselesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid, 'status' => 'Selesai'))->num_rows();
$qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid));
?>
<!--BUILD QUERY-->

<div class="page-content-wrap">                
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-9">
                        <a href="<?php echo $this->agent->referrer() ?>" class="btn btn-primary">Kembali</a>
                        <?php if(empty($get['lampiran_lain'])){ echo ""; }else{?>
                            <a href="<?php echo site_url('suratmasuk/surat/add') ?>" class="btn btn-success"><i class="fa fa-file-text-o"></i>Lihat Lampiran Surat</a>
                        <?php }?>
                        <a href="<?php echo site_url('export/lembar_disposisi/'.$suratid) ?>" class="btn btn-warning"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
                        <?php 
                        if($cekselesai == 0){
                        if($qdisposisi->num_rows() == 0){ ?>
                            <a href="<?php echo site_url('suratmasuk/surat/disposisi/'.$suratid) ?>" titl="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
                        <?php } }?>
                    </div>
                </div><br>
                <embed src="<?=base_url('assets/lampiransuratmasuk/').$get['lampiran']?>" type="application/pdf" width="100%" height="700px" />
                </div>
            </div>
        </div>
             <div class="col-md-6">
                 <div class="main-card mb-12 card">
                     <div class="card-body">
                         <h3 class="card-title">Riwayat Terusan</h3>
                         <?php if($cekselesai == 0){ ?>
                            <h4 class="card-title">Surat berada di : 
                            <!--Build Query - Pemanggulan nama surat berada di-->
                            <?php 
                                $cekAtasanJabatan = $this->db->get_where('jabatan', array('atasan_id' => $qdisposisi->row_array()['users_id']));
                                if (empty($cekAtasanJabatan->num_rows())) {
                                    $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                    $atasan_id = $statusTU['atasan_id'];
                                    $beradaTU = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk 
                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                            LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid'
                                            AND disposisi_suratmasuk.status='Belum Selesai'
                                        ")->result();
                                    // $beradaTU = $this->db->query("
                                    //         SELECT * FROM disposisi_suratmasuk 
                                    //         JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                    //         WHERE aparatur.opd_id = '$h->opd_id' 
                                    //         AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                    //         AND disposisi_suratmasuk.aparatur_id = '$atasan_id'
                                    //     ")->row_array();
                                    // echo "<b>".$beradaTU['nama']."</b>";
                                    foreach($beradaTU as $key =>$tu){
                                        echo "<b>".$tu->nama_jabatan.", </b>";
                                    }
                                }else{
                                        $opdid=$get['opd_id'];
                                        $berada = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk 
                                            LEFT JOIN jabatan ON jabatan.jabatan_id=disposisi_suratmasuk.aparatur_id
                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid'
                                            AND disposisi_suratmasuk.status='Belum Selesai'
                                        ")->result();
                                        foreach ($berada as $key => $b) {
                                            echo "<font style='font-size:14px; color:#598eff;'><b>".$b->nama_jabatan."</b></font> || ";
                                        }
                                    // $berada = $this->db->query("
                                    // SELECT * FROM disposisi_suratmasuk 
                                    // JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                    // WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                    // AND disposisi_suratmasuk.status='Belum Selesai'
                                    // ")->result();
                                    // foreach($berada as $key => $qry){
                                    //     echo "<b>".$qry->nama."</b>,";
                                    // }
                                // }
                                }
                            ?>
                            <!-- end -->
                            </h4><br>
                         <?php }else{ ?>
                            <h4><span class="badge badge-pill badge-success"><i class="fa fa-check"></i> Surat Sudah Diselesaikan</span></h4>
                         <?php }?>
                         <div class="scroll-area">
                             <div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                 <!--BUILD QUERY - Pemanggilan Detail Riwayat Disposisi-->
                                 <?php 
                                 $qketdis = $this->db->query("
                                 SELECT jabatan.nama_jabatan,disposisi_suratmasuk.tanggal,disposisi_suratmasuk.keterangan,disposisi_suratmasuk.users_id FROM disposisi_suratmasuk
                                 LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                 LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                 LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                 WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();
                                foreach ($qketdis as $key => $kd) {
                                 ?>
                                 <div class="vertical-timeline-item vertical-timeline-element">
                                     <div> <span class="vertical-timeline-element-icon bounce-in"> <i class="badge badge-dot badge-dot-xl badge-warning"> </i> </span>
                                         <div class="vertical-timeline-element-content bounce-in">
                                             <p style="font-size:14px; margin-left:15px;"><b><?php echo $kd->nama_jabatan; ?></b></p>
                                            <p style="font-size:12px; margin-left:15px;"></p><span class="vertical-timeline-element-date"><?= $kd->tanggal;?></span>
                                         </div>
                                     </div>
                                 </div>
                                 <?php } ?>
                             </div>
                             <br><h4><span class="badge badge-pill badge-primary"><i class="fa fa-check"></i> Catatan/Keterangan :</span></h4>
                             <?php 
                                $ketdisposisi = $this->db->query("
                                    SELECT * FROM disposisi_suratmasuk
                                    LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.users_id
                                    LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id
                                    LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.users_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC
                                ")->result();

                                foreach ($ketdisposisi as $key => $d) {
                                    if (!empty($d->keterangan)) {
                            ?>
                             <h5>- <b><?= $d->nama_jabatan?></b> : <u><?= $d->keterangan?></u></h5>
                            <?php
                                    }
                                } 
                                $catatan=$this->db->query("SELECT * FROM disposisi_suratmasuk
                                LEFT JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                LEFT JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                LEFT JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                WHERE disposisi_suratmasuk.suratmasuk_id = '$suratid' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.aparatur_id ORDER BY disposisi_suratmasuk.dsuratmasuk_id ASC")->result();
                                foreach ($catatan as $key => $c) {
                                    if (!empty($c->catatan)) {
                            ?>
                            <h5>- <b><?= $c->nama_jabatan?></b> : <u><?= $c->catatan?></u></h5>
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
