<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Masuk</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Masuk </h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">                

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">

                    <a href="<?php echo site_url('suratmasuk/surat/add') ?>" class="btn btn-primary"><i class="fa fa-plus"></i>Tambah Surat Manual</a> <br><br>
                    <table class="table datatable table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>DARI</th>
                                <th>NOMOR SURAT</th>
                                <th>DITERIMA</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($suratmasuk as $key => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo $h->nomor; ?></td>
                                <td><?php echo tanggal($h->diterima); ?></td>
                                <td>
                                    <?php 
                                        $cekSelesai = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'status' => 'Selesai'))->num_rows();

                                        $qdisposisi = $this->db->limit(1)->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id));

                                        if ($cekSelesai == 0) {

                                            if ($qdisposisi->num_rows() == 0) {
                                    ?>

                                                <p style='color:red; text-align:center;'> SURAT BELUM DIDISPOSISIKAN </p>

                                        <?php }else{ ?>

                                                <center><a href="javascript:void()" data-toggle="modal" data-target="#modalDisposisi<?php echo $h->suratmasuk_id ?>" title="Lihat Disposisi" class="btn btn-info">Lihat</a></center>
                                    
                                    <?php 
                                            }
                                        }else{
                                    ?>
                                            <p style='color:green; text-align:center;'> SURAT SUDAH DISELESAIKAN </p>

                                    <?php } ?>


                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalDisposisi<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    
                                    <a href="<?php echo base_url('assets/surat/'.$h->lampiran) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a> 

                                    <?php
                                        $lembardisposisi = $this->db->query("
                                            SELECT * FROM disposisi_suratmasuk
                                            LEFT JOIN aparatur
                                            ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                            WHERE aparatur.opd_id = '$h->opd_id'
                                            AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                        ")->num_rows();
                                        if (!empty($lembardisposisi)) {
                                    ?>

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Lihat Lembar Disposisi" target="_blank"><i class="fa fa-file-text-o"></i></a>

                                    <?php } ?>
                                    
                                    <?php if ($cekSelesai == 0) { ?>
                                        | <a href="<?php echo site_url('suratmasuk/surat/delete/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Hapus Surat" onclick="return confirm('Apakah anda yakin akan menghapus?')"><i class="fa fa-trash-o"></i></a>
                                    <?php } ?>

                                    <?php if ($qdisposisi->num_rows() == 0) { ?>
                                        | <a href="<?php echo site_url('suratmasuk/inbox/disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Disposisi Surat"><i class="fa fa-mail-forward"></i></a>
                                    <?php } ?>

                                </td>
                            </tr>

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