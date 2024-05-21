<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Lainnya</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Lainnya </h2>
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
                    <?php if($this->uri->segment(2) != 'tembusan'){ ?>
                    <a href="<?php echo site_url('suratkeluar/suratlainnya/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Surat</a> <br><br>
                    <?php } ?>
	            <div class="table-responsive">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>KODE SURAT</th>
                                <th>JENIS SURAT</th>
                                <th>NOMOR SURAT</th>
                                <th>HAL</th>
                                <th>KEPADA</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($suratlainnya as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo tanggal($h->tanggal); ?></td>
                                <td>
                                <?php
                                    $kode_id=$this->db->query("SELECT kode_surat.kode, kode_surat.tentang FROM surat_lainnya LEFT JOIN kode_surat ON kode_surat.kodesurat_id=surat_lainnya.kodesurat_id WHERE surat_lainnya.kodesurat_id='$h->kodesurat_id'")->row_array();
                                    echo $kode_id['kode'] . "-" . $kode_id['tentang'];
                                ?>
                                </td>
                                <td><?php echo $h->jenissurat; ?></td>
                                <td>
                                    <?php 
                                    $data = $h->nomor;
                                    if($data == null) {
                                        echo "-";
                                    }else {
                                        echo $data;
                                    }
                                     ?>
                                </td>
                                <td><?php echo $h->perihal; ?></td>
				<td>
                                <?php
                                    $this->db->from('disposisi_suratkeluar');
                                    $this->db->join('jabatan', 'jabatan.jabatan_id =  disposisi_suratkeluar.users_id', 'left'); 
                                    $this->db->join('opd', 'opd.opd_id = jabatan.opd_id',  'left'); 
                                    $this->db->join('eksternal_keluar',  'eksternal_keluar.id = disposisi_suratkeluar.users_id', 'left'); 
                                    $this->db->where('disposisi_suratkeluar.surat_id',  $h->id);
                                    $kepada = $this->db->get();
                                    $nokpd = 1;
                                    if ($kepada->num_rows() < 2) {
                                        foreach ($kepada->result() as $key => $k){
                                        $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
                                        if (!empty($k->nama_pd)){
                                            if($k->nomenklatur_pd == 'Sekda'){
                                                foreach($query as $key => $q){
                                                    echo $q->nama_jabatan;
                                                    ++$nokpd;
                                                }
                                            }else{
                                            echo ''.$k->kode_pd.'<br>';
                                            ++$nokpd;
                                            }
                                        }else{
                                            echo ''.$k->nama.'<br>';
                                            ++$nokpd;
                                        }
                                    }
                                    }elseif($kepada->num_rows() < 4){
                                        foreach ($kepada->result() as $key => $k){
                                        $query = $this->db->query("SELECT  nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
                                        if (!empty($k->nama_pd)){
                                            if($k->nomenklatur_pd == 'Sekda'){
                                                foreach($query as $key => $q){
                                                    echo $nokpd,'. ',$q->nama_jabatan.',<br>';
                                                    ++$nokpd;
                                                }
                                            }else{
                                            echo $nokpd,'.'.$k->kode_pd.'<br>';
                                            ++$nokpd;
                                            }
                                        }else{
                                            echo $nokpd, '. ', $k->nama.'<br>';
                                            ++$nokpd;
                                        }
                                    }
                                    }elseif($kepada->num_rows() > 3){
                                        echo "Terlampir";
                                    }
                                ?>                                
				</td>
                                <td style="font-weight: bold;">
                                    
                                    <?php
                                        $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array(); // Update @Mpik Egov 22/06/2022
                                        $qverifikasi = $this->db->query("
                                            SELECT * FROM verifikasi 
                                            JOIN surat_lainnya ON verifikasi.surat_id = surat_lainnya.id
                                            WHERE verifikasi.surat_id = '$h->id'
                                        ")->num_rows();
                                        if (empty($qverifikasi)) {
                                            echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                        }elseif($q['status'] == 'Belum Ditandatangani'){ ?>
                                            <p style='color:red; text-align:center;'> SURAT SEDANG PROSES TTE </p>
                                            <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->id ?>" title="Lihat Status Surat">Lihat</a></center>
                                        <?php
                                        }elseif($q['status'] == 'Sudah Ditandatangani'){
                                    ?>
                                    <p style='color:green; text-align:center;'>
                                        SURAT SUDAH DI TTE<br>
                                        <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->id ?>" title="Lihat Status Surat">Lihat</a></center>
                                    </p>
                                    <?php
                                        }else{
                                    // Update @Mpik Egov 26/07/2022
                                    ?>
                                    <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->id ?>" title="Lihat Status Surat" class="btn btn-info">Lihat</a></center>
                                    
                                    <?php } ?>
                                    <!-- Modal Status Surat -->
                                    <div class="modal fade" id="modalStatus<?php echo $h->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Status Surat</h5>
                                          </div>
                                          <div class="modal-body">

                                            <?php 
                                                // Update @Mpik Egov 3 August 2022
                                                $berada = $this->db->query("
                                                    SELECT draft.verifikasi_id,aparatur.nama,jabatan.nama_jabatan,penandatangan.nama as namapenandatangan, penandatangan.jabatan as jabatanpenandatangan FROM draft
                                                    JOIN surat_lainnya ON surat_lainnya.id = draft.surat_id 
                                                    JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                    JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                    LEFT JOIN penandatangan ON penandatangan.surat_id=draft.surat_id
                                                    WHERE surat_lainnya.id = '$h->id' AND aparatur.statusaparatur='Aktif'
                                                ")->row_array();
                                                $sudahdiarsipkan = $this->db->get_where('draft', array('surat_id' => $h->id))->row_array();
                                                if ($berada['verifikasi_id'] == '-1' OR $sudahdiarsipkan['verifikasi_id'] == '-1') {
                                                    echo "<center>SURAT SUDAH DIARSIPKAN</center>";
                                                }else{
                                                    // Update @Mpik Egov 3 August 2022
                                                    if(empty($berada['namapenandatangan'])){
                                                        echo "SURAT BERADA DI : <br>";
                                                        echo $berada['nama'].' - '.$berada['nama_jabatan']; 
                                                    }else{
                                                        echo "<b>Penandatangan : <br>";
                                                        echo $berada['namapenandatangan'].' - '.$berada['jabatanpenandatangan'];
                                                    }
                                                    // END Update @Mpik Egov 3 August 2022
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
                                            Keterangan : <?php echo $kv->keterangan; ?> <br><br>

                                            <?php $nmr++; } ?>
                                          
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Status Surat -->

                                </td>
                                <td align="center">

                                    <?php 
                                            $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array();
                                                if (isset($q['status']) == 'Sudah Ditandatangani' && isset($q['file_path']) != NULL) {
                                    ?>
                                        <!--<a href="<?=base_url('uploads/SIGNED/'.$q['file_path']);?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>-->
                                    	<a href="<?= base_url('assets/lampiransurat/suratlainnya/'.$h->suratlainnya_lain)?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>
				    <?php
                                                }else{
                                    ?>
				    
					<!-- Kondisi jika surat belum ditandatangan tidak bisa preview surat -->
                                        <?php 
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'");
                                        if($query->row_array()['status'] == 'Belum Ditandatangani'){
                                            echo "Surat Masih Proses Penandatanganan";                                            
                                        }elseif($query->row_array()['status'] == 'Sudah Ditandatangani'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->id.'.pdf') ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>     
                                        <?php }else{?>
                                            <!--<a href="<?php echo site_url('assets/suratlainnyasurat/suratlainnya/'.$h->id.'pdf') ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>-->
                                            <a href="<?php echo site_url('assets/lampiransurat/suratlainnya/'.$h->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>

					    <!--<a href="<?= base_url('assets/lampiransurat/suratlainnya/'.$h->lampiran)?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>-->
						

                                        <?php }?>
                                        <!-- Kondisi jika surat belum ditandatangan tidak bisa preview surat --> 

				                    <?php
                                            }
                                            if(empty($h->suratlainnya_lain)){
                                                echo '';
                                            }else{
                                    ?>
					
                                        <!--<a href="<?php echo base_url('assets/suratlainnyasurat/suratlainnya/'.$h->suratlainnya_lain) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a>-->                                
                                    <?php 
                                    } 
                                    ?>

                                    <?php
                                    if($this->uri->segment(2) != 'tembusan'){
                                        $cekTU = getTU($this->session->userdata('opd_id'));
                                        if ($h->verifikasi_id != $cekTU['jabatan_id']) {
                                            if ($h->verifikasi_id != -1) {
                                    ?>
                                    | <a href="<?php echo site_url('suratkeluar/suratlainnya/edit/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> 
                                    
                                    <?php if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {  ?>
                                    | <a href="<?php echo site_url('suratkeluar/suratlainnya/delete/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    
                                    <?php 
                                                }
                                            }
                                        }
                                    ?>
                                    
                                    <!-- Fungsi Terusan Surat -->
                                    <?php 
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        if ($this->session->userdata('jabatan_id') == $h->verifikasi_id OR $h->verifikasi_id == 0) {
                                            if ($this->session->userdata('level') != 9 AND $this->session->userdata('level') != 5 AND $this->session->userdata('level') != 10 AND $this->session->userdata('level') != 19 AND empty($query['status'])) { // Update @Mpik Egov 25/07/2022 ?>
                                                | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                <?php
                                            }
                                            // Update @Mpik Egov 25/07/2022
                                            if($this->session->userdata('level') == 5){
                                                if($h->kop_id == 4 || $h->kop_id == 3 || $h->kop_id == 1){ // Update @Mpik Egov 25/07/2022 ?>
                                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                <?php        
                                                }else{
                                                    echo '';
                                                }
                                            }elseif($this->session->userdata('level') == 10){
                                                if($h->kop_id == 1 || $h->kop_id == 3 || $h->kop_id == 6 || $h->kop_id == 7){ // Update @Mpik Egov 25/07/2022  ?>  
                                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                                <?php        
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            // END
                                            // Update @Mpik Egov 25/07/2022
                                        } 
                                    ?>
                                    <!-- Selesai Fungsi Terusan Surat -->

                                    <!-- Update @Mpik Egov 29/06/2022 -->
                                    <?php if ($this->session->userdata('level') == 5 OR $this->session->userdata('level') == 6) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        if (!empty($query['status']) or $cekdraft['verifikasi_id'] != 0) { }else{
                                            if($h->kop_id == 3 or $h->kop_id == 4 or $h->kop_id == 1){ }else{ // Update @Mpik Egov 29/06/2022
                                    ?>
                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } }elseif ($this->session->userdata('level') == 10 OR $this->session->userdata('level') == 11){ // Update @Mpik Egov 25/07/2022
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        if (!empty($query['status']) or $cekdraft['verifikasi_id'] != 0) { }else{
                                            if($h->kop_id == 3 or $h->kop_id == 1){ }else{ // Update @Mpik Egov 25/07/2022
                                    ?>
                                        <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } } ?> <!-- Update @Mpik Egov 25/07/2022 -->
                                    <!-- Update @Mpik Egov 29/06/2022 -->
                                    
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
                                            <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                            <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                            <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                            <label class="control-label">Keterangan</label>                             
                                            <textarea type="text" name="keterangan" class="form-control"></textarea>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <input type="submit" name="verifikasi" class="btn btn-primary" value="Teruskan">
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!-- End Modal Verifikasi -->

                            <?php
                                }
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