<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)#">Naskah Dinas Surat</a></li>
    <li class="active">Surat Berita Acara</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Berita Acara </h2>
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
                    <a href="<?php echo site_url('suratkeluar/beritaacara/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Surat</a> <br><br>
                    <div class="table-responsive">
		    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th width="12%">TANGGAL</th>
                                <th width="10%">KODE SURAT</th>
                                <th width="12%">KOP SURAT</th>
                                <th width="10%">NOMOR SURAT</th>
                                <th width="12%">PIHAK PERTAMA</th>
                                <th width="12%">PIHAK KEDUA</th>
                                <th width="15%">STATUS SURAT</th>
                                <th width="15%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($beritaacara as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td>
                                <?php
                                    $kode=$this->db->query("SELECT kode_surat.kode, kode_surat.tentang FROM surat_beritaacara LEFT JOIN kode_surat ON kode_surat.kodesurat_id=surat_beritaacara.kodesurat_id WHERE surat_beritaacara.kodesurat_id='$h->kodesurat_id'")->row_array();
                                    // echo $kode_id['kode'] . "-" . $kode_id['tentang'];
                                    if($kode['kode'] == null) {
                                        echo "-";
                                    }else{
                                        echo $kode['kode'] . "-" . $kode['tentang'];
                                    }
                                ?>
                                </td>
                                <td>
                                    <?php
                                    $kop_id=$this->db->query("SELECT kop_surat.nama FROM surat_beritaacara LEFT JOIN kop_surat ON kop_surat.kop_id=surat_beritaacara.kop_id WHERE surat_beritaacara.kop_id='$h->kop_id'")->row_array();
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
                                <?php 
                                $query=$this->db->query("SELECT b.nama as nama1,c.nama as nama2 FROM surat_beritaacara a LEFT JOIN aparatur b ON b.aparatur_id=a.pihakpertama_id LEFT JOIN aparatur c ON c.aparatur_id=a.pihakkedua_id WHERE a.id='$h->id'")->result();
                                foreach($query as $key){
                                ?>
                                <td><?= $key->nama1; ?></td>
                                <td><?= $key->nama2; ?></td>
                                <?php } ?>
                                <td style="font-weight: bold;">
                                    
                                    <?php 
                                        $q = $this->db->query("SELECT * FROM penandatangan WHERE surat_id = '$h->id'")->row_array(); // Update @Mpik Egov 21/06/2022 : Penambahan kondisi Surat Sedang Proses TTE
                                        $qverifikasi = $this->db->query("
                                            SELECT * FROM verifikasi 
                                            JOIN surat_beritaacara ON verifikasi.surat_id = surat_beritaacara.id
                                            WHERE verifikasi.surat_id = '$h->id'
                                        ")->num_rows();
                                        if (empty($qverifikasi)) {
                                            echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                        }elseif($q['status'] == 'Belum Ditandatangani'){
                                            echo "<p style='color:red; text-align:center;'> SURAT SEDANG PROSES TTE </p>";
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
                                                    JOIN surat_beritaacara ON surat_beritaacara.id = draft.surat_id 
                                                    JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                    JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                    WHERE surat_beritaacara.id = '$h->id'
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
                                            Keterangan : <?php echo $kv->keterangan; ?> <br>Tanggal : <?= tanggal($kv->tanggal);?><br>

                                            <?php $nmr++; } ?>
                                          
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Status Surat -->

                                    <?php } ?>

                                </td>
                                <td align="center">
                                <!-- Update @Mpik Egov 21/06/2022 : Penambahan kondisi Surat Sedang Proses TTE  -->
                                    <?php 
                                        if (empty($h->lampiran_lain)) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'");
                                        if($query->row_array()['status'] == 'Belum Ditandatangani'){
                                            echo "Surat Masih Proses Penandatanganan";                                            
                                        }elseif($query->row_array()['status'] == 'Sudah Ditandatangani'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->id.'.pdf') ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>     
                                        <?php }else{?>
                                            <a href="<?php echo site_url('export/beritaacara/'.$h->id) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a>                                    
                                        <?php }?>
                                        <!-- Kondisi jika surat belum ditandatangan tidak bisa preview surat --> 
                                <!-- Update @Mpik Egov 21/06/2022 : Penambahan kondisi Surat Sedang Proses TTE -->
                                        <?php
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
                                                <a href="<?php echo base_url('assets/lampiransurat/beritaacara/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
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
                                                <a href="<?php echo site_url('export/beritaacara/'.$h->id) ?>" title="Lihat Surat" target="_blank" style="text-decoration: none;" class="btn btn-danger">Lihat Surat</a> 
                                                <a href="<?php echo base_url('assets/lampiransurat/beritaacara/'.$h->lampiran_lain) ?>" title="Lihat Lampiran" target="_blank" style="text-decoration: none;" class="btn btn-warning">Lihat Lampiran</a>
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
                                    | <a href="<?php echo site_url('suratkeluar/beritaacara/edit/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Ubah Surat"><span class="fa-stack"><i class="fa fa-pencil fa-stack-2x"></i></span></a> 
                                    
                                    <?php if ($h->dibuat_id == $this->session->userdata('jabatan_id')) {  ?>
                                    | <a href="<?php echo site_url('suratkeluar/beritaacara/delete/'.$h->id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><span class="fa-stack"><i class="fa fa-trash-o fa-stack-2x"></i></span></a>
                                    
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
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalVerifikasi<?php echo $h->id ?>" title="Teruskan Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php
                                            } 
                                        } 
                                    ?>

<!--============ [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
                                    <!-- Update @Mpik Egov 29/06/2022 -->
                                    <?php if ($this->session->userdata('level') == 5 OR $this->session->userdata('level') == 6 OR $this->session->userdata('level') == 9) {
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        if ($query['status'] == 'Sudah Ditandatangani' or $cekdraft['verifikasi_id'] != 0) { }else{
                                            if($h->kop_id == 3 or $h->kop_id == 4 or $h->kop_id == 1){ }else{ // Update @Mpik Egov 29/06/2022
                                    ?>
                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } }elseif ($this->session->userdata('level') == 5 OR $this->session->userdata('level') == 10 OR $this->session->userdata('level') == 11){ 
                                        $query=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->id'")->row_array();
                                        $cekdraft=$this->db->query("SELECT verifikasi_id FROM draft WHERE surat_id='$h->id'")->row_array();
                                        $tombol = $this->db->query("SELECT keterangan FROM verifikasi WHERE surat_id = '$h->id'")->row_array();
                                        if ($query['status'] == 'Sudah Ditandatangani' or $cekdraft['verifikasi_id'] != 0) { }else{
                                    ?>
                                        <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                        <input type="hidden" name="surat_id" value="<?php echo $h->id ?>">
                                        <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } } ?>
                                    <!-- Update @Mpik Egov 29/06/2022 -->
<!--============ [UPDATE] Fikri Egov ================ [UPDATE] Fikri Egov ============================ [UPDATE] Fikri Egov ========================= [UPDATE] Fikri Egov ================================================-->
                                    
                                </td>
                            </tr>

                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="modalVerifikasi<?php echo $h->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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