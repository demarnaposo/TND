<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Keterangan</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Keterangan </h2>
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
                    <a href="<?php echo site_url('suratkeluar/keterangan/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Surat</a> <br><br>
                    <div class="table-responsive">
		    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th width="12%">TANGGAL</th>
                                <th width="10%">KODE SURAT</th>
                                <th width="12%">KOP SURAT</th>
                                <th width="10%">NOMOR SURAT</th>
                                <th width="12%">PEGAWAI</th>								
                                <th width="12%">MAKSUD</th>
                                <th width="15%">STATUS SURAT</th>
                                <th width="15%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($keterangan as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo tanggal($h->tanggal); ?></td>
                                <td>
                                <?php
                                    $kode=$this->db->query("SELECT kode_surat.kode, kode_surat.tentang FROM surat_keterangan LEFT JOIN kode_surat ON kode_surat.kodesurat_id=surat_keterangan.kodesurat_id WHERE surat_keterangan.kodesurat_id='$h->kodesurat_id'")->row_array();
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
                                    $kop_id=$this->db->query("SELECT kop_surat.kop_id,kop_surat.nama FROM surat_edaran LEFT JOIN kop_surat ON kop_surat.kop_id=surat_edaran.kop_id WHERE surat_edaran.kop_id='$h->kop_id'")->row_array();
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
								<td><?php echo $h->namapegawai; ?></td>								
								<td><?php echo $h->maksud; ?></td>
                                <td style="font-weight: bold;">
                                    
                                    <?php 
                                        $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array(); // Update @Mpik Egov 22/06/2022
                                        $qverifikasi = $this->db->query("
                                            SELECT * FROM verifikasi 
                                            JOIN surat_keterangan ON verifikasi.surat_id = surat_keterangan.id
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
                                                    JOIN surat_keterangan ON surat_keterangan.id = draft.surat_id 
                                                    JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                    JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                    LEFT JOIN penandatangan ON penandatangan.surat_id=draft.surat_id
                                                    WHERE surat_keterangan.id = '$h->id' AND aparatur.statusaparatur='Aktif'
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
                                            <a href="<?php echo site_url('export/keterangan/'.$h->id) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>                                    
                                        <?php }
                                        // END
                                        // Update @Mpik Egov 22/06/2022    
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
                                                <a href="<?php echo base_url('assets/lampiransurat/keterangan/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
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
                                                <a href="<?php echo site_url('export/keterangan/'.$h->id) ?>" title="Lihat Surat" target="_blank" style="text-decoration: none;" class="btn btn-danger">Lihat Surat</a> 
                                                <a href="<?php echo base_url('assets/lampiransurat/keterangan/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
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
                                    | <a href="<?php echo site_url('suratkeluar/keterangan/edit/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> 
                                    
                                    <?php if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {  ?>
                                    | <a href="<?php echo site_url('suratkeluar/keterangan/delete/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    
                                    <?php 
                                                }
                                            }
                                        }
                                    ?>
<!--============ END [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================--> 
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

<!--============ [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
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
<!--============ [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->  
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