<!-- START BREADCRUMB --> 
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Instruksi Wali Kota</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Instruksi Wali Kota </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php $this->session->set_userdata('referred_from', current_url()); ?>
                    <a href="<?php echo site_url('suratkeluar/instruksi/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Surat</a> <br><br>
                    <div class="table-responsive">
		    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>TENTANG</th> 
                                <th>KEPADA</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($instruksi as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->tentang; ?></td>
                                <td>
                                <?php
                                    $this->db->from('disposisi_suratkeluar');
                                    $this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left'); 
                                    $this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left'); 
                                    $this->db->join('eksternal_keluar', 'eksternal_keluar.id = disposisi_suratkeluar.users_id', 'left'); 
                                    $this->db->where('disposisi_suratkeluar.surat_id', $h->id);
                                    $kepada = $this->db->get();
                                    $no = 1;
                                    if ($kepada->num_rows() < 2) {
                                            foreach ($kepada->result() as $key => $k){
                                            $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
                                            if (!empty($k->nama_pd)){
                                            if($k->nomenklatur_pd == 'Sekda'){
                                                foreach($query as $key => $q){
                                                    if(substr($q->nama_jabatan, 0,6) == 'Kepala') {
                                                    echo "Sekretaris Daerah Kota Bogor<br>Up. ".substr($q->nama_jabatan, 7);
                                                    } 
                                                    elseif($q->nama_jabatan != 'Sekretaris Daerah') {
                                                    echo "Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan;
                                                    }
                                                    else {
                                                    echo "Sekretaris Daerah Kota Bogor";
                                                    }
                                                    ++$no;
                                                }
                                            }elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
                                                foreach($query as $key => $q){
                                                    echo $q->nama_jabatan;
                                                    ++$no;
                                                }
                                            }elseif($k->nomenklatur_pd == 'Sekwan'){
                                                foreach($query as $key => $q){
                                                    echo $q->nama_jabatan;
                                                    ++$no;
                                                }
                                            }elseif($k->nomenklatur_pd == 'Wali Kota Bogor'){
                                                    echo 'Bapak '.$k->nama_pd;
                                                    ++$no;
                                            }else{
                                            echo 'Kepala '.$k->nama_pd.'<br>';
                                            ++$no;
                                            }
                                            }else{
                                                echo $k->nama.'<br>';
                                                ++$no;
                                            }
                                        }
                                        }elseif($kepada->num_rows() < 4){
                                            foreach ($kepada->result() as $key => $k){
                                            $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
                                            if (!empty($k->nama_pd)){
                                                if($k->nomenklatur_pd == 'Sekda'){
                                                    foreach($query as $key => $q){
                                                    if(substr($q->nama_jabatan, 0,6) == 'Kepala') {
                                                    echo $no,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".substr($q->nama_jabatan, 7).'<br>';
                                                    } 
                                                    elseif($q->nama_jabatan != 'Sekretaris Daerah') {
                                                    echo $no,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan.'<br>';
                                                    }
                                                    else {
                                                    echo $no,'. '."Sekretaris Daerah Kota Bogor".'<br>';
                                                    }
                                                    ++$no;
                                                    }
                                                }elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
                                                    foreach($query as $key => $q){
                                                        echo $no,'. ',$q->nama_jabatan.'<br>';
                                                        ++$no;
                                                    }
                                                }else{
                                                echo $no,'. '.$k->kode_pd.'<br>';
                                                ++$no;
                                                }
                                            }else{
                                                echo $no, '. ', $k->nama.'<br>';
                                                ++$no;
                                            }
                                        }
                                        }elseif($kepada->num_rows() > 3){
                                            echo "Terlampir";
                                        }
                                        ?>                          
				</td>
                                <td style="font-weight: bold;">
                                    
                                    <?php 
                                        $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array(); // Update @Mpik Egov 21/06/2022
                                        $qverifikasi = $this->db->query("
                                            SELECT * FROM verifikasi 
                                            JOIN surat_instruksi ON verifikasi.surat_id = surat_instruksi.id
                                            WHERE verifikasi.surat_id = '$h->id'
                                        ")->num_rows();
                                        if (empty($qverifikasi)) {
                                            echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                        }elseif($q['status'] == 'Belum Ditandatangani'){// Update @Mpik Egov 21/06/2022
                                            echo "<p style='color:red; text-align:center;'> SURAT SEDANG PROSES TTE </p>"; // Update @Mpik Egov 21/06/2022
                                        }else{
                                    ?>
                                    
                                    <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->id ?>" title="Lihat Status Surat" class="btn btn-info">Lihat</a></center>
                                    
                                    <!-- Modal Status Surat -->
                                    <div class="modal fade" id="modalStatus<?php echo $h->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Status Surat</h5>
                                          </div>
                                          <div class="modal-body">

                                            <?php 
                                                $berada = $this->db->query("
                                                    SELECT * FROM draft
                                                    JOIN surat_instruksi ON surat_instruksi.id = draft.surat_id 
                                                    JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                    JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                    WHERE surat_instruksi.id = '$h->id'
                                                ")->row_array();
                                                $sudahdiarsipkan = $this->db->get_where('draft', array('surat_id' => $h->id))->row_array();
                                                if ($berada['verifikasi_id'] == '-1' OR $sudahdiarsipkan['verifikasi_id'] == '-1') {
                                                    echo "<center>SURAT SUDAH DIARSIPKAN</center>";
                                                }else{
                                                    echo "SURAT BERADA DI : <br>";
                                                    echo $berada['nama'].' - '.$berada['nama_jabatan']; 
                                                } 
                                            ?>
                                                
                                            <br><br>
                                            KETERANGAN SURAT : <br>
                                           
                                            <?php
                                                $qketver = $this->db->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $h->id))->result();
                                                $nmr = 1;
                                                foreach ($qketver as $key => $kv) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kv->dari; ?><br>
                                            Keterangan : <?php echo $kv->keterangan; ?> <br>Tanggal : <?= tanggal($kv->tanggal);?><br><br>

                                            <?php $nmr++; } ?>
                                          
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Status Surat -->

                                    <?php } ?>

                                </td>
                                <td align="center">

                                    <?php 
                                        // Update @Mpik Egov 21/06/2022
                                        if (empty($h->lampiran_lain)) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'");
                                        if($query->row_array()['status'] == 'Belum Ditandatangani'){
                                            echo "Surat Masih Proses Penandatanganan";                                            
                                        }elseif($query->row_array()['status'] == 'Sudah Ditandatangani'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->id.'.pdf') ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>     
                                        <?php }else{?>
                                            <a href="<?php echo site_url('export/instruksi/'.$h->id) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>                                    
                                        <?php }                       
                                        // END
                                        // Update @Mpik Egov 21/06/2022
                                        }else{
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'");
                                        if($query->row_array()['status'] == 'Belum Ditandatangani'){
                                            echo "Surat Masih Proses Penandatanganan";                                            
                                        }elseif($query->row_array()['status'] == 'Sudah Ditandatangani'){
                                    ?>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalLihat<?php echo $h->id ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>

                                        <!-- Modal Status Surat -->
                                        <div class="modal fade" id="modalLihat<?php echo $h->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
                                            </div>
                                            <div class="modal-body">
                                                <a href="<?php echo site_url('uploads/SIGNED/'.$h->id) ?>" title="Lihat Surat" target="_blank" style="text-decoration: none;" class="btn btn-danger">Lihat Surat</a> 
                                                <a href="<?php echo base_url('assets/lampiransurat/instruksi/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                        <!-- End Modal Status Surat -->
                                    <?php }else{ ?>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalLihat<?php echo $h->id ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>

                                        <!-- Modal Status Surat -->
                                        <div class="modal fade" id="modalLihat<?php echo $h->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
                                              </div>
                                              <div class="modal-body">
                                                <a href="<?php echo site_url('export/instruksi/'.$h->id) ?>" title="Lihat Surat" target="_blank" style="text-decoration: none;" class="btn btn-danger">Lihat Surat</a> 
                                                <a href="<?php echo base_url('assets/lampiransurat/instruksi/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Status Surat -->
                                    <?php
                                        }
                                    }
                                    ?>

<!--============ START [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
                                    <?php
                                        $cekTU = getTU($this->session->userdata('opd_id'));
                                        if ($h->verifikasi_id != $cekTU['jabatan_id']) {
                                            if ($h->verifikasi_id != -1 AND $q['status'] != 'Belum Ditandatangani' AND $q['status'] != 'Sudah Ditandatangani') {
                                    ?>
                                    | <a href="<?php echo site_url('suratkeluar/instruksi/edit/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> 
                                    
                                    <?php if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {  ?>
                                    | <a href="<?php echo site_url('suratkeluar/instruksi/delete/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    
                                    <?php 
                                                }
                                            }
                                        }
                                    ?>
<!--============ END [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================--> 

                                    
                                    <?php 
                                        if ($this->session->userdata('jabatan_id') == $h->verifikasi_id OR $h->verifikasi_id == 0) {
                                            if ($this->session->userdata('level') != 11 AND $this->session->userdata('level') != 5) {
                                    ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php
                                            } 
                                        } 
                                    ?>
<!--============ START [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
                                    <?php if ($this->session->userdata('level') == 5 OR $this->session->userdata('level') == 6 OR $this->session->userdata('level') == 9 OR $this->session->userdata('level') == 10 OR $this->session->userdata('level') == 11) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        if ($tombol['keterangan'] != 'Surat telah diselesaikan' AND $query['status'] != 'Belum Ditandatangani' AND $query['status'] != 'Sudah Ditandatangani' AND $this->session->userdata('jabatan_id') == $cekdraft['verifikasi_id']) {
                                    ?>
                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } ?>
<!--============ END [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->


                                </td>
                            </tr>

                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="modalPengajuan<?php echo $h->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pengajuan Surat</h5>
                                  </div>
                                  <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post">
                                      <div class="modal-body">
                                            <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                            <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                            <label class="control-label">Keterangan</label>                             
                                            <textarea type="text" name="keterangan" class="form-control"></textarea>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" name="verifikasi" class="btn btn-primary" value="Teruskan">
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!-- End Modal Verifikasi -->

                            <?php
                                $no++; 
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