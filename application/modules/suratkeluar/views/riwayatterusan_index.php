<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Draft Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Riwayat Terusan Surat </h2>
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
                                <th>NOMOR SURAT</th>
                                <th>PERIHAL</th>
                                <th>TANGGAL</th>
                                <th>NAMA PEMBUAT</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1; 
                                foreach ($riwayat as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->nama_surat; ?></td>
                                <!-- Update @Mpik Egov 28/06/2022 -->
                                <?php
                                if(substr($h->surat_id, 0,2) == 'SU'){
                                    echo '<td>'.$h->nomorundangan.'</td>';
                                    echo '<td>'.$h->halundangan.'</td>';
                                }elseif(substr($h->surat_id, 0,2) == 'SE'){
                                    echo '<td>'.$h->nomoredaran.'</td>';
                                    echo '<td>'.$h->haledaran.'</td>';
                                }elseif(substr($h->surat_id, 0,2) == 'SB'){
                                    echo '<td>'.$h->nomorbiasa.'</td>';
                                    echo '<td>'.$h->halbiasa.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'IZN'){
                                    echo '<td>'.$h->nomorizin.'</td>';
                                    echo '<td>'.$h->halizin.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'PGL'){
                                    echo '<td>'.$h->nomorpanggilan.'</td>';
                                    echo '<td>'.$h->halpanggilan.'</td>';
                                }elseif(substr($h->surat_id, 0,5) == 'NODIN'){
                                    echo '<td>'.$h->nomornotadinas.'</td>';
                                    echo '<td>'.$h->halnotadinas.'</td>';
                                }elseif(substr($h->surat_id, 0,5) == 'PNGMN'){
                                    echo '<td>'.$h->nomorpengumuman.'</td>';
                                    echo '<td>'.$h->halpengumuman;
                                }elseif(substr($h->surat_id, 0,3) == 'LAP'){
                                    echo '<td>'.$h->nomorlaporan.'</td>';
                                    echo '<td>'.$h->hallaporan.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'REK'){
                                    echo '<td>'.$h->nomorrekomendasi.'</td>';
                                    echo '<td>'.$h->halrekomendasi.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'NTL'){
                                    echo '<td>'.$h->nomornotulen.'</td>';
                                    echo '<td>'.$h->halnotulen.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'LMP'){
                                    echo '<td>'.$h->nomorlampiran.'</td>';
                                    echo '<td>'.$h->hallampiran.'</td>';
                                }elseif(substr($h->surat_id, 0,2) == 'SL'){
                                    echo '<td>'.$h->nomorsuratlainnya.'</td>';
                                    echo '<td>'.$h->halsuratlainnya.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'MKT'){
                                    echo '<td>'.$h->nomormelaksanakantugas.'</td>';
                                    echo '<td></td>';
                                }elseif(substr($h->surat_id, 0,3) == 'SPT'){
                                    echo '<td>'.$h->nomorperintahtugas.'</td>';
                                    echo '<td></td>';
                                }elseif(substr($h->surat_id, 0,2) == 'SK'){
                                    echo '<td>'.$h->nomorketerangan.'</td>';
                                    echo '<td>'.$h->halketerangan.'</td>';
                                }elseif(substr($h->surat_id, 0,3) == 'SKP'){
                                    echo '<td></td>';
                                    echo '<td>'.$h->halkeputusan.'</td>';
                                }else{
                                    echo '<td> - </td>';
                                    echo '<td> - </td>';
                                }
                                
                                ?>
                                <!-- END -->
                                 <!-- Update @Mpik Egov 28/06/2022 -->
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->nama . " - " . $h->nama_jabatan ?></td>
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
                                            $cekpenandatangan=$this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->surat_id'")->row_array(); // Update @Mpik Egov 28/06/2022
                                            $qverifikasi = $this->db->query("
                                                SELECT * FROM verifikasi 
                                                WHERE verifikasi.surat_id = '$h->surat_id'
                                            ")->num_rows();
                                            if (empty($qverifikasi)) {
                                                echo "<p style='color:red; text-align:center;'> KONSEP SURAT </p>";
                                            // Update @Mpik Egov 28/06/2022
                                            }elseif($cekpenandatangan['status'] == 'Belum Ditandatangani'){
                                                echo "<p style='color:red; text-align:center;'> SURAT SEDANG PROSES TTE </p>";
                                            }elseif($cekpenandatangan['status'] == 'Sudah Ditandatangani'){
                                            ?>
                                            <p style='color:green; text-align:center;'>
                                                SURAT SUDAH DI TTE<br>
                                                <center><a href="javascript:void()" data-toggle="modal" data-target="#modalStatus<?php echo $h->surat_id ?>" title="Lihat Status Surat">Lihat</a></center>
                                            </p>
                                        <?php
                                            }else{
                                        // END
                                        // Update @Mpik Egov 28/06/2022
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
                                                        WHERE draft.surat_id = '$h->surat_id' AND aparatur.statusaparatur = 'Aktif'
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
                                                    // $qketver = $this->db->order_by('verifikasi_id', 'ASC')->get_where('verifikasi', array('surat_id' => $h->surat_id))->result();
                                                    $qketver=$this->db->query("SELECT * FROM verifikasi WHERE surat_id='$h->surat_id'");
                                                    $nmr = 1;
                                                    foreach ($qketver->result() as $key => $kv) {
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
                                    <!-- Update @Mpik Egov 28/06/2022 -->
                                    <?php
                                        $ttd = $this->db->query("SELECT * FROM penandatangan WHERE surat_id='$h->surat_id'")->row_array();
                                        if ($ttd['status'] == 'Belum Ditandatangani') { // Update @Mpik Egov 28/06/2022 : Penambahan method lihat surat yang sudah TTE
                                            if (substr($h->surat_id, 0, 3) == 'LMP') {
                                                echo "";
                                            } else {
                                                lihatsurat($h->surat_id);
                                                lihatlampiransuratkeluar($h->surat_id);
                                            }
                                            
                                        // Update @Mpik Egov 28/06/2022 : Penambahan method lihat surat yang sudah di TTE
                                        }elseif($ttd['status'] == 'Sudah Ditandatangani'){
                                            lihatsurattte($h->surat_id);
                                        }else{
                                            if (substr($h->surat_id, 0, 3) == 'LMP') {
                                                echo "";
                                            } else {
                                                lihatsurat($h->surat_id);
                                            }
                                            lihatlampiransuratkeluar($h->surat_id);
                                        }
                                        // Update @Mpik Egov 28/06/2022 : Penambahan method lihat surat yang sudah di TTE
                                        ?>
                                        <!-- END -->
                                        <!-- Update @Mpik Egov 28/06/2022 -->
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