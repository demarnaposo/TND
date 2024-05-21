<!-- START BREADCRUMB -->
<ul class="breadcrumb">
        <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Disposisi Surat</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Riwayat Disposisi </h2>
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
                                <th>HAL</th>
                                <th>DARI</th>
                                <th>TANGGAL</th>
                                <th>STATUS SURAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1;
                                foreach ($inbox as $key1 => $h) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $h->hal; ?></td>
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
                                                            LEFT JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                            LEFT JOIN users ON users.aparatur_id=aparatur.aparatur_id
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
                                                            SELECT aparatur.nama as namaaparatur,aparatur.aparatur_id FROM disposisi_suratmasuk 
                                                            LEFT JOIN aparatur ON disposisi_suratmasuk.aparatur_id = aparatur.jabatan_id
                                                            WHERE disposisi_suratmasuk.suratmasuk_id = '$h->suratmasuk_id'
                                                            AND disposisi_suratmasuk.aparatur_id = '$j->jabatan_id'
                                                        ")->result();
                                                        foreach ($berada as $key => $b) {
                                                            echo "<b>".$b->namaaparatur."</b>, ";
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
                                            Keterangan : <?php echo $kd->keterangan; ?> <br>
                                            Tanggal : <?php echo tanggal($kd->tanggal); ?> <br><br>

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
                                    
                                <?php if(empty($h->lampiran_lain)){
                                    if(substr($h->lampiran,0,2) == 'SU' || substr($h->lampiran,0,2) == 'SB' || substr($h->lampiran,0,2) == 'SE' || substr($h->lampiran,0,5) == 'PNGMN' || substr($h->lampiran,0,3) == 'LAP' || substr($h->lampiran,0,3) == 'REK' || substr($h->lampiran,0,3) == 'INT' || substr($h->lampiran,0,3) == 'PNG' || substr($h->lampiran,0,5) == 'NODIN' || substr($h->lampiran,0,2) == 'SK' || substr($h->lampiran,0,3) == 'SPT' || substr($h->lampiran,0,2) == 'SP' || substr($h->lampiran,0,3) == 'IZN' || substr($h->lampiran,0,3) == 'PJL' || substr($h->lampiran,0,3) == 'KSA' || substr($h->lampiran,0,3) == 'MKT' || substr($h->lampiran,0,3) == 'PGL' || substr($h->lampiran,0,3) == 'NTL' || substr($h->lampiran,0,3) == 'MMO'){?>
                                        <a href="<?php echo site_url('uploads/SIGNED/'.$h->lampiran) ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php }else{ ?>
                                        <a href="<?php echo site_url('assets/lampiransuratmasuk/'.$h->lampiran) ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    <?php } ?>
                                    <?php }else{?>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modalLihat<?php echo $h->suratmasuk_id; ?>" title="Lihat Surat"><span class="fa-stack"><i class="fa fa-file-pdf-o fa-stack-2x"></i></span></a> 
                                    
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
                                    <form action="<?php echo site_url('suratmasuk/inbox/disposisi') ?>" method="post"> <br>
                                        <input type="hidden" name="dsuratmasuk_id" value="<?php echo $h->dsuratmasuk_id ?>">
                                        <input type="hidden" name="suratmasuk_id" value="<?php echo $h->suratmasuk_id ?>">
                                        <!-- <input type="submit" name="selesai" data-toggle="tooltip" data-placement="top" class="btn btn-warning" value="Selesai" onclick="return confirm('Apakah anda yakin akan menyelesaikan surat ini?')"> -->
                                    </form>

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