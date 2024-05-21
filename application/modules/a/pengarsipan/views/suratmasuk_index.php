<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo site_url('home/dashboard') ?>">Dashboard</a></li>
    <li><a href="javascript:void(0)">Pengarsipan</a></li>
    <li class="active">Surat Masuk</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-archive"></span> Pengarsipan Surat Masuk</h2>
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
                                <th>NO RAK</th>
                                <th>NO SAMPUL</th>
                                <th>NO BOOK</th>
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
                                <td><?php echo $h->nama_surat; ?></td>
                                <td><?php echo tanggal($h->tanggal) ?></td>
                                <td><?php echo $h->no_rak; ?></td>
                                <td><?php echo $h->no_sampul; ?></td>
                                <td><?php echo $h->no_book; ?></td>
                                <td align="center">

                                    <?php if (substr($h->surat_id, 0,2) == 'SB') { ?>
                                        <a href="<?php echo site_url('export/biasa/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SE') { ?>
                                        <a href="<?php echo site_url('export/edaran/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SU') { ?>
                                        <a href="<?php echo site_url('export/undangan/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,5) == 'PNGMN') { ?>
                                        <a href="<?php echo site_url('export/pengumuman/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'LAP') { ?>
                                        <a href="<?php echo site_url('export/laporan/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'REK') { ?>
                                        <a href="<?php echo site_url('export/rekomendasi/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'INT') { ?>
                                        <a href="<?php echo site_url('export/instruksi/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'PNG') { ?>
                                        <a href="<?php echo site_url('export/pengantar/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,5) == 'NODIN') { ?>
                                        <a href="<?php echo site_url('export/notadinas/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SK') { ?>
                                        <a href="<?php echo site_url('export/keterangan/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,3) == 'SPT') { ?>
                                        <a href="<?php echo site_url('export/perintahtugas/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
                                    <?php }elseif (substr($h->surat_id, 0,2) == 'SP') { ?>
                                        <a href="<?php echo site_url('export/perintah/'.$h->surat_id) ?>" title="Lihat Surat" target="_blank"><i class="fa fa-eye"></i></a>
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