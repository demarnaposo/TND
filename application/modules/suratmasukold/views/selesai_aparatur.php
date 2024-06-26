<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Beranda</a></li>                    
    <li><a href="javascript:void(0)">Naskah Dinas Surat</a></li>
    <li class="active">Surat Selesai</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-envelope"></span> Surat Selesai </h2>
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
                                <th>DITERIMA</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $no = 1;
                                $selesaiaparatur=$this->db->query("SELECT *,b.tanggal as tglsurat FROM disposisi_suratmasuk a LEFT JOIN surat_masuk b ON b.suratmasuk_id=a.suratmasuk_id WHERE a.aparatur_id='$jabatanid' AND a.status='Selesai'")->result();
                                foreach ($selesaiaparatur as $key => $s) {
                                // $query = $this->db->query("
                                //     SELECT * FROM disposisi_suratmasuk
                                //     JOIN surat_masuk ON surat_masuk.suratmasuk_id = disposisi_suratmasuk.suratmasuk_id
                                //     WHERE disposisi_suratmasuk.dsuratmasuk_id = '$h->dsuratmasuk_id'
                                //     AND surat_masuk.opd_id = '$opd_id'
                                //     AND LEFT(surat_masuk.diterima, 4) = '$tahun'
                                //     AND disposisi_suratmasuk.aparatur_id = '$jabatan_id'
                                //     GROUP BY disposisi_suratmasuk.suratmasuk_id
                                //     ORDER BY surat_masuk.diterima DESC, LENGTH(disposisi_suratmasuk.dsuratmasuk_id) DESC
                                // ")->result();
                                // foreach ($query as $key => $s) {
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $s->hal; ?></td>
                                <td><?php echo $s->dari; ?></td>
                                <td><?php echo tanggal($s->tglsurat); ?></td>
                                <td align="center">
                                    
                                    <a href="<?php echo base_url('assets/lampiransuratmasuk/'.$s->lampiran) ?>" title="Lihat Surat" target="_blank"><span class="fa-stack"><i class="fa fa-eye fa-stack-2x"></i></span></a> 

                                    | <a href="<?php echo site_url('export/lembar_disposisi/'.$s->suratmasuk_id) ?>" title="Lihat Lembar Disposisi" target="_blank"><span class="fa-stack"><i class="fa fa-file-text-o fa-stack-2x"></i></span></a> 

                                </td>
                            </tr>

                            <?php
                                $no++; 
                                }
                                // } 
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