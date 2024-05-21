<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Masuk</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <!-- <h2><span class="fa fa-envelope"></span> Surat Masuk </h2> -->
    <h2><img src="<?= site_url('assets/img/icons/icon-surat-masuk.png')?>" width="30px"> Surat Masuk </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                <div class="row">
                <div class="col-md-9">
                    <a href="<?php echo site_url('suratmasuk/surat/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Surat</a> <br><br>
                </div>
                <?php echo form_open_multipart('suratmasuk/surat')?>
                <div class="col-md-2" align="left">
                    <input type="text" class="form-control" placeholder="Cari Data" name="cari" autofocus>
                </div>
                <div class="col-md-1" align="left">
                    <input type="submit" name="submit" value="Cari" class="btn btn-primary">
                </div>
                <?php echo form_close();?>
                </div>
		    <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>PENERIMA</th>
                                <th>DARI</th>
                                <th>NOMOR SURAT</th>
                                <th>HAL</th>
                                <th>TANGGAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($suratmasuk == NULL){?>
                            <tr>
                                <td colspan="8" align="center">Tidak Ada Data</td>
                            </tr>
                            <?php
                            }else{
                                foreach ($suratmasuk as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo ++$start; ?></td>
                                <td><b><?php echo $h->penerima; ?></b></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo $h->nomor; ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td><b>Tanggal Surat :</b> <?php echo tanggal($h->tanggal) ?><br><b>Tanggal Diterima :</b> <?php echo tanggal($h->diterima) ?></td>
                                <td>
                                    <?php 
                                        $cekSelesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Selesai'))->num_rows();
                                        $dikembalikan = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Dikembalikan'))->num_rows();
                                        // $dikembalikan = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id,'status'=> 'Dikembalikan'));
                                        $qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id));
                                        
                                        if ($cekSelesai == 0) {
                                            if ($dikembalikan == 0) {
                                                if ($qdisposisi->num_rows() == 0) {
                                    ?>

                                                <p style='color:red; text-align:center;'> SURAT BELUM DITERUSKAN</p>

                                        <?php }else{ ?>
                                            <center><a href="javascript:void()" data-toggle="modal" data-target="#modalDisposisi<?php echo $h->suratmasuk_id ?>" title="Lihat Disposisi" class="btn btn-info">Lihat</a></center>
                                    <?php 
                                        }
                                        }else{
                                    ?>
                                        <p style='text-align:center;'><font color="red">SURAT DIKEMBALIKAN</font><br><a href="javascript:void()" data-toggle="modal" data-target="#modalKet<?php echo $h->suratmasuk_id ?>" title="Lihat Keterangan" >Klik disini</a> untuk lihat keterangan</p>
                                         <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalKet<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><center>Keterangan Surat</center></h5>
                                          </div>
                                          <div class="modal-body">
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status != 'Riwayat' AND users.level_id != 4 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?> <br>Didisposisikan Tanggal : <?= tanggal($kd->tanggal);?><br>

                                            <?php $nmr++;  } ?>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->
                                    <?php }
                                    }else{ ?>
                                        <p style='color:green; text-align:center;'> SURAT SUDAH DISELESAIKAN </p>
                                    <?php }?>

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDisposisi<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Terusan Surat</h5>
                                          </div>
                                          <div class="modal-body">

                                            Surat berada di
                                            <?php 
                                                $cekAtasanJabatan = $this->db->get_where('jabatan', array('atasan_id' => $qdisposisi->row_array()['users_id']));
                                                if (empty($cekAtasanJabatan->num_rows())) {
                                                    $statusTU = $this->db->get_where('jabatan', array('jabatan_id' => $qdisposisi->row_array()['users_id']))->row_array();
                                                    $atasan_id = $statusTU['atasan_id'];
                                                    $beradaTU = $this->db->query("
                                                            SELECT * FROM disposisi_suratmasuk 
                                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
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
                                                        echo "<b>".$tu->nama.", </b>";
                                                    }
                                                }else{
                                                    foreach ($cekAtasanJabatan->result() as $key => $j) {
                                                        $berada = $this->db->query("
                                                            SELECT * FROM disposisi_suratmasuk 
                                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                            WHERE aparatur.opd_id = '$h->opd_id' 
                                                            AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                            AND disposisi_suratmasuk.aparatur_id = '$j->jabatan_id'
                                                        ")->result();
                                                        foreach ($berada as $key => $b) {
                                                            echo "<b>".$b->nama."</b>, ";
                                                        }
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
                                            <br><br>
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status != 'Riwayat' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?> <br>Didisposisikan Tanggal : <?= tanggal($kd->tanggal);?><br>

                                            <?php $nmr++;  } ?>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->

                                </td>
                                <td align="center">
                                    <?php if(empty($h->lampiran_lain)){
                                    if(substr($h->lampiran,0,2) == 'SU' || substr($h->lampiran,0,2) == 'SB' || substr($h->lampiran,0,2) == 'SE' || substr($h->lampiran,0,5) == 'PNGMN' || substr($h->lampiran,0,3) == 'LAP' || substr($h->lampiran,0,3) == 'REK' || substr($h->lampiran,0,3) == 'INT' || substr($h->lampiran,0,3) == 'PNG' || substr($h->lampiran,0,5) == 'NODIN' || substr($h->lampiran,0,2) == 'SK' || substr($h->lampiran,0,3) == 'SPT' || substr($h->lampiran,0,2) == 'SP' || substr($h->lampiran,0,3) == 'IZN' || substr($h->lampiran,0,3) == 'PJL' || substr($h->lampiran,0,3) == 'KSA' || substr($h->lampiran,0,3) == 'MKT' || substr($h->lampiran,0,3) == 'PGL' || substr($h->lampiran,0,3) == 'NTL' || substr($h->lampiran,0,3) == 'MMO'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->lampiran) ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php }else{ ?>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php } ?>
                                    <?php }else{?>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modalLihat<?php echo $h->suratmasuk_id; ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    
                                    <!-- Modal Status Surat -->
                                    <div class="modal fade" id="modalLihat<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
                                              </div>
                                              <div class="modal-body">
                                                <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Lampiran Surat" target="_blank" class="btn btn-warning"><i class="fa fa-file-text-o"></i> Lihat Surat</a>
                                                <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$h->lampiran_lain) ?>" title="Lihat Lampiran Lainnya" target="_blank" class="btn btn-danger"><i class="fa fa-file-text-o"></i> Lihat Lampiran</a>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Status Surat -->
                                        <?php }?>
                                    <?php
                                        $lembardisposisi = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk
                                            LEFT JOIN aparatur
                                            ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                            WHERE aparatur.opd_id = '$h->opd_id'
                                            AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status='selesai' or disposisi_suratmasuk.status='selesai disposisi'
                                        ")->num_rows();
                                        $lembardikembalikan = $this->db->query("
                                        SELECT * FROM disposisi_suratmasuk
                                        LEFT JOIN aparatur
                                        ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                        WHERE aparatur.opd_id = '$h->opd_id'
                                        AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND disposisi_suratmasuk.status='Riwayat'
                                        ")->num_rows();
                                        $jabatanid=$this->session->userdata('jabatan_id');
                                        $pengembalian = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk a
                                            WHERE a.aparatur_id = '$jabatanid'
                                            AND a.suratmasuk_id = '$h->suratmasuk_id' AND a.status='Dikembalikan'
                                        ")->num_rows();
                                        if (!empty($lembardisposisi)) {
                                            ?>
        
                                            <?php if($lembardikembalikan != 0){?>
                                            | <a href="javascript:void()" data-toggle="modal" data-target="#modalLembarDisposisi<?php echo $h->suratmasuk_id ?>" title="Lihat Lembar Disposisi"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a>
                                            
                                            <!-- Modal Disposisi -->
                                            <div class="modal fade" id="modalLembarDisposisi<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Lembar Disposisi Surat</h5>
                                                  </div>
                                                  <div class="modal-body">
                                                    <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi" target="_blank" class="btn btn-warning">Lihat Lembar Disposisi</a>
                                                    <a href="<?php echo site_url('export/lembar_pengembalian/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi Pengembalian" target="_blank" class="btn btn-danger">Lihat Lembar Diposisi Pengembalian</a>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- End Modal Disposisi -->
                                            
                                            <?php }else{ ?>
                                            | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a>
                                            <?php } ?>
                                            | <a href="<?php echo site_url('export/tandaterima/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Buat Tanda Terima Surat" target="_blank"><span class="fa-stack"><i class="fa fas fa-check fa-stack-2x"></i></span></a>
        
                                    <?php }elseif($pengembalian != 0){?>
                                    <?php }?>
                                     
                                    <?php if ($cekSelesai == 0) { ?>
                                            | <a href="<?php echo site_url('suratmasuk/surat/edit/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a>  | <a href="<?php echo site_url('suratmasuk/surat/delete/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    <?php } ?>

                                    <?php if ($qdisposisi->num_rows() == 0) { ?>

                                        <!-- Untuk Admin TU Umum -->
                                        <?php if($this->session->userdata('jabatan_id') == 600 || $this->session->userdata('jabatan_id') == 611 || $this->session->userdata('jabatan_id') == 622 || $this->session->userdata('jabatan_id') == 633 || $this->session->userdata('jabatan_id') == 644 || $this->session->userdata('jabatan_id') == 655){?>
                                            | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $h->suratmasuk_id ?>" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                             <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDiposisi<?php echo $h->suratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
                                          </div>
                                          <form action="<?php echo site_url('suratmasuk/surat/disposisi') ?>" method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                                <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                                <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                                <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                                    <label class="control-label">Kelurahan Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                                                        <?php foreach ($kelurahan as $key1 => $pdt) { ?> 
                                                            <option value="<?php echo $pdt->jabatan_id ?>"><?php echo $pdt->nama_pd; ?></option>
                                                        <?php }?>
                                                    </select><br><br> 
                                                    <label class="control-label">Aparatur Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                                                        <?php foreach ($aparatur as $key2 => $a) { 
                                                          $atasan=$this->db->query("SELECT * FROM aparatur LEFT JOIN jabatan ON jabatan.jabatan_id=aparatur.jabatan_id WHERE aparatur.jabatan_id='$a->atasan_id'")->result();
                                                          foreach($atasan as $key => $ats){  
                                                        ?>
                                                            <option value="<?php echo $ats->jabatan_id ?>"><?php echo $ats->nama.' - '.$ats->nama_jabatan; ?></option>
                                                        <?php } }?>
                                                    </select><br><br>
                                                <label class="control-label">Tanggal</label>
                                                <input type="text" name="tanggal" class="form-control datepicker" value="<?php echo date('Y-m-d') ?>"/>
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
                                    <?php
                                        }elseif($this->session->userdata('level') == 18){
                                        ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $h->suratmasuk_id ?>" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDiposisi<?php echo $h->suratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
                                          </div>
                                          <form action="<?php echo site_url('suratmasuk/surat/disposisi') ?>" method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                                <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                                <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                                    <label class="control-label">Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                                                        <option value="1">Walikota Kota Bogor</option>
                                                        <option value="2">Wakil Walikota Kota Bogor</option>
                                                        <option value="5">Sekretariat Daerah Kota Bogor</option>
                                                        <option value="1475">Sekpri Walikota</option>
                                                        <option value="1476">Sekpri Wakil Walikota</option>
                                                        <option value="1477">Sekpri Sekretariat Daerah</option>
                                                    </select>  
                                                <br><br>
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
                                        <!-- Untuk Admin TU Umum -->
                                        <?php
                                        }elseif($cekSelesai == 0 ){?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                        <?php  }?>
                                    <?php }?>
                                    <?php 
                                    if($pengembalian==1){?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php }?>

                                </td>
                            </tr>
                            <?php
                                // ++$start; 
                                } 
                            }
                            ?>
                        </tbody>
                    </table>
		    </div>
                    <!-- Pagination Boostrap -->
                    <?php
                    if($h->dibuat_id == $jabatanid){
                      echo $this->pagination->create_links();
                     }
                    ?>
                    <!-- Pagination Boostrap -->
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>
<script>
    function yesnoCheck() {
    if (document.getElementById('noCheck').checked) {
        document.getElementById('ifNo').style.display = 'block';
    }else document.getElementById('ifNo').style.display = 'none';
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.display = 'block';
    }else document.getElementById('ifYes').style.display = 'none';
    if (document.getElementById('2Check').checked) {
        document.getElementById('if2').style.display = 'block';
        document.getElementById('if2').style.display = 'block';
    }else document.getElementById('if2').style.display = 'none';

}
</script>