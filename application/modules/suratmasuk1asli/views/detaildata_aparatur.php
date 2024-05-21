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
$get=$this->db->query("SELECT surat_masuk.lampiran, surat_masuk.lampiran_lain,surat_masuk.opd_id,disposisi_suratmasuk.dsuratmasuk_id FROM surat_masuk LEFT JOIN disposisi_suratmasuk ON disposisi_suratmasuk.suratmasuk_id=surat_masuk.suratmasuk_id WHERE surat_masuk.suratmasuk_id='$suratid'")->row_array();
$cekselesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid, 'status' => 'Selesai'))->num_rows();
$qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $suratid));
// Lembaran Pengembalian
$opdid=$get['opd_id'];
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
foreach($cekdisposisi as $key => $cd){
?>
<!--BUILD QUERY-->

<div class="page-content-wrap">                
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                    <div class="col-lg-9">
                        <a href="<?= $this->agent->referrer(); ?>" class="btn btn-primary">Kembali</a>
                        <?php if(empty($get['lampiran_lain'])){ echo ""; }else{?>
                            <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$cd->lampiran_lain) ?>" class="btn btn-success"><i class="fa fa-file-text-o"></i>Lihat Lampiran Surat</a>
                        <?php }
                         if($lembardikembalikan != 0){
                        ?>
                        <a href="javascript:void()" data-toggle="modal" data-target="#modalLembarDisposisi<?php echo $cd->suratmasuk_id ?>" title="Lihat Lembar Disposisi" class="btn btn-warning"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
                        <a href="<?php echo site_url('export/tandaterima/'.$suratid) ?>" class="btn btn-danger"><i class="fa fa-file-text-o"></i>Tanda Terima</a>
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
                        <a href="<?php echo site_url('export/lembar_disposisi/'.$suratid) ?>" class="btn btn-warning"><i class="fa fa-file-text-o"></i>Lembar Disposisi</a>
                        <?php }
                        if($cekdisposisi->num_rows() == 1){
                        ?>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalCatatan<?php echo $suratid ?>" class="btn btn-success"><i class="fa fa-check"></i>Diterima</a>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalKembalikan<?php echo $suratid; ?>" titl="Kembalikan Surat" class="btn btn-danger"><i class="fa fa-reply"></i>Kembalikan</a>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDisposisi<?php echo $cd->dsuratmasuk_id ?>" title="Disposisi Surat" class="btn btn-info"><i class="fa fa-mail-forward"></i>Disposisi</a>
                        <?php } ?>
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
                            
                    </div>
                </div><br>
                <embed src="<?=base_url('assets/lampiransuratmasuk/').$cd->lampiran; ?>" type="application/pdf" width="100%" height="700px" />
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

<?php if($this->session->userdata('users_id') == 1414 || $this->session->userdata('users_id') == 2131 || $this->session->userdata('users_id') == 2310 || $this->session->userdata('users_id') == 2311){ ?>
<!-- Jika Login SEKDA -->
<!-- Modal Disposisi -->
<div class="modal fade" id="modalDisposisi<?php echo $cd->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
      </div>
      <form action="<?php echo site_url('suratmasuk1/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-body">
            <input type="" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
            <input type="" name="suratmasuk_id" value="<?php echo $suratid ?>">
            <input type="" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                <center><label class="control-label">Perangkat Daerah Tujuan</label></center>
                <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                    <?php foreach ($opd as $key1 => $pdt) { 
                        $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$pdt->atasan_id'");
                        foreach($query->result() as $ky => $q){
                    ?> 
                        <option value="<?php echo $pdt->jabatan_id ?>"><?php echo $pdt->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                    <?php } }?>
                </select>
                <p style="color:red;"><b>Keterangan :</b> <u>Pilihlah perangkat daerah tujuan jika ingin meneruskan surat kepada perangkat daerah yang lain</u></p>
                <center><label class="control-label">Aparatur Tujuan</label></center>
                <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                    <option value=""> Pilih Aparatur </option>
                    <?php foreach ($aparatur as $key2 => $a) { ?>
                        <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                    <?php } ?>
                </select><br><br>
            <center><label class="control-label">Dengan Hormat harap</label></center>
            <select name="harap[]" class="form-control select" data-live-search="true" required>
                    <option value=""> Tidak Ada Yang Dipilih </option>
                    <option value="Saya Hadir"> Saya Hadir</option>
                    <option value="Hadiri/Wakili"> Hadiri/Wakili</option>
                    <option value="Pertimbangkan"> Pertimbangkan</option>
                    <option value="Pedomani"> Pedomani</option>
                    <option value="Sarankan"> Sarankan</option>
                    <option value="Kordinasikan"> Kordinasikan</option>
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
            <center><label class="control-label">Tanggal</label></center>
            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
            <br>
            <center><label class="control-label">Keterangan</label></center>                           
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
      <form action="<?php echo site_url('suratmasuk1/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
      <div class="modal-body">
            <input type="" name="dsuratmasuk_id" value="<?php echo $cd->dsuratmasuk_id ?>">
            <input type="" name="suratmasuk_id" value="<?php echo $suratid ?>">
            <input type="" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                <label class="control-label">Aparatur Tujuan</label>
                <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true" required>
                    <option value=""> Pilih Aparatur </option>
                    <?php foreach ($aparatur as $key => $a) { ?>
                        <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                    <?php } ?>
                </select> <br><br>
            <label class="control-label">Dengan Hormat harap</label>
            <select name="harap[]" class="form-control select" data-live-search="true" required>
                    <option value=""> Tidak Ada Yang Dipilih </option>
                    <option value="Saya Hadir"> Saya Hadir</option>
                    <option value="Hadiri/Wakili"> Hadiri/Wakili</option>
                    <option value="Pertimbangkan"> Pertimbangkan</option>
                    <option value="Pedomani"> Pedomani</option>
                    <option value="Sarankan"> Sarankan</option>
                    <option value="Kordinasikan"> Kordinasikan</option>
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
            <label class="control-label">Tanggal</label>
            <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
            <br>
            <label class="control-label">Keterangan</label>                             
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
      <form action="<?= site_url('suratmasuk1/inbox/disposisi');?>" method="post">
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