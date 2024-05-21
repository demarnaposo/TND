    <!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Draft Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat yang Sudah Ditandatangan </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">

		    <div class="table-responsive">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JENIS SURAT</th>
                                <th>TANGGAL</th>
                                <th>NAMA PEMBUAT</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($selesai as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->nama_jabatan; ?></td>
                                <td style="font-weight: bold;">
                                    
                                    <?php 
                                    $cekDisposisi = $this->db->query("
                                        SELECT * FROM users 
                                        JOIN aparatur ON users.aparatur_id = aparatur.aparatur_id 
                                        JOIN draft ON draft.verifikasi_id = aparatur.jabatan_id
                                        WHERE draft.id = '$h->surat_id'
                                    ")->row_array();

                                    if ($this->session->userdata('level') == 4) { 
                                        $ttd = $this->db->get_where('penandatangan', array('surat_id' => $h->surat_id));
                                        if (empty($ttd->num_rows())) {
                                            echo "Penomoran Surat Belum Diisi dan Penandatangan belum Dipilih";
                                        }else{
                                            foreach ($ttd->result() as $key => $t) {
                                                echo $t->status;
                                            }
                                        }

                                    }else{ 
                                            $qverifikasi = $this->db->query("
                                                SELECT * FROM verifikasi 
                                                WHERE verifikasi.surat_id = '$h->surat_id'
                                            ")->num_rows();
                                            if (empty($qverifikasi)) {
                                                echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                            }else{
                                    ?>
                                        
                                        <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->surat_id ?>" title="Lihat Status Surat" class="btn btn-info">Lihat</a></center>
                                        
                                        <!-- Modal Status Surat -->
                                        <div class="modal fade" id="modalStatus<?php echo $h->surat_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Status Surat</h5>
                                              </div>
                                              <div class="modal-body">

                                                <?php 
                                                    if ($this->session->userdata('level') != 4) { 
                                                    $berada = $this->db->query("
                                                        SELECT * FROM draft
                                                        JOIN aparatur ON aparatur.jabatan_id = draft.verifikasi_id
                                                        JOIN jabatan ON jabatan.jabatan_id = aparatur.jabatan_id
                                                        WHERE draft.surat_id = '$h->surat_id'
                                                    ")->row_array();
                                                    $sudahdiarsipkan = $this->db->get_where('draft', array('surat_id' => $h->surat_id))->row_array();
                                                    if ($berada['verifikasi_id'] == '-1' OR $sudahdiarsipkan['verifikasi_id'] == '-1') {
                                                        echo "<center>SURAT SUDAH DIARSIPKAN</center>";
                                                    }else{
                                                        echo "SURAT BERADA DI : <br>";
                                                        echo $berada['nama'].' - '.$berada['nama_jabatan']; 
                                                    } 
                                                ?>
                                                    <br><br>
                                                <?php } ?>
                                                    KETERANGAN SURAT : <br>
                                               
                                                <?php
                                                    $qketver = $this->db->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $h->surat_id))->result();
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

                                        <?php } ?>

                                    <?php } ?>

                                </td>
                                <td align="center">
					<a href="<?php echo site_url('uploads/SIGNED/'.$h-> surat_id);?>" title='Lihat Surat' target='_blank'><span class="fa-stack"><i class='fa fa-eye fa-stack-2x'></i></span></a>

                                  
                                    
                                </td>
                            </tr>

                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="modalPengajuan<?php echo $h->surat_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                        <?php 
                                            if ($this->session->userdata('level') != 4) { 
                                                echo "Teruskan Surat";
                                            }else{
                                                echo "Pengajuan Surat";
                                            }
                                        ?>
                                    </h5>
                                  </div>
                                  <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post">
                                      <div class="modal-body">
                                            <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                            <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
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

                            <!-- Modal Kembalikan -->
                            <div class="modal fade" id="modalKembalikan<?php echo $h->surat_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Kembalikan Surat</h5>
                                  </div>
                                  <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post">
                                      <div class="modal-body">
                                            <input type="hidden" name="uri_segment" value="<?php echo $this->uri->segment(2) ?>">
                                            <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                            <input type="hidden" name="jabatan_id" value="<?php echo $this->session->userdata('jabatan_id') ?>">
                                            <label class="control-label">Keterangan</label>                             
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
                            <!-- End Modal Kembalikan -->

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