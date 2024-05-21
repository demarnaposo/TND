<!-- START BREADCRUMB -->
<ul class="breadcrumb">
        <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
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
                    <div class="table-responsive">
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>DARI</th>
                                <th>TANGGAL</th>
                                <th>HAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                foreach ($inbox as $key1 => $p) {
                                
                                // $disposisi = $this->db->group_by("suratmasuk_id")->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $p->suratmasuk_id, 'aparatur_id' => $this->session->userdata('jabatan_id')))->result();
                                $suratmasukid = $p->suratmasuk_id;
                                $aparaturid = $this->session->userdata('jabatan_id');
                                $disposisi = $this->db->query("SELECT * FROM surat_masuk a 
                                LEFT JOIN disposisi_suratmasuk b ON b.suratmasuk_id=a.suratmasuk_id
                                WHERE a.suratmasuk_id='$suratmasukid' and b.aparatur_id='$aparaturid'
                                GROUP BY a.suratmasuk_id ORDER BY b.dsuratmasuk_id DESC")->result();
                                
                                
                                }
                                foreach ($disposisi as $key2 => $d) {
                                    
                                $qdisposisi = $this->db->query("
                                    SELECT * FROM disposisi_suratmasuk
                                    JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
                                    JOIN aparatur ON aparatur.jabatan_id = disposisi_suratmasuk.aparatur_id
                                    WHERE disposisi_suratmasuk.aparatur_id = ".$this->session->userdata('jabatan_id')."
                                    AND status = 'Belum Selesai'
                                    GROUP BY disposisi_suratmasuk.suratmasuk_id
                                    ORDER BY surat_masuk.diterima DESC, LENGTH(surat_masuk.suratmasuk_id) DESC, disposisi_suratmasuk.dsuratmasuk_id DESC
                                ")->result();
                                }
                                $no = 1;
                                foreach ($qdisposisi as $key3 => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->dari; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->hal; ?></td>
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
                                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                            AND disposisi_suratmasuk.status='Belum Selesai'
                                                        ")->result();
                                                    // $beradaTU = $this->db->query("
                                                    //         SELECT * FROM disposisi_suratmasuk 
                                                    //         JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                    //         WHERE aparatur.opd_id = '$h->opd_id' 
                                                    //         AND disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                    //         AND disposisi_suratmasuk.aparatur_id = '$atasan_id'
                                                    //     ")->row_array();
                                                    // echo "<b>".$beradaTU['nama']."</b>";
                                                    foreach($beradaTU as $key =>$tu){
                                                        echo "<b>".$tu->nama.", </b>";
                                                    }
                                                }else{
                                                    foreach ($cekAtasanJabatan->result() as $key => $j) {
                                                        $berada = $this->db->query("
                                                            SELECT * FROM disposisi_suratmasuk 
                                                            JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                            AND disposisi_suratmasuk.aparatur_id = '$j->jabatan_id'
                                                        ")->result();
                                                        foreach ($berada as $key => $b) {
                                                            echo "<b>".$b->nama."</b>, ";
                                                        }
                                                    }
                                                    // $berada = $this->db->query("
                                                    // SELECT * FROM disposisi_suratmasuk 
                                                    // JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                    // WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                    // AND disposisi_suratmasuk.status='Belum Selesai'
                                                    // ")->result();
                                                    // foreach($berada as $key => $qry){
                                                    //     echo "<b>".$qry->nama."</b>,";
                                                    // }
                                                // }
                                                }
                                            ?>
                                            <br><br>
                                            <?php
                                                $qketdis = $this->db->query("
                                                    SELECT * FROM disposisi_suratmasuk
                                                    JOIN aparatur ON disposisi_suratmasuk.users_id = aparatur.jabatan_id
                                                    JOIN users ON users.aparatur_id = aparatur.aparatur_id
                                                    WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id' AND users.level_id != 4 AND users.level_id != 18 GROUP BY disposisi_suratmasuk.users_id ORDER BY dsuratmasuk_id ASC
                                                ")->result();
                                                $nmr = 1;
                                                foreach ($qketdis as $key => $kd) {
                                            ?>

                                            <?php echo $nmr; ?>. Dari : <?php echo $kd->nama; ?> <br>
                                            Keterangan : <?php echo $kd->keterangan; ?> <br>Didisposisikan Tanggal : <?= tanggal($kd->tanggal);?><br>

                                            <?php $nmr++; } ?>
                                          
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->

                                </td>
                                
                                <td align="center">
                                    <?php if(empty($h->lampiran_lain)){?>
                                      <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Lampiran Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></i></a>
                                    <?php }else{?>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modalLihat<?php echo $h->suratmasuk_id; ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 

                                     <!-- Modal Status Surat -->
                                    <div class="modal fade" id="modalLihat<?php echo $h->suratmasuk_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
                                              </div>
                                              <div class="modal-body">
                                                <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Lampiran Surat" target="_blank" class="btn btn-warning"><i class="fa fa-file-text-o"></i> Lihat Surat</a>
                                                <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$h->lampiran_lain) ?>" title="Lihat Lampiran Lainnya" target="_blank" class="btn btn-danger"><i class="fa fa-file-text-o"></i> Lihat Lampiran</a>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal Status Surat -->
                                      <?php }?>

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$h->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a> 
                                    
				    <?php 

				    $cekDisposisi = $this->db->order_by('dsuratmasuk_id', 'DESC')->get_where('disposisi_suratmasuk', array('suratmasuk_id' => $h->suratmasuk_id, 'users_id' => $this->session->userdata('jabatan_id')))->num_rows();
				  	
				    if ($cekSelesai == 0 AND $cekDisposisi == 0) { ?>
                                    | <a href="<?php echo site_url('suratmasuk/inbox/disposisi/'.$h->suratmasuk_id) ?>" data-toggle="tooltip" data-placement="top" title="Disposisi Surat"><span class="fa-stack"><i class="fa fa-mail-forward fa-stack-2x"></i></span></a>
                                    <?php }?>

				    <?php if ($qdisposisi->num_rows() == 0) { ?>
                                    | <a href="javascript:void(0)" data-toggle="modal" data-target="#modalDiposisi<?php echo $h->dsuratmasuk_id ?>" title="Disposisi Surat"><i class="fa fa-mail-forward"></i></a>
                                    <?php }?>
                                    <br><br>
                                    <!-- Untuk levl Admin TU di Disposisi Surat -->
                                    <?php if($this->session->userdata('level')==4){?>
                                    <?php }else{ ?>
                                    <button type="button" data-toggle="modal" data-target="#modalCatatan<?php echo $h->dsuratmasuk_id ?>" class="btn btn-warning">Diterima</button>
                                    <?php }?>
                                    <!-- Untuk levl Admin TU di Disposisi Surat -->

                                    <!-- Modal Disposisi -->
                                    <div class="modal fade" id="modalCatatan<?php echo $h->dsuratmasuk_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Lihat Surat</h5>
                                          </div>
                                          <form action="<?= site_url('suratmasuk/inbox/disposisi');?>" method="post">
                                          <div class="modal-body">
                                                <label class="control-label">Catatan</label>                             
                                                <textarea type="text" name="keterangan" class="form-control"></textarea>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                            <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                            <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')">
                                          </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- End Modal Disposisi -->

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