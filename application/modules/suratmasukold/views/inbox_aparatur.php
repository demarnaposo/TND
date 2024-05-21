<!-- START BREADCRUMB -->
<ul class="breadcrumb">
        <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Terusan Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Terusan Surat </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                
   
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>PENERIMA</th>
                                <th>DARI</th>
                                <th>HAL</th>
                                <th>TANGGAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                foreach ($inbox as $key1 => $p) {
                                
                                $disposisi = $this->db->group_by("suratmasuk_id")->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $p->suratmasuk_id, 'aparatur_id' => $this->session->userdata('jabatan_id')))->result();
                                
                                }

                                foreach ($disposisi as $key2 => $d) {
                                $sql = $this->db->query("
                                    SELECT *,surat_masuk.tanggal as tglsurat, surat_masuk.lampiran_lain,surat_masuk.lampiran FROM disposisi_suratmasuk
                                    JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
                                    JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                    WHERE disposisi_suratmasuk.status = 'Belum Selesai'
                                    AND disposisi_suratmasuk.aparatur_id = ".$this->session->userdata('jabatan_id')."
                                    GROUP BY disposisi_suratmasuk.suratmasuk_id
                                    ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
                                ")->result();
                                $no = 1;
                                foreach ($sql as $key3 => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><b><?php echo $h->penerima; ?></b></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td><b>Tanggal Surat :</b> <?php echo tanggal($h->tglsurat) ?><br><b>Tanggal Diterima :</b> <?php echo tanggal($h->diterima) ?></td>
                                
                                <td>
                                    <?php 
                                        $qdisposisi = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk 
                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                            WHERE aparatur.opd_id = '$h->opd_id' 
                                            AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                            ORDER BY disposisi_suratmasuk.dsuratmasuk_id DESC LIMIT 1
                                        ");
                                    ?>

                                    <center><a href="javascript:void()" data-toggle="modal" data-target="#modalDisposisi<?php echo $h->dsuratmasuk_id ?>" title="Lihat Disposisi" class="btn btn-info">Lihat</a></center>

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDisposisi<?php echo $h->dsuratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Disposisi Surat</h5>
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
                                                    // foreach ($cekAtasanJabatan->result() as $key => $j) {
                                                    //     $berada = $this->db->query("
                                                    //         SELECT * FROM disposisi_suratmasuk 
                                                    //         JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                    //         WHERE aparatur.opd_id = '$h->opd_id' 
                                                    //         AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                    //         AND disposisi_suratmasuk.aparatur_id = '$j->jabatan_id'
                                                    //     ")->result();
                                                    //     foreach ($berada as $key => $b) {
                                                    //         echo "<b>".$b->nama."</b>, ";
                                                    //     }
                                                    // }
                                                    $berada = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk 
                                                    JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                    AND disposisi_suratmasuk.status='Belum Selesai'
                                                    ")->result();
                                                    foreach($berada as $key => $qry){
                                                        echo "<b>".$qry->nama."</b>,";
                                                    }
                                                // }
                                                }
                                            ?>
                                            <br><br>
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?><br>Diteruskan Tanggal : <?= tanggal($kd->tanggal);?><br><br>

                                            <?php $nmr++; } ?>
                                          
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
                                <!-- Pengembalian Surat -->
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modalKembalikan<?php echo $h->dsuratmasuk_id ?>" title="Kembalikan Surat"><span class="fa-stack"><i class="fa fa-reply fa-stack-2x"></i></span></a>
                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalKembalikan<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Pengembalian Surat</h5>
                                          </div>
                                          <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                                <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                                <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
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
                                    <!-- End Modal Disposisi -->
                                    <!-- Pengembalian Surat -->
                                    <?php if(empty($h->lampiran_lain)){
                                    if(substr($h->lampiran,0,2) == 'SU' || substr($h->lampiran,0,2) == 'SB' || substr($h->lampiran,0,2) == 'SE' || substr($h->lampiran,0,5) == 'PNGMN' || substr($h->lampiran,0,3) == 'LAP' || substr($h->lampiran,0,3) == 'REK' || substr($h->lampiran,0,3) == 'INT' || substr($h->lampiran,0,3) == 'PNG' || substr($h->lampiran,0,5) == 'NODIN' || substr($h->lampiran,0,2) == 'SK' || substr($h->lampiran,0,3) == 'SPT' || substr($h->lampiran,0,2) == 'SP' || substr($h->lampiran,0,3) == 'IZN' || substr($h->lampiran,0,3) == 'PJL' || substr($h->lampiran,0,3) == 'KSA' || substr($h->lampiran,0,3) == 'MKT' || substr($h->lampiran,0,3) == 'PGL' || substr($h->lampiran,0,3) == 'NTL' || substr($h->lampiran,0,3) == 'MMO' || substr($h->lampiran,0,3) == 'LMP'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php }else{ ?>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
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

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a> 
                                    <?php if($this->session->userdata('level') == 12){?>

                                    <?php }else{?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $h->dsuratmasuk_id ?>" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php }?>
                                    <?php if($this->session->userdata('users_id') == 1414 || $this->session->userdata('users_id') == 2131 || $this->session->userdata('users_id') == 2310 || $this->session->userdata('users_id') == 2311){ ?>
                                    <!-- Jika Login SEKDA -->
                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDiposisi<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
                                          </div>
                                          <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                                <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                                <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                                <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                                    <label class="control-label">Perangkat Daerah Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                                                        <?php foreach ($opd as $key1 => $pdt) { 
                                                            $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$pdt->atasan_id' AND opd_id!=4");
                                                            foreach($query->result() as $ky => $q){
                                                        ?> 
                                                            <option value="<?php echo $pdt->jabatan_id ?>"><?php echo $pdt->nama_pd; echo ' - '.$q->nama_jabatan; ?></option>
                                                        <?php } }?>
                                                    </select>
                                                    <p style="color:red;"><b>Keterangan :</b> <u>Pilihlah perangkat daerah tujuan jika ingin meneruskan surat kepada perangkat daerah yang lain</u></p> 
                                                    <label class="control-label">Aparatur Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true">
                                                        <option value=""> Pilih Aparatur </option>
                                                        <?php foreach ($aparatur as $key2 => $a) { ?>
                                                            <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                                                        <?php } ?>
                                                    </select><br>
                                                <br><br>
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
                                    <!-- <script>
                                        function yesnoCheck() { this.checked = false;

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
                                    </script> -->
                                    <!-- End Modal Disposisi -->
                                    <!-- Jika Login SEKDA -->

                                     <?php }else{ ?>                     

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDiposisi<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Teruskan Surat</h5>
                                          </div>
                                          <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                                <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                                <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                                <input type="hidden" name="users_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                                    <label class="control-label">Aparatur Tujuan</label>
                                                    <select multiple name="aparatur_id[]" class="form-control select" data-live-search="true" required>
                                                        <option value=""> Pilih Aparatur </option>                                                       
                                                        <?php foreach ($aparatur as $key => $a) { ?>
                                                            <?php if($a->statusaparatur != 'Aktif') { ?>
                                                              <option value="<?php echo $a->jabatan_id ?>" disabled><?php echo $a->nama.' - '.$a->nama_jabatan.' (Sudah '.$a->statusaparatur.')'; ?>
                                                            <?php } else { ?> 
                                                              <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                                                        <?php } } ?>
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
                                    <br><br>
                                        <button type="button" data-toggle="modal" data-target="#modalCatatan<?php echo $h->dsuratmasuk_id ?>" class="btn btn-warning">Diterima</button>

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalCatatan<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                            <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                            <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                          </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->

                                </td>
                            </tr>

                            <?php
                                $no++; 
                                }
                                }
                            ?>
                        </tbody>
                    </table>
		    </div>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>






<!-- $result = array();
$group = $result[$h->suratmasuk_id][] = $h;
foreach ($group as $key => $pp) {
    echo $pp;
}
    // echo "<pre>";
    // var_dump($pp);
    // echo "</pre>"; -->
