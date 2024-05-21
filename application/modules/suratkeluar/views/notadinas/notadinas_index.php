<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Nota Dinas</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Nota Dinas </h2>
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
                    <a href="<?php echo site_url('suratkeluar/notadinas/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Surat</a> <br><br>
                    
		    <div class="table-responsive">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL</th>
                                <th>KODE SURAT</th>
                                <th>KOP SURAT</th>
                                <th>NOMOR SURAT</th>
                                <th>KEPADA</th>
                                <th>SIFAT</th>
                                <th>HAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($notadinas as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?>.</td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td>
                                <?php
                                    $kode=$this->db->query("SELECT kode_surat.kode, kode_surat.tentang FROM surat_notadinas LEFT JOIN kode_surat ON kode_surat.kodesurat_id=surat_notadinas.kodesurat_id WHERE surat_notadinas.kodesurat_id='$h->kodesurat_id'")->row_array();
                                    // echo $kode['kode'] . "-" . $kode['tentang'];
                                    if($kode['kode'] == null) {
                                        echo "-";
                                    }else{
                                        echo $kode['kode'] . "-" . $kode['tentang'];
                                    }
                                ?>
                                </td>
                                <td>
                                    <?php
                                    $kop_id=$this->db->query("SELECT kop_surat.nama FROM surat_notadinas LEFT JOIN kop_surat ON kop_surat.kop_id=surat_notadinas.kop_id WHERE surat_notadinas.kop_id='$h->kop_id'")->row_array();
                                    echo $kop_id['nama'];
                                    ?>
                                </td>
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
                                <td><?php						
                            	    $this->db->from('disposisi_suratkeluar');
                            		$this->db->join('jabatan', 'jabatan.jabatan_id = disposisi_suratkeluar.users_id', 'left'); 
                            		$this->db->join('opd', 'opd.opd_id = jabatan.opd_id', 'left'); 
                            		$this->db->join('eksternal_keluar', 'eksternal_keluar.id = disposisi_suratkeluar.users_id', 'left'); 
                            		$this->db->where('disposisi_suratkeluar.surat_id', $h->id);
                            		$kepada = $this->db->get();
                            		$jmlkepada=$kepada->num_rows();
                            		$nokpd = 1;
                                    if ($kepada->num_rows() == 1) {
                                        foreach ($kepada->result() as $key => $k){
                                        $query = $this->db->query("SELECT nama_jabatan,jabatan_id FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->row_array();
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
                                            }
                                        }elseif($k->nomenklatur_pd == 'Sekwan'){
                                            foreach($query as $key => $q){
                                                echo $q->nama_jabatan;
                                            }
                                        }elseif($k->nomenklatur_pd == 'Walikota'){
                                                echo 'Bapak '.$k->nama_pd;
                                        }elseif($k->nomenklatur_pd == 'Dirut. RSUD'){
                                                echo 'Direktur '.$k->nama_pd;
                                        }elseif(substr($k->nama_jabatan, 0,7) != 'admintu'){
                                                echo $k->jabatan; // Update @Mpik Egov 29/06/2022
                                        }else{
                                        echo 'Kepala '.$k->nama_pd;
                                        }
                                        }else{
                                            echo $k->nama;
                                         }
                                    }
                                    }elseif($kepada->num_rows() <= 3){
                                        foreach ($kepada->result() as $key => $k){
                                        $query = $this->db->query("SELECT nama_jabatan FROM jabatan WHERE jabatan_id = '$k->atasan_id'")->result();
                                        if (!empty($k->nama_pd)){
                                            if($k->nomenklatur_pd == 'Sekda'){
                                                foreach($query as $key => $q){
                                                    if(substr($q->nama_jabatan, 0,6) == 'Kepala') {
                                                    echo $nokpd,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".substr($q->nama_jabatan, 7).'<br>';
                                                    } 
                                                    elseif($q->nama_jabatan != 'Sekretaris Daerah') {
                                                    echo $nokpd,'. '."Sekretaris Daerah Kota Bogor<br>Up. ".$q->nama_jabatan.'<br>';
                                                    }
                                                    else {
                                                    echo $nokpd,'. '."Sekretaris Daerah Kota Bogor".'<br>';
                                                    }
                                                    ++$nokpd;
                                                }
                                            }elseif(substr($k->nama_jabatan, 0,7) != 'admintu'){
                                                echo $nokpd,'. '.$k->jabatan.'<br>'; //Update @Mpik Egov 29/06/2022
                                                ++$nokpd;
                                            }elseif(substr($k->nomenklatur_pd, 0,5) == 'Lurah' || substr($k->nomenklatur_pd, 0,5) == 'Camat'){
                                                foreach($query as $key => $q){
                                                    echo $nokpd,'. ',$q->nama_jabatan.',<br>';
                                                    ++$nokpd;
                                                }
                                            }else{
                                            echo $nokpd,'. '.$k->kode_pd.'<br>';
                                            ++$nokpd;
                                            }
                                        }else{
                                            echo $nokpd, '. ', $k->nama.'<br>';
                                            ++$nokpd;
                                        }
                                    }
                                    }elseif($kepada->num_rows() >= 3){
                                        echo "Terlampir";
                                    }				
                            		?></td>
                                <td><?php echo $h->sifat; ?></td>
                                <td><?php echo $h->hal; ?></td>
                                <td style="font-weight: bold;">
                                    
                                    <?php 
                                            $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array(); // Update @Mpik Egov 22/06/2022
                                            $qverifikasi = $this->db->query("
                                            SELECT * FROM verifikasi 
                                            JOIN surat_notadinas ON verifikasi.surat_id = surat_notadinas.id
                                            WHERE verifikasi.surat_id = '$h->id'
                                        ")->num_rows();
                                        if (empty($qverifikasi)) {
                                            echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                        // Update @Mpik Egov 3 August 2022
                                        }elseif($q['status'] == 'Belum Ditandatangani'){ ?>
                                            <p style='color:red; text-align:center;'> SURAT SEDANG PROSES TTE </p>
                                            <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->id ?>" title="Lihat Status Surat">Lihat</a></center>
                                        <?php
                                        // Update @Mpik Egov 3 August 2022
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
                                                    SELECT draft.verifikasi_id,aparatur.nama,jabatan.nama_jabatan,penandatangan.nama as namapenandatangan, penandatangan.jabatan as jabatanpenandatangan,penandatangan.status FROM draft
                                                    JOIN surat_notadinas ON surat_notadinas.id = draft.surat_id 
                                                    JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                    JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                    LEFT JOIN penandatangan ON penandatangan.surat_id=draft.surat_id
                                                    WHERE surat_notadinas.id = '$h->id' AND aparatur.statusaparatur='Aktif'
                                                ")->row_array();
                                                // Update @Mpik Egov 3 August 2022
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
                                            Keterangan : <?php echo $kv->keterangan; ?> <br>Tanggal : <?= tanggal($kv->tanggal);?><br><br>

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
                                        // Update @Mpik Egov 22/06/2022
                                        if (empty($h->lampiran_lain)) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'");
                                        if($query->row_array()['status'] == 'Belum Ditandatangani'){
                                            echo "Surat Masih Proses Penandatanganan";                                            
                                        }elseif($query->row_array()['status'] == 'Sudah Ditandatangani'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->id.'.pdf') ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>     
                                        <?php }else{?>
                                            <a href="<?php echo site_url('export/notadinas/'.$h->id) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>                                    
                                        <?php }                         
                                        // END
                                        // Update @Mpik Egov 22/06/2022
                                        }else{
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
                                                <a href="<?php echo site_url('export/notadinas/'.$h->id) ?>" title="Lihat Surat" target="_blank" style="text-decoration: none;" class="btn btn-danger">Lihat Surat</a> 
                                                <a href="<?php echo base_url('assets/lampiransurat/notadinas/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
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
                                    ?>

<!--============ START [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
                                    <?php
                                        $cekTU = getTU($this->session->userdata('opd_id'));
                                        if ($h->verifikasi_id != $cekTU['jabatan_id']) {
                                            if ($h->verifikasi_id != -1 AND $q['status'] != 'Belum Ditandatangani' AND $q['status'] != 'Sudah Ditandatangani') {
                                    ?>
                                    | <a href="<?php echo site_url('suratkeluar/notadinas/edit/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> 
                                    
                                    <?php if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {  ?>
                                    | <a href="<?php echo site_url('suratkeluar/notadinas/delete/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    
                                    <?php 
                                                }
                                            }
                                        }
                                    ?>
<!--============ END [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================--> 
<!--============ START [UPDATE] Fikri Egov 11 Feb 2022 ==================================================[UPDATE] Fikri Egov 11 Feb 2022=====================================================================[UPDATE] Fikri Egov 11 Feb 2022==============================================================================================================[UPDATE] Fikri Egov 11 Feb 2022============================================================-->
                                    <?php 
                                        if ($this->session->userdata('jabatan_id') == $h->verifikasi_id OR $h->verifikasi_id == 0) {
                                            if ($this->session->userdata('level') != 11 AND $this->session->userdata('level') != 5) {
                                    ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php
                                            } 
                                        } 
                                    ?>

<?php
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        if ($tombol['keterangan'] != 'Surat telah diselesaikan' AND $query['status'] != 'Belum Ditandatangani' AND $query['status'] != 'Sudah Ditandatangani') {
                                            if($this->session->userdata('jabatan_id') != $cekdraft['verifikasi_id'] AND $cekdraft['verifikasi_id'] != 0){ }else{
                                    ?>
                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } ?>
<!--============ END [UPDATE] Fikri Egov 11 Feb 2022 ==================================================[UPDATE] Fikri Egov 11 Feb 2022=====================================================================[UPDATE] Fikri Egov 11 Feb 2022==============================================================================================================[UPDATE] Fikri Egov 11 Feb 2022============================================================-->
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
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
