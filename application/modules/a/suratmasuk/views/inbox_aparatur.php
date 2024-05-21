<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Disposisi Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Disposisi Surat </h2>
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
                                <th>DARI</th>
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
                                    
                                $qdisposisi = $this->db->query("
                                    SELECT * FROM disposisi_suratmasuk
                                    JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
                                    JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                    WHERE disposisi_suratmasuk.status = 'Belum Selesai'
                                    AND disposisi_suratmasuk.users_id = ".$d->users_id." 
                                    AND disposisi_suratmasuk.aparatur_id = ".$this->session->userdata('jabatan_id')."
                                    AND status = 'Belum Selesai'
                                    GROUP BY disposisi_suratmasuk.suratmasuk_id
                                    ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
                                ")->result();

                                $no = 1;
                                foreach ($qdisposisi as $key3 => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                
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
                                                            WHERE aparatur.opd_id = '$h->opd_id' 
                                                            AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                            AND disposisi_suratmasuk.aparatur_id = '$atasan_id'
                                                        ")->row_array();
                                                    echo "<b>".$beradaTU['nama']."</b>";
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
                                                }
                                            ?>
                                            <br><br>
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND users.level_id != 4 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?> <br><br>

                                            <?php $nmr++; } ?>
                                          
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->
                                </td>
                                
                                <td align="center">
                                    
                                    <a href="<?php echo base_url('assets/surat/'.$h->lampiran) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a> 

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><i class="fa fa-file-text-o"></i></a> 

                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $h->dsuratmasuk_id ?>" title="Disposisi Surat"><i class="fa fa-mail-forward"></i></a>

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDiposisi<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Disposisi Surat</h5>
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
                                                            <option value="<?php echo $a->jabatan_id ?>"><?php echo $a->nama.' - '.$a->nama_jabatan; ?></option>
                                                        <?php } ?>
                                                    </select> <br><br>
                                                <label class="control-label">Dengan Hormat harap</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="check"><input type="checkbox" name="harap[]" alt="Checkbox" value="Tanggapan dan Saran" /> Tanggapan dan Saran</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="check"><input type="checkbox" name="harap[]" alt="Checkbox" value="Proses lebih lanjut" /> Proses lebih lanjut</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="check"><input type="checkbox" name="harap[]" alt="Checkbox" value="Lainnya" /> Lainnya</label>
                                                    </div>
                                                </div>
                                                <br>
                                                <label class="control-label">Keterangan</label>                             
                                                <textarea type="text" name="keterangan" class="form-control"></textarea>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <input type="submit" name="disposisi" class="btn btn-primary" value="Disposisi">
                                          </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->

                                    <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post"> <br>
                                        <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                        <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                        <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                    </form>

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