<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Draft Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Draft Surat </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">

                    <table class="table datatable table-responsive table-bordered">
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
                                foreach ($draft as $key => $h) {
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
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Status Surat -->

                                        <?php } ?>

                                    <?php } ?>

                                </td>
                                <td align="center">

                                    <?php lihatsurat($h->surat_id); ?>

                                    <?php
                                        if ($cekDisposisi['level_id'] != 4) {
                                            if ($h->verifikasi_id != -1) {
                                                if ($this->session->userdata('level') != 4) { 
                                    ?>
                                    
                                    <?php if (substr($h->surat_id, 0,2) == 'SB') { ?>
                                        | <a href="<?php echo site_url('suratkeluar/biasa/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SE'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/edaran/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SU'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/undangan/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,5) == 'PNGMN'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/pengumuman/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'LAP'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/laporan/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'REK'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/rekomendasi/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'INT'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/instruksi/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'PNG'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/pengantar/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,5) == 'NODIN'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/notadinas/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SK'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/keterangan/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'SPT'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/perintahtugas/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SP'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/perintah/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'IZN'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/izin/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'PJL'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/perjalanan/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'KSA'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/kuasa/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'MKT'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/melaksanakan_tugas/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'PGL'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/panggilan/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'NTL'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/notulen/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'MMO'){ ?>
                                        | <a href="<?php echo site_url('suratkeluar/memo/edit/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Edit Surat"><i class="fa fa-pencil"></i></a>
                                    <?php } ?> 

                                        <?php 
                                            } 
                                            if ($h->dibuat_id == $this->session->userdata('jabatan_id')) { 
                                        ?>
                                    | <a href="<?php echo site_url('suratkeluar/biasa/delete/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i></a>
                                    <?php 
                                            }
                                            }
                                        }
                                    ?>
                                    
                                    <?php if ($h->dibuat_id != $this->session->userdata('jabatan_id') AND $this->session->userdata('level') != 4) { ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalKembalikan<?php echo $h->surat_id ?>" title="Kembalikan Surat"><i class="fa fa-mail-reply"></i></a>
                                    <?php } ?>
                                    
                                    <?php 
                                        if ($this->session->userdata('level') != 4 AND $this->session->userdata('jabatan_id') == $h->verifikasi_id OR $h->verifikasi_id == 0) {
                                            $cekLevel = $this->db->get_where('users', array('users_id' => $this->session->userdata('users_id')))->row_array();
                                            if ($this->session->userdata('level') != 11 AND $cekLevel['level_id'] != 11 AND $this->session->userdata('level') != 5 AND $cekLevel['level_id'] != 5) {
                                    ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPengajuan<?php echo $h->surat_id ?>" title="Teruskan Surat"><i class="fa fa-mail-forward"></i></a>
                                    <?php
                                            } 
                                        } 
                                    ?>

                                    <?php if ($this->session->userdata('level') == 5 OR $this->session->userdata('level') == 6 OR $this->session->userdata('level') == 9 OR $this->session->userdata('level') == 10 OR $this->session->userdata('level') == 11) { ?>
                                    <form action="<?php echo site_url('suratkeluar/draft/verify') ?>" method="post"> <br>
                                        <input type="hidden" name="surat_id" value="<?php echo $h->surat_id ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>
                                    <?php } ?>
                                    
                                    <?php if ($this->session->userdata('level') == 4) { ?>
                                    | <a href="<?php echo site_url('suratkeluar/draft/disposisi/'.$h->surat_id) ?>" data-toggle="tooltip" data-placement="bottom" title="Penomoran dan Pengarsipan Surat"><i class="fa fa-mail-forward"></i></a>
                                    <?php } ?>
                                    
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
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            <!-- END DEFAULT DATATABLE -->

        </div>
    </div>
</div>